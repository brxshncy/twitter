<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    //
    use Notifiable;
    protected $guard = 'users';
    protected $guarded=[];

    public function tweets(){
        return $this->hasMany(Tweet::class,'user_id');
    }
}
