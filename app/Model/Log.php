<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'creation', 'update', 'informations', 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inscrit()
    {
        return $this->belongsTo(Inscrit::class);
    }
}
