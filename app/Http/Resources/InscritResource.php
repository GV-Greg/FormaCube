<?php

namespace App\Http\Resources;

use App\Model\Recrutement;
use Illuminate\Http\Resources\Json\JsonResource;

class InscritResource extends JsonResource
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
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'genre' => $this->genre,
            'date_naissance' => $this->date_naissance,
            'rue' => $this->rue,
            'numero' => $this->numero,
            'boite' => $this->boite,
            'ville_id' => $this->ville->id,
            'cp' => $this->ville->code_postal,
            'ville' => $this->ville->ville,
            'email' => $this->email,
            'tel' => $this->tel,
            'gsm' => $this->gsm,
            'commentaire_inscrit' => $this->commentaire_inscrit,
            'prospect' => $this->prospect,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'age' => $this->age,
            'num_national' => $this->num_national,
            'statut_legal' => $this->statut_legal,
            'diplome' => $this->diplome,
            'duree_chomage' => $this->duree_chomage,
            'source_info' => $this->source_info,
            'groupe_social' => $this->groupe_social,
            'newsletter' => $this->newsletter,
        ];
    }
}
