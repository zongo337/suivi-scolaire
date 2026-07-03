<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Eleve;
use App\Models\Matiere;
use App\Models\Classe;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $classes  = Classe::all();
        $eleves   = collect();
        $classe   = null;
        $matieres = collect();

        if ($request->filled('classe_id')) {
            $classe   = Classe::findOrFail($request->classe_id);
            $matieres = $classe->matieres;
            $eleves   = Eleve::where('classe_id', $classe->id)
                ->with(['notes' => function ($q) use ($request) {
                    if ($request->filled('trimestre')) {
                        $q->where('trimestre', $request->trimestre);
                    }
                    if ($request->filled('annee_scolaire')) {
                        $q->where('annee_scolaire', $request->annee_scolaire);
                    }
                    $q->with('matiere');
                }])
                ->orderBy('nom')
                ->get();
        }

        return view('notes.index', compact('classes', 'matieres', 'eleves', 'classe'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'eleve_id'       => 'required|exists:eleves,id',
            'matiere_id'     => 'required|exists:matieres,id',
            'note'           => 'required|numeric|min:0|max:20',
            'trimestre'      => 'required|in:1,2,3',
            'annee_scolaire' => 'required|string',
        ]);

        Note::updateOrCreate(
            [
                'eleve_id'       => $request->eleve_id,
                'matiere_id'     => $request->matiere_id,
                'trimestre'      => $request->trimestre,
                'annee_scolaire' => $request->annee_scolaire,
            ],
            ['note' => $request->note]
        );

        return back()->with('success', 'Note enregistrée avec succès.');
    }

    public function storeBulk(Request $request)
    {
        $trimestre      = $request->trimestre;
        $annee_scolaire = $request->annee_scolaire;

        foreach ($request->notes as $item) {
            if (!isset($item['note']) || $item['note'] === '') continue;

            Note::updateOrCreate(
                [
                    'eleve_id'       => $item['eleve_id'],
                    'matiere_id'     => $item['matiere_id'],
                    'trimestre'      => $trimestre,
                    'annee_scolaire' => $annee_scolaire,
                ],
                ['note' => $item['note']]
            );
        }

        return back()->with('success', 'Notes enregistrées avec succès.');
    }

    public function moyennes(Request $request)
    {
        $classes   = Classe::all();
        $resultats = collect();

        if ($request->filled('classe_id')) {
            $classe = Classe::findOrFail($request->classe_id);
            $eleves = $this->calculerMoyennes($classe, $request);
            if ($eleves->count() > 0) {
                $resultats->put($classe->nom, [
                    'eleves'         => $eleves,
                    'moyenne_classe' => round($eleves->avg('moyenne_annuelle'), 2),
                    'admis'          => $eleves->filter(fn($e) => $e->moyenne_annuelle >= 5)->count(),
                    'redoublants'    => $eleves->filter(fn($e) => $e->moyenne_annuelle < 5)->count(),
                ]);
            }
        } else {
            foreach ($classes as $classe) {
                $eleves = $this->calculerMoyennes($classe, $request);
                if ($eleves->count() > 0) {
                    $resultats->put($classe->nom, [
                        'eleves'         => $eleves,
                        'moyenne_classe' => round($eleves->avg('moyenne_annuelle'), 2),
                        'admis'          => $eleves->filter(fn($e) => $e->moyenne_annuelle >= 5)->count(),
                        'redoublants'    => $eleves->filter(fn($e) => $e->moyenne_annuelle < 5)->count(),
                    ]);
                }
            }
        }

        return view('notes.moyennes', compact('classes', 'resultats'));
    }

    private function calculerMoyenneParTrimestre($eleve, $trimestre)
    {
        $notes = $eleve->notes->where('trimestre', $trimestre);

        $totalPoints = 0;
        $totalCoeff  = 0;

        foreach ($notes as $note) {
            $coeff    = $note->matiere ? $note->matiere->coefficient : 1;
            $note_sur = $note->matiere ? ($note->matiere->note_sur ?? 20) : 20;

            $note_sur_10  = ($note->note / $note_sur) * 10;
            $totalPoints += $note_sur_10 * $coeff;
            $totalCoeff  += $coeff;
        }

        return $totalCoeff > 0 ? round($totalPoints / $totalCoeff, 2) : null;
    }

    private function calculerMoyennes($classe, $request){
    return Eleve::where('classe_id', $classe->id)
        ->with(['notes.matiere', 'classe'])
        ->get()
        ->map(function ($eleve) {

            $moy_t1 = $this->calculerMoyenneParTrimestre($eleve, '1');
            $moy_t2 = $this->calculerMoyenneParTrimestre($eleve, '2');
            $moy_t3 = $this->calculerMoyenneParTrimestre($eleve, '3');

            $eleve->moy_t1 = $moy_t1;
            $eleve->moy_t2 = $moy_t2;
            $eleve->moy_t3 = $moy_t3;

            // Somme des 3 trimestres / 3
            // Si trimestre null → 0
            $t1 = $moy_t1 ?? 0;
            $t2 = $moy_t2 ?? 0;
            $t3 = $moy_t3 ?? 0;

            $eleve->moyenne_annuelle = round(($t1 + $t2 + $t3) / 3, 2);

            $eleve->decision = $eleve->moyenne_annuelle >= 5
                ? 'admis' : 'redouble';

            return $eleve;
        })
        ->sortByDesc('moyenne_annuelle')
        ->values()
        ->map(function ($eleve, $index) {
            $eleve->rang = $index + 1;
            return $eleve;
        });
}

/*
 * Génère le relevé de notes d'un élève
 * Affiche toutes les notes par trimestre et la moyenne annuelle
 */
public function releve(Eleve $eleve)
{
    // Charger toutes les données de l'élève
    $eleve->load(['classe', 'notes.matiere']);

    // Récupérer l'école active
    $ecole = \App\Models\Ecole::active();

    // Calculer les moyennes par trimestre
    $trimestres = [];
    foreach (['1', '2', '3'] as $t) {
        $notes = $eleve->notes->where('trimestre', $t);
        $totalPoints = 0;
        $totalCoeff  = 0;

        foreach ($notes as $note) {
            $coeff    = $note->matiere ? $note->matiere->coefficient : 1;
            $note_sur = $note->matiere && $note->matiere->note_sur > 0
                        ? $note->matiere->note_sur : 20;
            $note_sur_10  = ($note->note / $note_sur) * 10;
            $note_sur_10  = min($note_sur_10, 10);
            $totalPoints += $note_sur_10 * $coeff;
            $totalCoeff  += $coeff;
        }

        $trimestres[$t] = [
            'notes'   => $notes,
            'moyenne' => $totalCoeff > 0
                         ? round($totalPoints / $totalCoeff, 2)
                         : null,
        ];
    }

    // Moyenne annuelle
    $moyennes = collect($trimestres)->pluck('moyenne')->filter();
    $t1 = $trimestres['1']['moyenne'] ?? 0;
    $t2 = $trimestres['2']['moyenne'] ?? 0;
    $t3 = $trimestres['3']['moyenne'] ?? 0;
    $moyenne_annuelle = round(($t1 + $t2 + $t3) / 3, 2);
    $decision = $moyenne_annuelle >= 5 ? 'Admis(e)' : 'Redouble';

    return view('eleves.releve', compact(
        'eleve', 'trimestres', 'moyenne_annuelle', 'decision', 'ecole'
    ));
}

    public function destroy(Note $note)
    {
        $note->delete();
        return back()->with('success', 'Note supprimée.');
    }
}