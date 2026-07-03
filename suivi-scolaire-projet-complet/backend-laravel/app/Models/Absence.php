<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    protected $fillable = [
        'eleve_id',
        'date_absence',
        'motif',
        'justifiee',
    ];

    protected $casts = [
        'date_absence' => 'date',
        'justifiee'    => 'boolean',
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }
}
