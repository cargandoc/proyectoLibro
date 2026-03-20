<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Libro;
use App\Models\Genero;
use App\Models\Localizacion;
use Inertia\Inertia;

class LibroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function libro(Request $request)
    {
        $user = auth()->user();
        $credencial = $request->credencial;

        $query = $user->libro();

        switch ($credencial) {
            case 'leido':
                $query->where('estado', 'leido');
                break;

            case 'leyendo':
                $query->where('estado', 'leyendo');
                break;

            case 'pendientes':
                $query->where('estado', 'pendiente');
                break;

            case 'valorados':
                if (is_numeric($request->valor) && $request->valor >= 0 && $request->valor <= 5) {
                    $query->where('calificacion', $request->valor);
                } else {
                    $query->whereNotNull('calificacion');
                }
                break;

            case 'generos':
                if ($request->genero) {
                    $query->where(function ($q) use ($request) {
                        $q->where('genero', 'like', '%' . $request->genero . '%')
                        ->orWhereNull('genero');
                    });
                }
                break;

            case 'anhos':
                if ($request->anhio) {
                    $query->where('anho_publicacion', $request->anhio);
                }
                break;

            case 'localizacion':
                if ($request->localizacion) {
                    $query->where(function ($q) use ($request) {
                        $q->where('localizacion', 'like', '%' . $request->localizacion . '%')
                        ->orWhereNull('localizacion');
                    });
                }
                break;
        }

        if ($request->filled('nombre') && in_array($credencial, [null, 'leido', 'leyendo', 'pendientes'])) {
            $query->where('titulo', 'like', '%' . $request->nombre . '%');
        }

        $libro = $query->get();

        $libro = $libro->map(function($libro) {
            return [
                'id' => $libro->id,
                'titulo' => $libro->titulo,
                'autor' => $libro->autor,
                'anho_publicacion' => $libro->anho_publicacion,
                'editorial' => $libro->editorial,
                'resenha' => $libro->resenha,
                'paginas' => $libro->paginas,
                'estado' => $libro->estado,
                'calificacion' => $libro->calificacion,
                'inicio_lectura' => $libro->inicio_lectura,
                'fin_lectura' => $libro->fin_lectura,
                'imagen' => $libro->imagen ?? 'images/preSet.png',
                'genero' => $libro->genero ?? 'Desconocido',
                'localizacion' => $libro->localizacion ?? 'Desconocido',
            ];
        });

        return Inertia::render('Home', [
            'libros' => $libro,
            'credencial' => $credencial,
            'nombreBusqueda' => $request->nombre ?? null,
            'valor' => $request->valor ?? null,
            'genero' => $request->genero ?? null,
            'anhio' => $request->anhio ?? null,
            'localizacion' => $request->localizacion ?? null,
        ]);
    }

    public function create()
    {
        $userId = auth()->id();
        $generos = Genero::where('user_id', $userId)->get();
        $localizaciones = Localizacion::where('user_id', $userId)->get();

        return Inertia::render('librosAdd', [
            'generos' => $generos,
            'localizaciones' => $localizaciones,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'anho_publicacion' => 'required|numeric',
            'editorial' => 'required|string|max:255',
            'resenha' => 'nullable|string|max:500',
            'paginas' => 'required|numeric',
            'estado' => 'required|string|in:leido,leyendo,pendiente',
            'calificacion' => 'nullable|numeric|min:0|max:5|required_if:estado,leido',
            'inicio_lectura' => 'nullable|date|required_if:estado,leido,leyendo',
            'fin_lectura' => 'nullable|date|after_or_equal:inicio_lectura|required_if:estado,leido',
            'imagen' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'genero' => 'nullable|string|max:255',
            'localizacion' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('libro', $nombre, 'public');
            $validated['imagen'] = $ruta;
        } else {
            $validated['imagen'] = 'images/preSet.png';
        }

        $validated['user_id'] = auth()->id();
        Libro::create($validated);

        return redirect('/');
    }

    public function edit(Libro $libro)
    {
        if ($libro->user_id !== auth()->id()) abort(403);

        $userId = auth()->id();
        $generos = Genero::where('user_id', $userId)->get();
        $localizaciones = Localizacion::where('user_id', $userId)->get();

        return Inertia::render('librosEdit', [
            'libro' => $libro,
            'generos' => $generos,
            'localizaciones' => $localizaciones,
            'from' => request('from'),
        ]);
    }

    public function update(Request $request, Libro $libro)
    {
        if ($libro->user_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'anho_publicacion' => 'required|numeric',
            'editorial' => 'required|string|max:255',
            'resenha' => 'nullable|string|max:500',
            'paginas' => 'required|numeric',
            'estado' => 'required|string|in:leido,leyendo,pendiente',
            'calificacion' => 'nullable|numeric|min:0|max:5|required_if:estado,leido',
            'inicio_lectura' => 'nullable|date|required_if:estado,leido,leyendo',
            'fin_lectura' => 'nullable|date|after_or_equal:inicio_lectura|required_if:estado,leido',
            'imagen' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'genero' => 'nullable|string|max:255',
            'localizacion' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('imagen')) {
            if ($libro->imagen && $libro->imagen !== 'images/preSet.png') {
                Storage::disk('public')->delete($libro->imagen);
            }
            $file = $request->file('imagen');
            $nombre = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('libro', $nombre, 'public');
            $validated['imagen'] = $ruta;
        } else {
            $validated['imagen'] = $libro->imagen ?? 'images/preSet.png';
        }

        $libro->update($validated);

        return redirect('/');
    }

    public function destroy($id)
    {
        $libro = Libro::where('id', $id)
                      ->where('user_id', auth()->id())
                      ->firstOrFail();

        if ($libro->imagen && $libro->imagen !== 'images/preSet.png') {
            Storage::disk('public')->delete($libro->imagen);
        }

        $libro->delete();

        return redirect()->back();
    }
}