<?php

namespace App\Http\Controllers;

use App\Event;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{

	public function main(Request $request)
	{

//		Set default search terms if the input is empty
		$attribute = null;
		empty($request->input('atr')) ? $attribute = 'name' : $attribute = $request->input('atr');
		$search = null;
		empty($request->input('search')) ? $search = '' : $search = $request->input('search');
		$orderBy = null;
		empty($request->input('orderBy')) ? $orderBy = 'name' : $orderBy = $request->input('orderBy');
		$sortType = null;
		empty($request->input('order')) ? $sortType = '0' : $sortType = $request->input('order');

//		Set sorting to make human sense. Ascending words sorts A-Z, Ascending likes go 99-0
		if ($orderBy == 'likes' || $orderBy == 'created_at') {
			$sortType = ($sortType + 1) % 2;
		}

		$sort = array('asc', 'desc');

		if ($attribute == 'organiser_id' || $attribute == 'category') {
			$events = Event::where($attribute, $search)->orderBy($orderBy, $sort[$sortType])->get();
		} else {
			$events = Event::where($attribute, 'like', '%' . $search . '%')->orderBy($orderBy, $sort[$sortType])->get();
		}

		$users = User::all()->pluck('name', 'id');

		return view('eventSearch', array('events' => $events, 'users' => $users));
	}

//	gets input from form, redirects using form param, to correct route for good formatting
	public function showParser()
	{
		$id = Input::get(['id']);
		$event = Event::find($id);

		$htmlName = rawurlencode($event->name);

		return Redirect::to('event/' . $htmlName);
	}

//	gets input from above method, and sends off to correct view
	public function show($htmlName)
	{

		$id = explode('.', $htmlName)[1];

		$event = Event::find($id);

		if ($event == null) {
			return redirect('/');
		}

		$owner = false;
		if (Auth::check()) {
			$owner = Auth::user()->id == $event->organiser_id;
		}

		return view('/events/event', array('create' => false, 'event' => $event, 'owner' => $owner));
	}

	public function edit($name)
	{

		$id = explode('.', $name)[1];

		$event = Event::find($id);

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
		$event = new Event();

		$this->validateName($request);

		$this->validateFields($request);

		//create image details
		if ($request->file('picture') != null) {
			$path = $request->file('picture')->store('img/events', 'public');
		} else {
			$path = null;
		}

		$event = $this->setupEvent($event, $request, $path);

		$event->save();

		$this->createurlName($event);

		return redirect('event/' . $event->urlname);
	}

	public function updateEvent($name, Request $request)
	{

		$event = Event::where('urlname', '=', $name)->first();

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

		$this->createurlName($event);

		return redirect('event/' . $event->urlname);

	}

	public function createurlName($event)
	{
		$symbolpattern = '/[^\p{L}\p{N}\s]/u';
		$noSymbols = preg_replace($symbolpattern, '', $event->name);

		$pattern = '/(\W)+/';
		$replacement = '-';
		$urlName = preg_replace($pattern, $replacement, $noSymbols) . '.' . $event->id;

		$event->urlname = $urlName;

		$event->save();
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
//		Require name to be unique, and not contain '/' as it messes with routes
		$request->validate([
			'name' => 'required|unique:events|max:100'
		]);


	}

	private function validateFields($request)
	{
//		Check if all required fields are filled in, organiser not needed, done server side, so user cant set organiser
//		to someone who isn't them, or mess up the client code to remove checking. Never trust the user.
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
