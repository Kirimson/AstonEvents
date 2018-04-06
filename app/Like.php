<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
	protected $fillable = [
		'organiser_id', 'event_id'
	];
}
