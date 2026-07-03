<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Classe;
use App\Models\Eleve;
use Illuminate\Http\Request;

/*
 * Contrôleur de gestion des absences — accessible par l'administrateur,
 * toutes classes confondues. Voir EnseignantAbsenceController pour la
 * version restreinte à la classe de l'enseignant connecté.
 */
class AbsenceController extends Controller
{
    public function index(Request $request)
    {
        $classes = Classe::all();
        $query = Absence::with('eleve.classe')->orderByDesc('date_absence');

        if ($request->filled('classe_id')) {
            $query->whereHas('eleve', function ($q) use ($request) {
                $q->where('classe_id', $request->classe_id);
            });
        }

        $absences = $query->get();

        return view('absences.index', compact('absences', 'classes'));
    }

    public function create()
    {
        $eleves = Eleve::with('classe')->orderBy('nom')->get();
        return view('absences.create', compact('eleves'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'eleve_id'     => 'required|exists:eleves,id',
            'date_absence' => 'required|date',
            'motif'        => 'nullable|string|max:255',
            'justifiee'    => 'nullable|boolean',
        ]);

        Absence::create([
            'eleve_id'     => $request->eleve_id,
            'date_absence' => $request->date_absence,
            'motif'        => $request->motif,
            'justifiee'    => $request->boolean('justifiee'),
        ]);

        return redirect()->route('absences.index')
               ->with('success', 'Absence enregistrée avec succès.');
    }

    public function destroy(Absence $absence)
    {
        $absence->delete();
        return redirect()->route('absences.index')->with('success', 'Absence supprimée.');
    }
}
