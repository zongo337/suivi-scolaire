<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/*
 * Contrôleur de gestion des enseignants
 * Accessible uniquement par l'administrateur
 * Permet de créer, modifier, supprimer les comptes enseignants
 */
class EnseignantController extends Controller
{
    /*
     * Liste tous les enseignants
     */
    public function index()
    {
        // Récupérer uniquement les users avec rôle enseignant
        $enseignants = User::where('role', 'enseignant')
                           ->with('classe')
                           ->get();
        return view('enseignants.index', compact('enseignants'));
    }

    /*
     * Formulaire de création d'un enseignant
     */
    public function create()
    {
        $classes = Classe::all();
        return view('enseignants.create', compact('classes'));
    }

    /*
     * Enregistre un nouvel enseignant
     * Le rôle est fixé à 'enseignant' automatiquement
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|string|min:6|confirmed',
            'classe_id'             => 'required|exists:classes,id',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'enseignant', // Rôle fixé automatiquement
            'classe_id' => $request->classe_id,
        ]);

        return redirect()->route('enseignants.index')
               ->with('success', 'Enseignant créé avec succès.');
    }

    /*
     * Formulaire de modification d'un enseignant
     */
    public function edit(User $enseignant)
    {
        // Vérifier que c'est bien un enseignant
        abort_if($enseignant->isAdmin(), 403);
        $classes = Classe::all();
        return view('enseignants.edit', compact('enseignant', 'classes'));
    }

    /*
     * Met à jour un enseignant
     */
    public function update(Request $request, User $enseignant)
    {
        abort_if($enseignant->isAdmin(), 403);

        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $enseignant->id,
            'classe_id' => 'required|exists:classes,id',
        ]);

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'classe_id' => $request->classe_id,
        ];

        // Changer le mot de passe seulement si fourni
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $enseignant->update($data);

        return redirect()->route('enseignants.index')
               ->with('success', 'Enseignant mis à jour.');
    }

    /*
     * Supprime un enseignant
     */
    public function destroy(User $enseignant)
    {
        abort_if($enseignant->isAdmin(), 403);
        $enseignant->delete();
        return redirect()->route('enseignants.index')
               ->with('success', 'Enseignant supprimé.');
    }
}