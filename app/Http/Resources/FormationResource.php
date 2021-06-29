<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FormationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->salle_id != null) {
            $salle = $this->salle->nom;
        } else {
            $salle = null;
        }
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'session' => $this->session,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'min_stagiaires' => $this->min_stagiaires,
            'max_stagiaires' => $this->max_stagiaires,
            'nbre_heures' => $this->nbre_heures,
            'salle_id' => $this->salle_id,
            'salle' => $salle,
            'prix' => $this->prix,
            'commentaire_formation' => $this->commentaire_formation,
            'user_id' => $this->user['id'],
            'tuteur_prenom' => $this->user['firstname'],
            'tuteur_genre' => $this->user['avatar'],
            'pouvsub_id' => $this->pouvsub->id,
            'pouvsub' => $this->pouvsub->nom,
            'statut' => $this->statut,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'abreviation' => $this->abreviation,
            'duree_rgpd' => $this->duree_rgpd,
        ];
    }
}
