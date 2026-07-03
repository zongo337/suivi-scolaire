<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    protected $fillable = [
        'nom',
        'coefficient',
        'note_sur'
    ];

    public function classes(){
    return $this->belongsToMany(Classe::class, 'classe_matiere');
    }

    #Méthode de notes. Dans chaque matière ont peut avoir plusieurs notes
    public function notes(){
        return $this->hasMany(Note::class);
    } 
}
