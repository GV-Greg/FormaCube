<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    protected $fillable = [
        'nom'
    ];

    public function formations()
    {
        return $this->hasMany(Formation::class);
    }
}
