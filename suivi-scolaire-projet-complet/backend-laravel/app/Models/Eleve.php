<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    protected $fillable = [
        'nom', 
        'prenom', 
        'date_naissance', 
        'sexe', 
        'photo', 
        'nom_parent', 
        'telephone_parent', 
        'classe_id'
    ];
    public function classe(){
        return $this -> belongsTo(Classe::class);
    }
    public function paiements(){
        return $this->hasMany(Paiement::class);
    }
    public function notes(){
        return $this->hasMany(Note::class);
    }
    public function absences(){
        return $this->hasMany(Absence::class);
    }

    /*
     * Comptes parents pouvant suivre cet élève depuis l'application mobile.
     */
    public function parents(){
        return $this->belongsToMany(ParentUser::class, 'eleve_parent', 'eleve_id', 'parent_id');
    }
    public function getTotalPayeAttribute()
{
    // relationLoaded vérifie si les paiements sont chargés
    if ($this->relationLoaded('paiements')) {
        return $this->paiements->sum('montant');
    }
    return $this->paiements()->sum('montant');
}

public function getResteAPayerAttribute()
{
    $frais = $this->relationLoaded('classe')
        ? $this->classe->frais_scolarite
        : $this->classe()->value('frais_scolarite');

    return $frais - $this->total_paye;
}
    
    # fonction qui calcule la moyenne
    public function getMoyenneAttribute(){
    $notes = $this->notes;
    if ($notes->isEmpty())
        return 0;
    $totalPoints = 0;
    $totalCoeff = 0;
    foreach ($notes as $note) {
        $coeff     = $note->matiere->coefficient ?? 1;
        $noteSur   = $note->matiere->note_sur ?? 10;
        // Ramène toujours la note sur 10
        $noteSur10 = ($note->note / $noteSur) * 10;
        $totalPoints += $noteSur10 * $coeff;
        $totalCoeff  += $coeff;
    }
    return $totalCoeff > 0 ? round($totalPoints / $totalCoeff, 2) : 0;
}
    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function getRouteKeyName(){
        return 'id';
    }
}
