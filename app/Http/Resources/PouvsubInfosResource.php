<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PouvsubInfosResource extends JsonResource
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
            'pouvsub_id' => $this->pouvsub->id,
            'adresse' => $this->adresse,
            'date_naissance' => $this->date_naissance,
            'age' => $this->age,
            'email' => $this->email,
            'num_national' => $this->num_national,
            'statut_legal' => $this->statut_legal,
            'diplome' => $this->diplome,
            'duree_chomage' => $this->duree_chomage,
            'source_info' => $this->source_info,
            'groupe_social' => $this->groupe_social,
        ];
    }
}
