<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnonceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'titre'            => $this->titre,
            'contenu'          => $this->contenu,
            'type'             => $this->type,
            'classe'           => $this->whenLoaded('classe', fn () => $this->classe?->nom),
            'date_publication' => $this->date_publication?->format('Y-m-d H:i'),
        ];
    }
}
