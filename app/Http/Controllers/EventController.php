<?php

namespace App\Http\Controllers;

use App\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\Middleware\ShareErrorsFromSession;

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

	public function search()
	{
		return view('/events/search', array('events' => Event::all()));
	}

	public function createEvent(Request $request)
	{
		//create image details
		$imageName = $request->name . '.' . $request->file('picture')->getClientOriginalExtension();
		$imagePath = 'img/events/';
		$request->file('picture')->move(base_path() . '/public/' . $imagePath, $imageName);

		$request->validate([
			'name' => 'required|unique:events|max:191',
			'description' => 'required|max:191',
			'category' => 'required|max:191',
			'contact' => 'required|max:191',
			'venue' => 'required|max:191',
		]);

		$event = new Event();

		$event->organiser_id = $request->organiser_id;
		$event->created_at = Carbon::now();
		$event->updated_at = Carbon::now();
		$event->name = $request->name;
		$event->description = $request->description;
		$event->category = Input::get('category');
		$event->time = Carbon::now();
		$event->picture = $imagePath . $imageName;
		$event->contact = $request->contact;
		$event->venue = $request->venue;

		$event->save();

		return back();
	}
}
