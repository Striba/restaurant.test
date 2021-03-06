<?php

namespace Rest;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function reserves()
    {
        return $this->hasMany('Rest\Reserve');
    }

    public function breakfasts()
    {
        return $this->hasMany('Rest\Breakfast');
    }
}
