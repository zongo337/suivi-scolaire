<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Classe;
use Illuminate\Http\Request;

/*
 * Gestion des annonces et notifications diffusées aux parents
 * (réunions, examens, échéances de paiement...). Accessible uniquement
 * par l'administrateur.
 */
class AnnonceController extends Controller
{
    public function index()
    {
        $annonces = Annonce::with('classe')->orderByDesc('date_publication')->get();
        return view('annonces.index', compact('annonces'));
    }

    public function create()
    {
        $classes = Classe::all();
        return view('annonces.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre'            => 'required|string|max:150',
            'contenu'          => 'required|string',
            'type'             => 'required|in:annonce,notification',
            'classe_id'        => 'nullable|exists:classes,id',
            'date_publication' => 'nullable|date',
        ]);

        Annonce::create([
            'titre'            => $request->titre,
            'contenu'          => $request->contenu,
            'type'             => $request->type,
            'classe_id'        => $request->classe_id,
            'date_publication' => $request->date_publication ?? now(),
        ]);

        return redirect()->route('annonces.index')
               ->with('success', 'Annonce publiée avec succès.');
    }

    public function destroy(Annonce $annonce)
    {
        $annonce->delete();
        return redirect()->route('annonces.index')->with('success', 'Annonce supprimée.');
    }
}
