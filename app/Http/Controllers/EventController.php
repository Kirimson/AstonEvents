<?php

namespace App\Http\Controllers;

use App\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class EventController extends Controller
{

//	gets input from form, redirects using form param, to correct route for good formatting
	public function showParser()
	{
		$id = Input::get(['id']);
		return Redirect::to('events/show/' . $id);
	}

//	gets input from above method, and sends off to correct view
	public function show($id)
	{
		$event = Event::find($id);
		return view('/events/show', array('event' => $event));
	}

	public function list()
	{
		return view('/events/list', array('events' => Event::all()));
	}

	public function createEvent(Request $request)
	{

		$imageName = $request->name . '.' . $request->file('picture')->getClientOriginalExtension();

		$imagePath = 'img/events/';

		$request->file('picture')->move(base_path() . '/public/' . $imagePath, $imageName);

		$event = new Event();

    	$fields = [
   		'created_at' => Carbon::now(),
   		'updated_at' => Carbon::now(),
    	'name' => $request->name,
    	'description' => $request->description,
    	'time' => Carbon::now(),
    	'picture' => $imagePath.$imageName,
    	'organiser_id' => $request->organiser_id,
    	'contact' => $request->contact,
    	'venue' => $request->venue
    	];

		$event->create($fields);
	}
}
