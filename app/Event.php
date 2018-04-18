<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use Illuminate\Support\Facades\DB;

class Event extends Model
{

	public function user()
	{
		return $this->belongsTo('App\User', 'organiser_id');
	}

	protected $fillable = [
		'organiser_id', 'name', 'description', 'category', 'time', 'picture', 'contact', 'venue', 'likes'
	];

	public function getUCNameAttribute(){
		return ucfirst($this->name);
	}

	public function getReadableTimeAttribute()
	{
		$format = 'Y-m-d H:i:s';
		$date = DateTime::createFromFormat($format, $this->time);
		return date_format($date, 'l jS F Y \a\t g:ia');
	}

	public function getUCCategoryAttribute()
	{
		return ucfirst($this->category);
	}

	public function getUCVenueAttribute()
	{
		return ucfirst($this->venue);
	}

	public function getShortDescriptionAttribute()
	{
		$desc = $this->description;

		$goodPara = false;

//		If contains a paragraph
		while ($goodPara === false && strpos($desc, "<p>") !== false) {
			$start = strpos($desc, "<p>");

//			Get the inside of the <p> so string can be escaped, even though it doesn't *need* to be, as it gets
//			sanitised on upload
			$end = strpos($desc, "</p>");
			$length = $end - $start - 3;
			$para = substr($desc, $start + 3, $length);

//			If para contains a image, go again, we don't want that. if not, return the current text
			if (strpos($para, "<") !== false) {
//			    reset to re-do the loop
				$goodPara = false;
				$desc = substr($desc, $end + 4, strlen($desc) - $end);
			} else {
//				Found a good paragraph for our preview! lets shorten it

				$MAX_LENGTH = 160;
				$shorter = strlen($para) > $MAX_LENGTH ? substr($para, 0, $MAX_LENGTH-3)."..." : $para;

				return ucfirst($shorter);
			}
		}

		return "No description available";
	}

	public function geturlNameAttribute()
	{
//		Find any symbols in the name, and remove it, symbols don't play nice with urls
		$symbolpattern = '/[^\p{L}\p{N}\s]/u';
		$noSymbols = preg_replace($symbolpattern, '', $this->name);

//		replace any whitespace with a hyphen, makes url look nicer, spaces look odd in a url, even though they accept them
		$pattern = '/(\W)+/';
		$replacement = '-';

//		append the id of the event, ensures every url is unique.
//      as names could become the same from alterations from the patterns
		return preg_replace($pattern, $replacement, $noSymbols) . '.' . $this->id;
	}
}
