<?php

namespace App\Http\Controllers;

use App\Event;

class EventController extends Controller
{
	/**
	 * @param $id
	 * @return $this
	 */
	public function show($id){

        $event = Event::find($id);

        return view('/show', array('event' => $event));
    }

    public function list(){
        return view('/list', array('events' => Event::all()));
    }
}
