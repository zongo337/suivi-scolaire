<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
 * Contrôleur d'authentification
 * Gère la connexion pour admin ET enseignant
 * La redirection se fait selon le rôle
 */
class AuthController extends Controller
{
    /*
     * Affiche le formulaire de connexion
     */
    public function showLogin()
    {
        // Si déjà connecté → rediriger selon le rôle
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('dashboard')
                : redirect()->route('enseignant.notes');
        }
        return view('auth.login');
    }

    /*
     * Traite la connexion
     * Admin → dashboard
     * Enseignant → page de saisie des notes de sa classe
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Rediriger selon le rôle
            return $user->isAdmin()
                ? redirect()->route('dashboard')
                : redirect()->route('enseignant.notes');
        }

        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect.',
        ])->onlyInput('email');
    }

    /*
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}