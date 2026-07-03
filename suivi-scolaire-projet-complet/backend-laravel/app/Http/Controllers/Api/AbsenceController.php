<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\AuthorizesEleve;
use App\Http\Controllers\Controller;
use App\Http\Resources\AbsenceResource;
use App\Models\Eleve;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    use AuthorizesEleve;

    /*
     * Liste des absences de l'élève, motifs inclus lorsqu'ils sont renseignés.
     */
    public function index(Request $request, Eleve $eleve)
    {
        $this->eleveAppartientAuParent($eleve, $request->user());

        $absences = $eleve->absences()->orderByDesc('date_absence')->get();

        return AbsenceResource::collection($absences);
    }
}
