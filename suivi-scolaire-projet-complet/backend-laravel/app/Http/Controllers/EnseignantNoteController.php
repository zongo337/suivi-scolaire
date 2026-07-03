<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Eleve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
 * Contrôleur des notes pour les enseignants
 *
 * RÈGLES DE SÉCURITÉ :
 * - Un enseignant ne voit QUE les notes de SA classe
 * - Un enseignant ne peut PAS inscrire des élèves
 * - Un enseignant ne peut PAS gérer les paiements
 * - Toute tentative d'accès à une autre classe est bloquée (403)
 */
class EnseignantNoteController extends Controller
{
    /*
     * Retourne l'enseignant connecté
     */
    private function enseignant()
    {
        return Auth::user();
    }

    /*
     * Affiche la page de saisie des notes
     * Seules les notes de la classe de l'enseignant sont affichées
     */
    public function index(Request $request)
    {
        $enseignant = $this->enseignant();

        /*
         * Récupérer la classe assignée à l'enseignant
         * Si aucune classe → afficher un message d'avertissement
         */
        $classe = $enseignant->classe;

        if (!$classe) {
            return view('enseignant.notes', [
                'classe'     => null,
                'eleves'     => collect(),
                'matieres'   => collect(),
                'enseignant' => $enseignant,
            ]);
        }

        // Matières associées à cette classe uniquement
        $matieres = $classe->matieres;

        // Élèves de la classe avec leurs notes filtrées
        $eleves = Eleve::where('classe_id', $classe->id)
            ->with(['notes' => function ($q) use ($request) {
                // Filtrer par trimestre si sélectionné
                if ($request->filled('trimestre')) {
                    $q->where('trimestre', $request->trimestre);
                }
                // Filtrer par année scolaire si sélectionnée
                if ($request->filled('annee_scolaire')) {
                    $q->where('annee_scolaire', $request->annee_scolaire);
                }
                $q->with('matiere');
            }])
            ->orderBy('nom')
            ->get();

        return view('enseignant.notes', compact(
            'classe', 'eleves', 'matieres', 'enseignant'
        ));
    }

