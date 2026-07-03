<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\AuthorizesEleve;
use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use App\Models\Eleve;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use AuthorizesEleve;

    /*
     * Tableau de bord d'un élève :
     * - informations de base (nom, prénom, photo, classe)
     * - moyenne générale
     * - rang dans la classe
     * - résumé des dernières notes obtenues
     */
    public function show(Request $request, Eleve $eleve)
    {
        $this->eleveAppartientAuParent($eleve, $request->user());

        $eleve->load(['classe', 'notes.matiere']);

        return response()->json([
            'eleve' => [
                'id'             => $eleve->id,
                'nom'            => $eleve->nom,
                'prenom'         => $eleve->prenom,
               'photo_url' => $eleve->photo
    ? (str_starts_with($eleve->photo, 'http') 
        ? $eleve->photo 
        : url('storage/' . $eleve->photo))
    : null,
                'classe'         => $eleve->classe?->nom,
                'moyenne_generale' => $eleve->moyenne,
                'rang'           => $this->calculerRang($eleve),
                'effectif_classe' => $eleve->classe?->eleves()->count(),
            ],
            'dernieres_notes' => NoteResource::collection(
                $eleve->notes()->with('matiere')->latest()->take(5)->get()
            ),
        ]);
    }

    /*
     * Calcule le rang de l'élève au sein de sa classe en comparant
     * la moyenne générale de chaque élève de la même classe.
     */
    private function calculerRang(Eleve $eleve): ?int
    {
        if (! $eleve->classe) {
            return null;
        }

        $eleves = $eleve->classe->eleves()->with('notes.matiere')->get();

        $classement = $eleves
            ->sortByDesc(fn (Eleve $e) => $e->moyenne)
            ->values();

        $position = $classement->search(fn (Eleve $e) => $e->id === $eleve->id);

        return $position === false ? null : $position + 1;
    }
}
