<?php

namespace App;

use App\Model\Log;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Formation;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'fonction', 'role', 'avatar'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function formations()
    {
        return $this->hasMany(Formation::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }
}
