<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    protected $fillable = [
        'nom',
        'effectif_max',
        'frais_solarite'
    ];

    public function getRouteKeyName(){
    return 'id';
    }

    # Méthode eleve c'est-à-dire une classe peut contenir plusieurs élèves
    public function eleves(){
        return $this->hasMany(Eleve::class);
    }

    public function matieres(){
    return $this->belongsToMany(Matiere::class, 'classe_matiere');
    }

    public function annonces(){
        return $this->hasMany(Annonce::class);
    }
    
    # Une m"thode qui parcours chaque eélève puit somme son montant de paiement
    #et calcul la somme totale des paiements
    public function getTotalPaiementsAttribute(){
        return $this->eleves->sum(function($eleve){
            return $eleve->paiements->sum('montant');
        });
    }

    #Méthode qui compte le nombre d'"lève qui n'ont pas encore payer puit retourne la somme total des impayés
    public function getTotalAttenduAttribute(){
        return $this->eleves->count() * $this->frais_scolarite;
    }
}
