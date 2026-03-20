<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Localizacion;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LocalizacionController extends Controller
{
    public function index()
    {
        $localizaciones = Localizacion::all();

        return Inertia::render('localizacionList', [
            'localizaciones' => $localizaciones
        ]);
    }

    public function create()
    {
        return Inertia::render('localizacionAdd');
    }

    public function store(Request $request)
    {
        $request->validate([
            'lugar' => 'required|string|max:255',
        ]);

        Localizacion::create([
            'lugar' => $request->lugar,
            'user_id' => auth()->id(),
        ]);

        return redirect('/localizaciones')->with('success', 'Localización creada correctamente');
    }

    public function edit(Localizacion $localizacion)
    {
        return Inertia::render('localizacionEdit', [
            'localizacion' => $localizacion,
        ]);
    }

    public function update(Request $request, Localizacion $localizacion)
    {
        $request->validate([
            'lugar' => 'required|string|max:255',
        ]);

        $localizacion->update([
            'lugar' => $request->lugar,
        ]);

        return redirect('/localizaciones')->with('success', 'Localización actualizada correctamente');
    }

    public function destroy($id)
    {
        $localizacion = Localizacion::findOrFail($id);
        $localizacion->delete();

        return redirect('/localizaciones')->with('success', 'Localización eliminada correctamente');
    }
}