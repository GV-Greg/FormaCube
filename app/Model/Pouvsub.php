<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Model\Formation;

class Pouvsub extends Model
{
    protected $table = 'pouvsubs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nom', 'logo', 'adresse', 'date_naissance', 'age', 'email', 'num_national', 'statut_legal', 'diplome', 'duree_chomage', 'moyen_recrutement', 'groupe_social'
    ];

    public function formations()
    {
        return $this->hasMany(Formation::class);
    }

    public function pouvsubinfos()
    {
        return $this->hasMany(PouvsubInfos::class);
    }
}
