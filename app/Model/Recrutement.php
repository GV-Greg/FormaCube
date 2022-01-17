<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Recrutement extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'date'
    ];

    protected $dates = ['date'];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function inscrits()
    {
        return $this->belongsToMany(Inscrit::class, 'inscrit_recrutement')->withPivot('date_ajout', 'selection');
    }
}
