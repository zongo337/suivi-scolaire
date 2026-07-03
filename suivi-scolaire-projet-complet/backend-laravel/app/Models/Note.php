<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'eleve_id',
        'matiere_id',
        'note',
        'trimestre',
        'annee_scolaire'
    ];

    public function eleve(){
        return $this->belongsTo(Eleve::class);
    }

    public function matiere(){
        return $this->belongsTo(Matiere::class);
    }
}
