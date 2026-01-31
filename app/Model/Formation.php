<?php

namespace App\Model;

use App\User;
use App\Model\Pouvsub;
use App\Model\Recrutement;
use App\Model\PouvsubInfos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'session', 'date_debut', 'date_fin', 'min_stagiaires', 'max_stagiaires', 'nbre_heures', 'salle', 'prix', 'commentaire_formation', 'statut', 'abreviation', "duree_rgpd",
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recrutements()
    {
        return $this->hasMany(Recrutement::class);
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
