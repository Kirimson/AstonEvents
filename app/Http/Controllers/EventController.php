<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class EventController extends Controller
{

//	gets input from form, redirects using form param, to correct route for good formatting
	public function showParser(){
		$id = Input::get(['id']);
		return Redirect::to('events/show/'.$id);
	}

//	gets input from above method, and sends off to correct view
	public function show($id){
        $event = Event::find($id);
        return view('/events/show', array('event' => $event));
    }

    public function list(){
        return view('/events/list', array('events' => Event::all()));
    }
}
