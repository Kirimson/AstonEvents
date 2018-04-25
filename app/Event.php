<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use Illuminate\Support\Facades\DB;

class Event extends Model
{

	/**
	 * Setup relation between Event and user, allowing the event to have access to its user
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo('App\User', 'organiser_id');
	}

	protected $fillable = [
		'organiser_id', 'name', 'description', 'category', 'time', 'picture', 'contact', 'venue', 'likes'
	];

	/**
	 * Gets the name of the event in UCfirst format
	 * @return string
	 */
	public function getUCNameAttribute(){
		return ucfirst($this->name);
	}

	/**
	 * Creates a more human readable time format from a timestamp
	 * @return false|string date in format of: Day Date Month Year at Hour:Minute AM/PM
	 */
	public function getReadableTimeAttribute()
	{
		$format = 'Y-m-d H:i:s';
		$date = DateTime::createFromFormat($format, $this->time);
		return date_format($date, 'l jS F Y \a\t g:ia');
	}

	/**
	 * Gets the category of the event in UCfirst format
	 * @return string
	 */
	public function getUCCategoryAttribute()
	{
		return ucfirst($this->category);
	}

	/**
	 * Gets the venue of the event in UCfirst format
	 * @return string
	 */
	public function getUCVenueAttribute()
	{
		return ucfirst($this->venue);
	}

	/**
	 * Creates a shorter description of the event, searches for the first set of readable text in a <p> element
	 * and limits it to 160 characters if needed
	 * @return string short description of the event, limited to 160 characters
	 */
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

//			If paragraph contains a image, trim up to the current end position and try again
			if (strpos($para, "<") !== false) {
//			    reset to re-do the loop
				$goodPara = false;
				$desc = substr($desc, $end + 4, strlen($desc) - $end);
			} else {
//				Found paragraph for the preview, shorten it if needed
				$MAX_LENGTH = 160;
				$shorter = strlen($para) > $MAX_LENGTH ? substr($para, 0, $MAX_LENGTH-3)."..." : $para;
				return ucfirst($shorter);
			}
		}
//      No valid paragraph found
		return "No description available";
	}

	/**
	 * Create a name for the event to be displayed in urls
	 * removes all symbols, hyphonates spaces, and appends '.$id' to the string
	 * @return string url-friendly name for event
	 */
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

	/**
	 * returns all events that are related to this event
	 * @return Event collection of events related to this event
	 */
	public function getRelatedEventsAttribute(){

		$relatedIDs = RelatedEvent::where('event_id', $this->id)->pluck('related_event_id');
		$events = null;

		if(!empty($relatedIDs)){
			$events = Event::find($relatedIDs);
		}

		return $events;
	}
}
