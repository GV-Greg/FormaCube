<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
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
            'creation' => $this->creation,
            'update' => $this->update,
            'informations' => $this->informations,
            'date' => $this->date,
            'inscrit_id' => $this->inscrit->id,
            'user_id' => $this->user->id,
            'user' => $this->user->firstname . ' ' . $this->user->lastname,
        ];
    }
}
