<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use Illuminate\Support\Facades\DB;

class Event extends Model
{

	public function user(){
		return $this->belongsTo('App\User', 'organiser_id');
	}

	protected $fillable = [
		'organiser_id', 'name', 'description', 'category', 'time', 'picture', 'contact', 'venue', 'likes', 'urlname'
	];

	public function getReadableTimeAttribute(){
		$format = 'Y-m-d H:i:s';
		$date = DateTime::createFromFormat($format, $this->time);
		return date_format($date, 'l jS F Y \a\t g:ia');
	}

	public function getUCCategoryAttribute(){
		return ucfirst($this->category);
	}

	public function getUCVenueAttribute(){
		return ucfirst($this->venue);
	}
}
