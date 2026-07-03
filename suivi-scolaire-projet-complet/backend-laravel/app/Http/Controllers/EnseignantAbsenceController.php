<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Eleve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
 * Gestion des absences pour l'enseignant — restreinte à SA classe.
 * Même logique de sécurité que EnseignantNoteController.
 */
class EnseignantAbsenceController extends Controller
{
    public function index()
    {
        $enseignant = Auth::user();
        $classe = $enseignant->classe;

        if (!$classe) {
            return view('enseignant.absences', ['classe' => null, 'absences' => collect(), 'eleves' => collect()]);
        }

        $absences = Absence::with('eleve')
            ->whereHas('eleve', fn ($q) => $q->where('classe_id', $classe->id))
            ->orderByDesc('date_absence')
            ->get();

        $eleves = Eleve::where('classe_id', $classe->id)->orderBy('nom')->get();

        return view('enseignant.absences', compact('classe', 'absences', 'eleves'));
    }

    public function store(Request $request)
    {
        $enseignant = Auth::user();
        $classe = $enseignant->classe;

        $request->validate([
            'eleve_id'     => 'required|exists:eleves,id',
            'date_absence' => 'required|date',
            'motif'        => 'nullable|string|max:255',
            'justifiee'    => 'nullable|boolean',
        ]);

        // Sécurité : l'élève doit appartenir à la classe de l'enseignant
        $eleve = Eleve::find($request->eleve_id);
        if (!$classe || !$eleve || $eleve->classe_id !== $classe->id) {
            abort(403, 'Accès refusé — cet élève n\'appartient pas à votre classe.');
        }

        Absence::create([
            'eleve_id'     => $request->eleve_id,
            'date_absence' => $request->date_absence,
            'motif'        => $request->motif,
            'justifiee'    => $request->boolean('justifiee'),
        ]);

        return back()->with('success', 'Absence enregistrée avec succès.');
    }

    public function destroy(Absence $absence)
    {
        $enseignant = Auth::user();

        if ($absence->eleve->classe_id !== $enseignant->classe_id) {
            abort(403, 'Accès refusé — cette absence ne concerne pas votre classe.');
        }

        $absence->delete();
        return back()->with('success', 'Absence supprimée.');
    }
}
