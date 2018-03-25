<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
	function create($fields)
    {
	    DB::table('events')->insert(
		    ['created_at' => $fields['created_at'], 'updated_at' => $fields['updated_at'], 'name' => $fields['name'], 'description' => $fields['description'], 'time' => $fields['time'],
			    'picture' => $fields['picture'], 'organiser_id' => $fields['organiser_id'], 'contact' => $fields['contact'],
			    'venue' => $fields['venue']]
	    );
    }
}
