<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/*
 * Modèle User — représente les utilisateurs de l'application
 * Deux rôles possibles :
 *   - admin      : accès total à toute l'application
 *   - enseignant : accès uniquement aux notes de sa classe assignée
 */
#[Fillable(['name', 'email', 'password', 'role', 'classe_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /*
     * Conversions automatiques des types
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /*
     * Vérifie si l'utilisateur est administrateur
     * Admin = accès total à toute l'application
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /*
     * Vérifie si l'utilisateur est enseignant
     * Enseignant = accès uniquement aux notes de sa classe
     */
    public function isEnseignant(): bool
    {
        return $this->role === 'enseignant';
    }

    /*
     * Relation : un enseignant appartient à une classe
     * Un admin n'a pas de classe (classe_id = null)
     */
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }
}