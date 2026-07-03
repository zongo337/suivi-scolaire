<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/*
 * Modèle ParentUser — représente un compte parent.
 * Distinct du modèle User (admin/enseignant) : un parent se connecte
 * uniquement via l'API mobile (token Sanctum), jamais sur l'espace web.
 *
 * La table s'appelle "parents" ; le modèle n'est pas nommé "Parent"
 * pour éviter toute confusion avec le mot-clé PHP `parent::`.
 */
class ParentUser extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'parents';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /*
     * Un parent peut suivre plusieurs élèves (ses enfants).
     */
    public function eleves()
    {
        return $this->belongsToMany(Eleve::class, 'eleve_parent', 'parent_id', 'eleve_id');
    }
}
