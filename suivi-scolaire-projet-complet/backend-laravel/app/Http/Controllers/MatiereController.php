<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use App\Models\Classe;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    public function index()
    {
        $classes  = Classe::with('matieres')->get();
        $matieres = Matiere::all();
        return view('matieres.index', compact('classes', 'matieres'));
    }

    public function create()
    {
        $classes = Classe::all();
        return view('matieres.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'         => 'required|string|max:100',
            'coefficient' => 'required|numeric|min:0.5|max:10',
            'classes'     => 'required|array',
            'classes.*'   => 'exists:classes,id',
        ]);

        $matiere = Matiere::create([
            'nom'         => $data['nom'],
            'coefficient' => $data['coefficient'],
        ]);

        // Associer aux classes sélectionnées
        $matiere->classes()->sync($request->classes);

        return redirect()->route('matieres.index')
               ->with('success', 'Matière créée avec succès.');
    }

    public function edit(Matiere $matiere)
    {
        $classes = Classe::all();
        $classesAssociees = $matiere->classes->pluck('id')->toArray();
        return view('matieres.edit', compact('matiere', 'classes', 'classesAssociees'));
    }

    public function update(Request $request, Matiere $matiere)
    {
        $data = $request->validate([
            'nom'         => 'required|string|max:100',
            'coefficient' => 'required|numeric|min:0.5|max:10',
            'note_sur'    => 'required|integer|in:10,20',
            'classes'     => 'required|array',
            'classes.*'   => 'exists:classes,id',
        ]);
        $data['note_sur'] = $request->note_sur ?? 10;   //forcer les anciennes matières à revenir /10 par defaut
        Matiere::create($data);

        $matiere->update([
            'nom'         => $data['nom'],
            'coefficient' => $data['coefficient'],
            'note_sur'    => $request->note_sur,
        ]);

        // Mettre à jour les classes associées
        $matiere->classes()->sync($request->classes);

        return redirect()->route('matieres.index')
               ->with('success', 'Matière mise à jour.');
    }

    public function destroy(Matiere $matiere)
    {
        $matiere->classes()->detach();
        $matiere->delete();
        return redirect()->route('matieres.index')
               ->with('success', 'Matière supprimée.');
    }
}