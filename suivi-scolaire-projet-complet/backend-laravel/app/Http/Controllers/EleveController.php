<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EleveController extends Controller
{
    /**
     * Display a listing of the resource.
     * cette m"thode contients touts données envoyées par l'utilisateur(formulaire, URL, recherche, filtrre)
     * elle permet de filtrer par classe et rechercher un elèves par son nom ou per son prenom
     */
    public function index(Request $request){
    $classes = Classe::withCount('eleves')->get();
    $classe  = null;
    $eleves  = collect();

    if ($request->filled('classe_id')) {
        $classe = Classe::findOrFail($request->classe_id);
        $query  = Eleve::with(['classe', 'paiements'])
                    ->where('classe_id', $classe->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('prenom', 'like', "%$search%");
            });
        }

        $eleves = $query->orderBy('nom')->paginate(15);
    }

    return view('eleves.index', compact('eleves', 'classes', 'classe'));
    }

    /**
     * Afficher le formulaire de création d’une nouvelle ressource.
     */
    public function create()
    {
        $classes = Classe::all();  # Récupere toutes les classes depuits la base de données
        return view('eleves.create', compact('classes'));
    }

    /**
     * Enregistrer une nouvelle ressource dans le stockage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'              => 'required|string|max:100',
            'prenom'           => 'required|string|max:100',
            'date_naissance'   => 'required|date',
            'sexe'             => 'required|in:M,F',
            'nom_parent'       => 'required|string|max:100',
            'telephone_parent' => 'required|string|max:20',
            'classe_id'        => 'required|exists:classes,id',
            'photo'            => 'nullable|image|max:2048',
        ]);

        if ($request ->hasFile('photo')){
            $data["photo"] = $request-> file('photo')->store('photos','public');
        }

        Eleve::create($data);  # Enregistrer l'élève dans la base de données

        return redirect()->route('eleves.index')->with('success', 'Élève inscrit avec succès.');
    }

    /**
     *Afficher les détails d’un élément précis de l'eleve(classe, paiements, notes, matiere).
     */
    public function show(Eleve $eleve)
    {
        $eleve->load(['classe', 'paiements', 'notes.matiere']);
        return view('eleves.show', compact('eleve'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Eleve $eleve)
    {
        $classes = Classe::all();
        return view('eleves.edit', compact('eleve', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Eleve $eleve)
    {
        $data = $request->validate([
            'nom'              => 'required|string|max:100',
            'prenom'           => 'required|string|max:100',
            'date_naissance'   => 'required|date',
            'sexe'             => 'required|in:M,F',
            'nom_parent'       => 'required|string|max:100',
            'telephone_parent' => 'required|string|max:20',
            'classe_id'        => 'required|exists:classes,id',
            'photo'            => 'nullable|image|max:2048',

        ]);

        if ($request->hasFile('photo')){
            if ($eleve->photo)
                Storage::disk('public')->delete($eleve->photo);
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $eleve->update($data);

        return redirect()->route('eleves.index')->with('success', 'Élève mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Eleve $eleve)
    {
        if ($eleve->photo) Storage::disk('public')->delete($eleve->photo);
        $eleve->delete();
        return redirect()->route('eleves.index')->with('success','Élève supprimé.');
    }
}
