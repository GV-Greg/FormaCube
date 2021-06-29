<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RecrutementInscrit extends Model
{
    protected $table = 'inscrit_recrutement';

    protected $fillable = [
        'date_ajout', 'selection'
    ];

    public $timestamps = false;
}
