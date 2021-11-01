<?php

namespace App\Http\Resources;

use App\Model\Inscrit;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FormationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => FormationResource::collection($this->collection),
        ];
    }
}
