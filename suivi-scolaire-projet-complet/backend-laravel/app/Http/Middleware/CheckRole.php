<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
 * Middleware de vérification des rôles
 * Redirige l'utilisateur selon son rôle
 */
class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Vérifier le rôle demandé
        if ($user->role !== $role) {
            /*
             * Un admin qui accède à une route enseignant
             * → rediriger vers le dashboard admin
             */
            if ($user->isAdmin()) {
                return redirect()->route('dashboard');
            }

            /*
             * Un enseignant qui accède à une route admin
             * → rediriger vers ses notes
             */
            return redirect()->route('enseignant.notes');
        }

        return $next($request);
    }
}