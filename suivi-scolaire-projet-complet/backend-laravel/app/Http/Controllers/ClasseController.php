<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = Classe::withCount('eleves')->get();
        return view('classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('classes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'             => 'required|string|max:50|unique:classes',
            'effectif_max'    => 'required|integer|min:1',
            'frais_scolarite' => 'required|numeric|min:0',
        ]);

        Classe::create($data);
        return redirect()->route('classes.index')->with('success', 'Classe créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classe $classe)
    {
        $classe->load(['eleves.paiements', 'eleves.notes.matiere']);
        $eleves=$classe->eleves->map(function($eleve){
            $eleve->moyenne_calculee=$eleve->moyenne;
            return $eleve;
        })->sortByDesc('moyenne_calculee');

        return view('classes.show', compact('classe', 'eleves'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classe $classe)
    {
        return view('classes.edit', compact('classe'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classe $classe)
    {
        $data = $request->validate([
            'nom'             => 'required|string|max:50|unique:classes,nom,' . $classe->id,
            'effectif_max'    => 'required|integer|min:1',
            'frais_scolarite' => 'required|numeric|min:0',
        ]);

        $classe->update($data);
        return redirect()->route('classes.index')->with('success', 'Classe mise à jour');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classe $classe)
    {
        $classe->delete();
        return redirect()->route('classes.index')->with('success', 'Classe supprimée.');
    }
}
