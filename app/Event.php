<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
	protected $fillable = [
		'organiser_id', 'name', 'description', 'category', 'time', 'picture', 'contact', 'venue',
	];
}
