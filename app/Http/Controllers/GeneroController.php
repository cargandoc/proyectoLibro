<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Genero;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class GeneroController extends Controller
{
    public function index()
    {
        $generos = Genero::all();

        return Inertia::render('generoList', [
            'generos' => $generos
        ]);
    }

    public function create()
    {
        return Inertia::render('generoAdd');
    }

    public function store(Request $request)
    {
        $request->validate([
            'generoLit' => 'required|string|max:255',
        ]);

        Genero::create([
            'generoLit' => $request->generoLit,
            'user_id' => auth()->id(),
        ]);

        return redirect('/generos')->with('success', 'Género creado correctamente');
    }

    public function edit(Genero $genero)
    {
        return Inertia::render('generoEdit', [
            'genero' => $genero,
        ]);
    }

    public function update(Request $request, Genero $genero)
    {
        $request->validate([
            'generoLit' => 'required|string|max:255',
        ]);

        $genero->update([
            'generoLit' => $request->generoLit,
        ]);

        return redirect('/generos')->with('success', 'Género actualizado correctamente');
    }

    public function destroy($id)
    {
        $genero = Genero::findOrFail($id);
        $genero->delete();

        return redirect('/generos')->with('success', 'Género eliminado correctamente');
    }
}