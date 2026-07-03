<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ecole extends Model
{
    protected $fillable = [
        'nom',
        'adresse',
        'email',
        'telephone',
        'directeur',
        'active'
    ];

    public static function active(){
        return self::where('active', true)->first();

    }

}
