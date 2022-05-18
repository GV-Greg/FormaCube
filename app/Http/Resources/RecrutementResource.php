<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecrutementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'formation_id' => $this->formation->id,
            'formation' => $this->formation->nom,
            'abreviation' => $this->formation->abreviation,
            'session' => $this->formation->session,
            'date' => $this->date->format('Y-m-d'),
            'tuteur_prenom' => $this->formation->user['firstname'],
            'tuteur_genre' => $this->formation->user['avatar'],
            'nombreCandidats' => $this->inscrits->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'selection' => $this->selection,
            'prospection' => $this->prospection,
        ];
    }
}