    /*
     * Enregistre les notes en masse
     * SÉCURITÉ : vérifie que chaque élève appartient à la classe de l'enseignant
     */
    public function storeBulk(Request $request)
    {
        $enseignant = $this->enseignant();
        $classe     = $enseignant->classe;

        if (!$classe) {
            return back()->withErrors(['error' => 'Aucune classe assignée.']);
        }

        $trimestre      = $request->trimestre;
        $annee_scolaire = $request->annee_scolaire;

        foreach ($request->notes ?? [] as $item) {
            // Ignorer les notes vides
            if (!isset($item['note']) || $item['note'] === '') continue;

            /*
             * SÉCURITÉ : Vérifier que l'élève appartient
             * bien à la classe de l'enseignant connecté
             */
            $eleve = Eleve::find($item['eleve_id']);
            if (!$eleve || $eleve->classe_id !== $classe->id) {
                continue; // Bloquer silencieusement les tentatives d'accès
            }

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

    /*
     * Supprime une note
     * SÉCURITÉ : vérifie que la note appartient à la classe de l'enseignant
     */
    public function destroy(Note $note)
    {
        $enseignant = $this->enseignant();

        /*
         * Bloquer l'accès si la note n'appartient pas
         * à un élève de la classe de l'enseignant
         */
        if ($note->eleve->classe_id !== $enseignant->classe_id) {
            abort(403, 'Accès refusé — cette note ne vous appartient pas.');
        }

        $note->delete();
        return back()->with('success', 'Note supprimée.');
    }

    /*
 * Calcule et affiche les moyennes des élèves de la classe de l'enseignant
 * Calcule : moyenne par trimestre, moyenne annuelle, décision admis/redouble
 * et la moyenne générale de la classe
 */
public function moyennes(Request $request)
{
    $enseignant = $this->enseignant();
    $classe     = $enseignant->classe;

    if (!$classe) {
        return view('enseignant.moyennes', [
            'classe'     => null,
            'eleves'     => collect(),
            'enseignant' => $enseignant,
            'data'       => null,
        ]);
    }

    // Calculer les moyennes de tous les élèves de la classe
    $eleves = Eleve::where('classe_id', $classe->id)
        ->with(['notes.matiere'])
        ->get()
        ->map(function ($eleve) {

            // Moyenne par trimestre
            $moy_t1 = $this->calculerMoyenneParTrimestre($eleve, '1');
            $moy_t2 = $this->calculerMoyenneParTrimestre($eleve, '2');
            $moy_t3 = $this->calculerMoyenneParTrimestre($eleve, '3');

            $eleve->moy_t1 = $moy_t1;
            $eleve->moy_t2 = $moy_t2;
            $eleve->moy_t3 = $moy_t3;

            // Moyenne annuelle = (T1 + T2 + T3) / 3
            $t1 = $moy_t1 ?? 0;
            $t2 = $moy_t2 ?? 0;
            $t3 = $moy_t3 ?? 0;

            $eleve->moyenne_annuelle = round(($t1 + $t2 + $t3) / 3, 2);

            // Décision
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

    // Données globales de la classe
    $data = [
        'eleves'         => $eleves,
        'moyenne_classe' => round($eleves->avg('moyenne_annuelle'), 2),
        'admis'          => $eleves->filter(fn($e) => $e->moyenne_annuelle >= 5)->count(),
        'redoublants'    => $eleves->filter(fn($e) => $e->moyenne_annuelle < 5)->count(),
    ];

    return view('enseignant.moyennes', compact('classe', 'eleves', 'enseignant', 'data'));
}

/*
 * Calcule la moyenne d'un élève pour un trimestre donné
 * Ramène toutes les notes sur 10
 */
private function calculerMoyenneParTrimestre($eleve, $trimestre)
{
    $notes = $eleve->notes->where('trimestre', $trimestre);

    if ($notes->isEmpty()) return null;

    $totalPoints = 0;
    $totalCoeff  = 0;

    foreach ($notes as $note) {
        $coeff    = $note->matiere ? $note->matiere->coefficient : 1;
        $note_sur = $note->matiere && $note->matiere->note_sur > 0
                    ? $note->matiere->note_sur : 20;

        // Ramener sur 10
        $note_sur_10  = ($note->note / $note_sur) * 10;
        $note_sur_10  = min($note_sur_10, 10);

        $totalPoints += $note_sur_10 * $coeff;
        $totalCoeff  += $coeff;
    }

    $moyenne = $totalCoeff > 0 ? round($totalPoints / $totalCoeff, 2) : null;
    return $moyenne !== null ? min($moyenne, 10) : null;
}

/*
 * Relevé de notes — enseignant voit uniquement sa classe
 */
public function releve(Eleve $eleve)
{
    $enseignant = $this->enseignant();

    // Sécurité : vérifier que l'élève appartient à la classe de l'enseignant
    if ($eleve->classe_id !== $enseignant->classe_id) {
        abort(403, 'Accès refusé.');
    }

    $eleve->load(['classe', 'notes.matiere']);
    $ecole = \App\Models\Ecole::active();

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

    $t1 = $trimestres['1']['moyenne'] ?? 0;
    $t2 = $trimestres['2']['moyenne'] ?? 0;
    $t3 = $trimestres['3']['moyenne'] ?? 0;
    $moyenne_annuelle = round(($t1 + $t2 + $t3) / 3, 2);
    $decision = $moyenne_annuelle >= 5 ? 'Admis(e)' : 'Redouble';

    return view('eleves.releve', compact(
        'eleve', 'trimestres', 'moyenne_annuelle', 'decision', 'ecole'
    ));
}
}