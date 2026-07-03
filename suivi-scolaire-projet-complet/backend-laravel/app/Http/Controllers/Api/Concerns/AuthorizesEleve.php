<?php

namespace App\Http\Controllers\Api\Concerns;

use App\Models\Eleve;
use App\Models\ParentUser;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/*
 * Évite qu'un parent puisse consulter les données d'un élève qui n'est
 * pas le sien simplement en changeant l'identifiant dans l'URL.
 */
trait AuthorizesEleve
{
    protected function eleveAppartientAuParent(Eleve $eleve, ParentUser $parent): void
    {
        if (! $parent->eleves()->where('eleves.id', $eleve->id)->exists()) {
            throw new AccessDeniedHttpException('Cet élève ne fait pas partie de vos enfants.');
        }
    }
}
