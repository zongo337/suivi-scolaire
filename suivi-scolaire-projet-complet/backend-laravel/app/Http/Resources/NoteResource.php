<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'note'           => (float) $this->note,
            'note_sur'       => $this->matiere?->note_sur,
            'trimestre'      => $this->trimestre,
            'annee_scolaire' => $this->annee_scolaire,
            'matiere'        => $this->whenLoaded('matiere', fn () => [
                'id'          => $this->matiere->id,
                'nom'         => $this->matiere->nom,
                'coefficient' => $this->matiere->coefficient,
            ]),
        ];
    }
}
