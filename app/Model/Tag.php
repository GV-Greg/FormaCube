<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'tag'
    ];

    public $timestamps = false;

    public function inscrits()
    {
        return $this->belongsToMany(Inscrit::class, 'tag_inscrit');
    }
}
