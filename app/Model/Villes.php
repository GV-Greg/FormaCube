<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Villes extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'code_postal', 'ville',
    ];

    public $timestamps = false;

    public function inscrits()
    {
        return $this->hasMany(Inscrit::class);
    }
}
