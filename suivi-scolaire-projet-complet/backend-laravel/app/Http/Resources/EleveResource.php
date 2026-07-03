<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EleveResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'nom'             => $this->nom,
            'prenom'          => $this->prenom,
            'date_naissance'  => $this->date_naissance?->format('Y-m-d'),
            'sexe'            => $this->sexe,
            'photo_url'       => $this->photo ? asset('storage/'.$this->photo) : null,
            'classe'          => $this->whenLoaded('classe', fn () => [
                'id'  => $this->classe->id,
                'nom' => $this->classe->nom,
            ]),
        ];
    }
}
