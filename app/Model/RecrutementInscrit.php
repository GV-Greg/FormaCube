<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecrutementInscrit extends Model
{
    use HasFactory;

    protected $table = 'inscrit_recrutement';

    protected $fillable = [
        'date_ajout', 'selection'
    ];

    public $timestamps = false;
}
