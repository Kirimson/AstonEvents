<?php

namespace App\Http\Controllers;

use App\Event;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use DateTime;

class EventController extends Controller
{

	public function main(Request $request){

		$attribute = null;
		empty($request->input('atr')) ? $attribute = 'name' : $attribute = $request->input('atr');

		$search = null;
		empty($request->input('search')) ? $search = '' : $search = $request->input('search');

		$orderBy = null;
		empty($request->input('orderBy')) ? $orderBy = 'name' : $orderBy = $request->input('orderBy');

		$orderType = null;
		empty($request->input('order')) ? $orderType = 'asc' : $orderType = $request->input('order');

		if($attribute == 'organiser_id' || $attribute == 'category'){
			$events = Event::where($attribute, $search)->orderBy($orderBy, $orderType)->get();
		} else {
			$events = Event::where($attribute, 'like', '%'.$search.'%')->orderBy($orderBy, $orderType)->get();
		}

		$users = User::all()->pluck('name', 'id');

		return view('events', array('events' => $events, 'users' => $users));
	}

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

		if ($event == null) {
			return view('welcome');
		}

		$owner = false;
		if (Auth::check()) {
			$owner = Auth::user()->id == $event->organiser_id;
		}

		return view('/events/event', array('create' => false, 'event' => $event, 'owner' => $owner));
	}

	public function edit($name)
	{
		$event = Event::where('name', '=', $name)->first();

		if (Auth::user()->id == $event->organiser_id) {
			return view('/events/event', array('create' => true, 'event' => $event));
		}
		return back();
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
		$this->validateName($request);

		$this->validateFields($request);

		$event = new Event();

		//create image details
		if ($request->file('picture') != null) {
			$path = $request->file('picture')->store('img/events', 'public');
		} else {
			$path = null;
		}

		$event = $this->setupEvent($event, $request, $path);

		$event->save();

		return redirect('event/' . $event->name);
	}

	public function updateEvent($name, Request $request)
	{
		$event = Event::where('name', '=', $name)->first();

//		If name has been changed
		if ($event->name != $request->name) {
			$this->validateName($request);
		}

		$this->validateFields($request);

//		If an image was passed
		if ($request->file('picture') != null) {
//			if there was a picture before, delete old image
			if ($event->picture != null) {
				Storage::disk('public')->delete($event->picture);
			}
//			Create a new file for the image, and store in event
			$path = $request->file('picture')->store('img/events', 'public');
		} else {
//			There is no image, check if image is already null, if not, delete image, else, just set path to null
			if ($event->picture != null) {
				Storage::disk('public')->delete($event->picture);
			}
			$path = null;
		}

		$event = $this->setupEvent($event, $request, $path);

		$event->save();

		return redirect('event/' . $event->name);

	}

	public function setupEvent($event, Request $request, $path)
	{

		$datetime = Input::get('date') . ' ' . Input::get('time');

		$event->organiser_id = Auth::user()->id;
		$event->name = $request->name;
		$event->description = $request->description;
		$event->category = Input::get('category');
		$event->time = $datetime;
		$event->picture = $path;
		$event->contact = $request->contact;
		$event->venue = $request->venue;

		return $event;
	}

	public function like(Request $request)
	{
//		find event by its id, passed from the POST, then increment/decrement likes depending on POST request
		$event = Event::find($request->id);
		if ($request->like == 'true') {
			$event->likes++;
		} elseif ($event->likes > 0) {
			$event->likes--;
		}
		$event->save();
		return $event->likes;
	}

	public function validateName(Request $request)
	{
		$request->validate([
			'name' => 'required|unique:events|max:100'
		]);
	}

	private function validateFields($request)
	{
//		Check if all required fields are filled in, organiser not needed, done server side, so user cant set organiser
//		to someone who isn't them
		$request->validate([
			'description' => 'required',
			'category' => 'required',
			'contact' => 'required',
			'date' => 'required',
			'time' => 'required',
			'venue' => 'required',
			'picture' => 'mimes:jpeg,bmp,png,svg'
		]);

//		Clean the description
		$request->description = clean($request->description);
	}
}
