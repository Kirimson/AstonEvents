<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
Use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    public function events(){
    	return $this->hasMany('App\Event');
    }

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
        'password', 'remember_token', 'id'
    ];

    public function getUserNameAttribute(){
    	if(Auth::check()) {
		    if ($this->name === Auth::user()->name) {
			    return ucfirst($this->name . " (You)");
		    }
	    }
	    return ucfirst($this->name);
    }
}
