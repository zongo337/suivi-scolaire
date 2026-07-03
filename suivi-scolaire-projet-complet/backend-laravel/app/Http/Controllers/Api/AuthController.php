<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EleveResource;
use App\Models\ParentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/*
 * Authentification des parents pour l'application mobile.
 * Utilise Laravel Sanctum (tokens d'accès personnels), indépendamment
 * du système de connexion web (admin/enseignant).
 */
class AuthController extends Controller
{
    /*
     * Connexion par email + mot de passe.
     * Retourne un token d'accès Sanctum à utiliser dans l'en-tête
     * Authorization: Bearer {token} pour toutes les requêtes suivantes.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $parent = ParentUser::where('email', $request->email)->first();

        if (! $parent || ! Hash::check($request->password, $parent->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email ou mot de passe incorrect.'],
            ]);
        }

        // Un seul appareil connecté à la fois : on supprime les anciens tokens.
        $parent->tokens()->delete();

        $token = $parent->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token'  => $token,
            'parent' => [
                'id'      => $parent->id,
                'nom'     => $parent->nom,
                'prenom'  => $parent->prenom,
                'email'   => $parent->email,
            ],
            'eleves' => EleveResource::collection($parent->eleves()->with('classe')->get()),
        ]);
    }

    /*
     * Déconnexion : révoque le token utilisé pour cette requête.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie.',
        ]);
    }

    /*
     * Modification du mot de passe du parent connecté.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:6|confirmed',
        ]);

        $parent = $request->user();

        if (! Hash::check($request->current_password, $parent->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Le mot de passe actuel est incorrect.'],
            ]);
        }

        $parent->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'message' => 'Mot de passe modifié avec succès.',
        ]);
    }
}
