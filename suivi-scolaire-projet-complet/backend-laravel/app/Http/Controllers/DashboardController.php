<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Paiement;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEleves    = Eleve::count();
        $totalClasses   = Classe::count();
        $totalPaiements = Paiement::sum('montant');

        $totalAttendu = Classe::with('eleves')->get()->sum(function ($classe) {
            return $classe->eleves->count() * $classe->frais_scolarite;
        });

        $resteACollecter = $totalAttendu - $totalPaiements;

        $impayes = Eleve::with(['classe', 'paiements'])
            ->get()
            ->filter(fn($e) => $e->reste_a_payer > 0)
            ->sortByDesc('reste_a_payer')
            ->take(5);

        $derniersPaiements = Paiement::with('eleve.classe')
            ->orderBy('date_paiement', 'desc')
            ->take(5)
            ->get();

        $meilleuresMoyennes = Eleve::with(['notes.matiere', 'classe'])
            ->get()
            ->sortByDesc('moyenne')
            ->take(5);

        return view('dashboard', compact(
            'totalEleves',
            'totalClasses',
            'totalPaiements',
            'totalAttendu',
            'resteACollecter',
            'impayes',
            'derniersPaiements',
            'meilleuresMoyennes'
        ));
    }
}