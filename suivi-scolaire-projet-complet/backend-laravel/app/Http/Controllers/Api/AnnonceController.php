<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnnonceResource;
use App\Models\Annonce;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    /*
     * Annonces générales de l'école + notifications importantes
     * (examens, réunions, échéances de paiement), filtrées sur les
     * classes des enfants du parent connecté ainsi que les annonces
     * générales (classe_id = null).
     * Filtrable par type via ?type=annonce ou ?type=notification
     */
    public function index(Request $request)
    {
        $classesIds = $request->user()->eleves()->pluck('classe_id')->unique();

        $query = Annonce::with('classe')
            ->where(function ($q) use ($classesIds) {
                $q->whereNull('classe_id')
                  ->orWhereIn('classe_id', $classesIds);
            })
            ->orderByDesc('date_publication');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        return AnnonceResource::collection($query->get());
    }
}
