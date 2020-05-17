<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'first_name', 'last_name', 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     *  Attribute stores email & password
     *
     * @var string
     */
    protected $hash = '';

    /**
     *  Hash attribute setter
     */
    public function SetForHash($email, $password){
        $this->hash = $email.'$'.$password;
    }

    /**
     * Hash attribute getter
     */
    public function GetForHash(){
        return $this->hash;
    }
}
