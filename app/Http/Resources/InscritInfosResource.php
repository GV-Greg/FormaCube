<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InscritInfosResource extends JsonResource
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
            'pouvsub_info_id' => $this->pouvsubinfos->id,
            'inscrit_id' => $this->inscrit->id,
            'age' => $this->age,
            'num_national' => $this->num_national,
            'statut_legal' => $this->statut_legal,
            'diplome' => $this->diplome,
            'duree_chomage' => $this->duree_chomage,
            'moyen_recrutement' => $this->moyen_recrutement,
            'groupe_social' => $this->groupe_social,
        ];
    }
}
