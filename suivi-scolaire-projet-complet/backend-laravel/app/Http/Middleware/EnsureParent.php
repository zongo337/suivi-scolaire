<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/*
 * Sécurité supplémentaire : s'assure que le token Sanctum utilisé
 * appartient bien à un compte parent (ParentUser) et non à un compte
 * admin/enseignant. Les comptes admin/enseignant n'émettent jamais de
 * token API dans cette application, mais ce garde-fou évite toute
 * mauvaise surprise si cela changeait un jour.
 */
class EnsureParent
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user() instanceof \App\Models\ParentUser) {
            return response()->json([
                'message' => 'Accès réservé aux comptes parents.',
            ], 403);
        }

        return $next($request);
    }
}
