<?php

namespace App\Http\Controllers;

use App\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use DateTime;

class EventController extends Controller
{

//	gets input from form, redirects using form param, to correct route for good formatting
	public function showParser()
	{
		$id = Input::get(['id']);
		$event = Event::find($id);

		return Redirect::to('event/' . $event->name);
	}

//	gets input from above method, and sends off to correct view
	public function show($name)
	{
		$event = Event::where('name', '=', $name)->first();

		if($event == null){
			return view('welcome');
		}

		$event->time = $this->readableDateTime($event->time);

		return view('/events/event', array('create' => false, 'event' => $event));
	}

	public function readableDateTime($timeString)
	{
		$format = 'Y-m-d H:i:s';
		$date = DateTime::createFromFormat($format, $timeString);
		return date_format($date, 'l jS F Y \a\t g:ia');
	}

	public function search()
	{
		return view('/events/search', array('events' => Event::all()));
	}

	public function create()
	{
		return view('/events/event', array('create' => true, 'event' => null));
	}

	public function createEvent(Request $request)
	{
		$imagesql = null;

		//create image details
		if ($request->file('picture') != null) {
			$imageName = $request->name . '.' . $request->file('picture')->getClientOriginalExtension();
			$imagePath = 'img/events/';
			$request->file('picture')->move(base_path() . '/public/' . $imagePath, $imageName);
			$imagesql = $imagePath . $imageName;
		}

		$request->validate([
			'name' => 'required|unique:events|max:100',
		]);

		$datetime = Input::get('date') . ' ' . Input::get('time');

		$event = new Event();

		$event->organiser_id = Auth::user()->id;
		$event->created_at = Carbon::now();
		$event->updated_at = Carbon::now();
		$event->name = $request->name;
		$event->description = $request->description;
		$event->category = Input::get('category');
		$event->time = $datetime;
		$event->picture = $imagesql;
		$event->contact = $request->contact;
		$event->venue = $request->venue;

		$event->save();

		return redirect('event/' . $event->name);
	}

	public function like(Request $request){
		$event = Event::find($request->id);
		if($request->like == 'true') {
			$event->likes++;
		} elseif ($event->likes >0){
			$event->likes--;
		}
		$event->save();
		return $event->likes;
	}
}
