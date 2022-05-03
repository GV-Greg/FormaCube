<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class FormationInscrit extends Model
{
    protected $table = 'inscrit_formation';

    protected $fillable = [
        'date_ajout', 'date_rappel', 'rappel_resultat', 'date_rdv', 'validation_rdv'
    ];

    public $timestamps = false;

    public function rappel_user()
    {
        return $this->belongsTo(User::class);
    }

    public function rdv_user()
    {
        return $this->belongsTo(User::class);
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
}
