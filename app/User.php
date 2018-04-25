<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
Use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

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

	/**
	 * Get the users name. If the user is the same as the current user, append (You) at the end
	 * @return string of users name
	 */
    public function getUserNameAttribute(){
    	if(Auth::check()) {
		    if ($this->name === Auth::user()->name) {
			    return ucfirst($this->name . " (You)");
		    }
	    }
	    return ucfirst($this->name);
    }
}
