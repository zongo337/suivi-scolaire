<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EleveResource;
use Illuminate\Http\Request;

class EleveController extends Controller
{
    /*
     * Liste des enfants du parent connecté.
     * Utilisé pour le sélecteur d'enfant lorsqu'un parent a plusieurs
     * enfants scolarisés dans l'établissement.
     */
    public function index(Request $request)
    {
        $eleves = $request->user()->eleves()->with('classe')->orderBy('nom')->get();

        return EleveResource::collection($eleves);
    }
}
