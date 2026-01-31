<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Recrutement extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'date'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function inscrits()
    {
        return $this->belongsToMany(Inscrit::class, 'inscrit_recrutement')->withPivot('date_ajout', 'selection');
    }
}
