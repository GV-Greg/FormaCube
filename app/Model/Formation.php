<?php

namespace App\Model;

use App\User;
use App\Model\Pouvsub;
use App\Model\Recrutement;
use App\Model\PouvsubInfos;

use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    protected $fillable = [
        'nom', 'session', 'date_debut', 'date_fin', 'min_stagiaires', 'max_stagiaires', 'nbre_heures', 'salle', 'prix', 'commentaire_formation', 'statut', 'abreviation', "duree_rgpd",
    ];

    protected $dates = ['date_debut', 'date_fin'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pouvsub()
    {
        return $this->belongsTo(Pouvsub::class);
    }

    public function recrutements()
    {
        return $this->hasMany(Recrutement::class);
    }

    public function pouvsubinfos()
    {
        return $this->hasOne(PouvsubInfos::class);
    }

    public function inscrits()
    {
        return $this->belongsToMany(Inscrit::class, 'inscrit_formation');
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
}
