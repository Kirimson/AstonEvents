<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RelatedEvent extends Model
{
	protected $fillable = [
		'event_id', 'related_event_id'
	];
}
