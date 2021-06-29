<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InscritTag extends Model
{
    protected $table = 'tag_inscrit';

    public $timestamps = false;

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
