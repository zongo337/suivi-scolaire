<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Eleve;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Classe;

class PaiementController extends Controller
{
    public function index(Request $request){
    $classes = Classe::all();
    $query   = Paiement::with('eleve.classe')->orderBy('date_paiement', 'desc');

    if ($request->filled('classe_id')) {
        $query->whereHas('eleve', function ($q) use ($request) {
            $q->where('classe_id', $request->classe_id);
        });
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('eleve', function ($q) use ($search) {
            $q->where('nom', 'like', "%$search%")
              ->orWhere('prenom', 'like', "%$search%");
        });
    }

    // Grouper par classe
    $paiementsParClasse = $query->get()->groupBy(function ($paiement) {
        return $paiement->eleve->classe->nom;
    })->sortKeys();

    return view('paiements.index', compact('paiementsParClasse', 'classes'));
    }

    public function create()
    {
        $eleves = Eleve::with('classe')->orderBy('nom')->get();
        return view('paiements.create', compact('eleves'));
    }
    public function recu(Paiement $paiement){
    $paiement->load('eleve.classe');
    return view('paiements.recu', compact('paiement'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'eleve_id'      => 'required|exists:eleves,id',
            'montant'       => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
            'observation'   => 'nullable|string',
        ]);

        $data['reference'] = 'PAY-' . strtoupper(Str::random(8));

        Paiement::create($data);

        return redirect()->route('paiements.index')->with('success', 'Paiement enregistré avec succès.');
    }

    public function show(Paiement $paiement){
        $paiement->load('eleve.classe');
        return view('paiements.show', compact('paiement'));
    }

    public function destroy(Paiement $paiement)
    {
        $paiement->delete();
        return redirect()->route('paiements.index')->with('success', 'Paiement supprimé.');
    }

    public function impayes(Request $request){
    $classes = Classe::all();

    $query = Eleve::with(['classe', 'paiements']);

    if ($request->filled('classe_id')) {
        $query->where('classe_id', $request->classe_id);
    }

    $eleves = $query->get()
        ->filter(fn($e) => $e->reste_a_payer > 0)
        ->sortByDesc('reste_a_payer');

    return view('paiements.impayes', compact('eleves', 'classes'));
    }
}