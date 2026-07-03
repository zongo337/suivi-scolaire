<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        'eleve_id',
        'montant',
        'date_paiement',
        'reference',
        'observation'
    ];

    protected $casts = [
        'date_paiement' =>'date',
    ];

    public function eleve(){
        return $this->belongsTo(Eleve::class);
    }
}
