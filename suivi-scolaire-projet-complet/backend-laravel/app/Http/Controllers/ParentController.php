<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\ParentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/*
 * Contrôleur de gestion des comptes parents
 * Accessible uniquement par l'administrateur.
 * Permet de créer un compte parent et de l'associer à un ou
 * plusieurs élèves. Les identifiants sont ensuite transmis au parent,
 * qui les utilise pour se connecter depuis l'application mobile.
 */
class ParentController extends Controller
{
    public function index()
    {
        $parents = ParentUser::with('eleves.classe')->orderBy('nom')->get();
        return view('parents.index', compact('parents'));
    }

    public function create()
    {
        $eleves = Eleve::with('classe')->orderBy('nom')->get();
        return view('parents.create', compact('eleves'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'        => 'required|string|max:100',
            'prenom'     => 'required|string|max:100',
            'email'      => 'required|email|unique:parents,email',
            'telephone'  => 'nullable|string|max:30',
            'password'   => 'required|string|min:6|confirmed',
            'eleves'     => 'required|array|min:1',
            'eleves.*'   => 'exists:eleves,id',
        ]);

        $parent = ParentUser::create([
            'nom'       => $request->nom,
            'prenom'    => $request->prenom,
            'email'     => $request->email,
            'telephone' => $request->telephone,
            'password'  => Hash::make($request->password),
        ]);

        $parent->eleves()->sync($request->eleves);

        return redirect()->route('parents.index')
               ->with('success', 'Compte parent créé avec succès.');
    }

    public function edit(ParentUser $parent)
    {
        $eleves = Eleve::with('classe')->orderBy('nom')->get();
        $parent->load('eleves');
        return view('parents.edit', compact('parent', 'eleves'));
    }

    public function update(Request $request, ParentUser $parent)
    {
        $request->validate([
            'nom'       => 'required|string|max:100',
            'prenom'    => 'required|string|max:100',
            'email'     => 'required|email|unique:parents,email,' . $parent->id,
            'telephone' => 'nullable|string|max:30',
            'eleves'    => 'required|array|min:1',
            'eleves.*'  => 'exists:eleves,id',
        ]);

        $data = [
            'nom'       => $request->nom,
            'prenom'    => $request->prenom,
            'email'     => $request->email,
            'telephone' => $request->telephone,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $parent->update($data);
        $parent->eleves()->sync($request->eleves);

        return redirect()->route('parents.index')
               ->with('success', 'Compte parent mis à jour.');
    }

    public function destroy(ParentUser $parent)
    {
        // Révoque tous les tokens d'accès mobile en même temps que le compte
        $parent->tokens()->delete();
        $parent->delete();

        return redirect()->route('parents.index')
               ->with('success', 'Compte parent supprimé.');
    }
}
