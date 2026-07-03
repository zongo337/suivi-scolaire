<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\AuthorizesEleve;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaiementResource;
use App\Models\Eleve;
use App\Models\Paiement;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    use AuthorizesEleve;

    /*
     * Historique des versements, montant total payé et reste à payer.
     */
    public function index(Request $request, Eleve $eleve)
    {
        $this->eleveAppartientAuParent($eleve, $request->user());

        $eleve->load('classe');
        $paiements = $eleve->paiements()->orderByDesc('date_paiement')->get();

        return response()->json([
            'paiements'        => PaiementResource::collection($paiements),
            'total_paye'       => (float) $eleve->total_paye,
            'frais_scolarite'  => (float) ($eleve->classe?->frais_scolarite ?? 0),
            'reste_a_payer'    => (float) $eleve->reste_a_payer,
        ]);
    }

    /*
     * Reçu de paiement au format HTML, prêt à être affiché dans une
     * WebView côté mobile. L'application Android utilise le
     * gestionnaire d'impression natif d'Android (PrintManager) pour
     * proposer l'enregistrement de ce reçu en PDF, sans dépendance
     * serveur supplémentaire.
     */
    public function recu(Request $request, Eleve $eleve, Paiement $paiement)
    {
        $this->eleveAppartientAuParent($eleve, $request->user());

        abort_if($paiement->eleve_id !== $eleve->id, 404);

        $paiement->load('eleve.classe');

        return response()->view('paiements.recu', compact('paiement'));
    }
}
