<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\AuthorizesEleve;
use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use App\Models\Eleve;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    use AuthorizesEleve;

    /*
     * Liste des matières avec les notes de l'élève dans chacune d'elles,
     * ainsi que les moyennes trimestrielles (toutes matières confondues).
     * Filtrable par année scolaire via ?annee_scolaire=2025-2026
     */
    public function index(Request $request, Eleve $eleve)
    {
        $this->eleveAppartientAuParent($eleve, $request->user());

        $query = $eleve->notes()->with('matiere');

        if ($request->filled('annee_scolaire')) {
            $query->where('annee_scolaire', $request->annee_scolaire);
        }

        $notes = $query->get();

        // Regroupement par matière
        $parMatiere = $notes->groupBy('matiere_id')->map(function ($notesMatiere) {
            $matiere = $notesMatiere->first()->matiere;

            return [
                'matiere' => [
                    'id'          => $matiere->id,
                    'nom'         => $matiere->nom,
                    'coefficient' => $matiere->coefficient,
                    'note_sur'    => $matiere->note_sur,
                ],
                'notes'           => NoteResource::collection($notesMatiere->values()),
                'moyenne_matiere' => round($notesMatiere->avg('note'), 2),
            ];
        })->values();

        // Moyennes trimestrielles (pondérées par coefficient, comme la moyenne générale)
        $moyennesTrimestrielles = $notes->groupBy('trimestre')->map(function ($notesTrimestre) {
            $totalPoints = 0;
            $totalCoeff  = 0;

            foreach ($notesTrimestre as $note) {
                $coeff = $note->matiere->coefficient ?? 1;
                $totalPoints += $note->note * $coeff;
                $totalCoeff  += $coeff;
            }

            return $totalCoeff > 0 ? round($totalPoints / $totalCoeff, 2) : 0;
        })->sortKeys();

        return response()->json([
            'matieres'               => $parMatiere,
            'moyennes_trimestrielles' => $moyennesTrimestrielles,
        ]);
    }
}
