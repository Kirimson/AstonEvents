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

		$request->file('picture')->move(
			base_path() . '/public/images/events/', $imageName
		);

		$event = new Event();

    	$fields = [
   		'created_at' => Carbon::now(),
   		'updated_at' => Carbon::now(),
    	'name' => Input::get('name'),
    	'description' => Input::get('description'),
    	'time' => Carbon::now(),
    	'picture' => Input::get('picture'),
    	'organiser_id' => Input::get('organiser_id'),
    	'contact' => Input::get('contact'),
    	'venue' => Input::get('venue')
    	];

		$event->create($fields);
	}
}
