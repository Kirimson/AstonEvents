<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Event extends Model
{

	public function user(){
		return $this->belongsTo('App\User', 'organiser_id');
	}

	protected $fillable = [
		'organiser_id', 'name', 'description', 'category', 'time', 'picture', 'contact', 'venue',
	];
}
