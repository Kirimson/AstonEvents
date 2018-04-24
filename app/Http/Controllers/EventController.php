<?php

namespace App\Http\Controllers;

use App\Event;
use App\Like;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{

	public function findSpecifics(Request $request)
	{
		//Set default search terms if the input is empty
		$attribute = null;
		empty($request->input('atr')) ? $attribute = 'name' : $attribute = $request->input('atr');
		$search = null;
		empty($request->input('search')) ? $search = '' : $search = $request->input('search');
		$orderBy = null;
		empty($request->input('orderBy')) ? $orderBy = 'name' : $orderBy = $request->input('orderBy');
		$sortType = null;
		empty($request->input('order')) ? $sortType = '0' : $sortType = $request->input('order');

//		if sort type is set, convert it to its binary counterpart. done here so GET displays ascending/descending
		if ($sortType != '0') {
			$sortType = $sortType == 'ascending' ? 0 : 1;
		}

//		Set sorting to make human sense. Ascending words sorts A-Z, Ascending likes go 99-0
		if ($orderBy == 'likes' || $orderBy == 'created_at') {
			$sortType = ($sortType + 1) % 2;
		}

		$sort = array('asc', 'desc');

		if ($attribute == 'organiser_id' || $attribute == 'category') {
			$events = Event::where($attribute, $search)->orderBy($orderBy, $sort[$sortType]);
		} else {
			$events = Event::where($attribute, 'like', '%' . $search . '%')->orderBy($orderBy, $sort[$sortType]);
		}
		return $events;
	}

	public function main(Request $request)
	{
		$events = $this->findSpecifics($request)->paginate(5);

		$users = User::all()->pluck('name', 'id');

		return view('events.eventSearch', array('events' => $events->appends(Input::except('page')), 'users' => $users));
	}

//	gets input from above method, and sends off to correct view
	public function show($urlname)
	{
		$id = explode('.', $urlname)[1];

		$event = Event::find($id);

		if ($event == null) {
			return redirect('/');
		}

		$owner = false;
		$liked = false;
//		If logged in
		if (Auth::check()) {
//			check if you are owner
			$owner = Auth::user()->id == $event->organiser_id;

//			Check if user liked this event
			$liked = (Like::whereRaw('`organiser_id` = ' . Auth::user()->id . ' and `event_id` = ' . $event->id)->first() != null);
		}

		return view('/events/event', array('create' => false, 'event' => $event, 'owner' => $owner, 'liked' => $liked));
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

		$event = $this->saveEvent($event, $request, $path);

		return redirect('event/' . $event->urlname);
	}

	public function updateEvent($name, Request $request)
	{
		$id = explode('.', $name)[1];
		$event = Event::find($id);

		if($event->user->id != Auth::user()->id)
			{
				return "Fuck you";
			}

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
//			There is no image, keep it the same
			$path = $event->picture;
		}

		$event = $this->saveEvent($event, $request, $path);

		return redirect('event/' . $event->urlname);

	}

	public function saveEvent($event, Request $request, $path)
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

		$event->save();

		return $event;
	}

	public function like(Request $request)
	{
//		find event by its id, passed from the POST, then increment/decrement likes depending on POST request
		$event = Event::find($request->id);
		if ($request->like == 'true') {
			$event->likes++;

//			If user logged in, add this like to the table
			if (Auth::check()) {
				$like = new Like();
				$like->organiser_id = Auth::user()->id;
				$like->event_id = $request->id;

				$like->save();
			}
		} elseif ($event->likes > 0) {
			$event->likes--;

//			If user logged in, remove this like to the table
			if (Auth::check()) {
				Like::whereRaw('`organiser_id` = ' . Auth::user()->id . ' and `event_id` = ' . $event->id)->delete();
			}
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

	public function deleteEvent($name){
		$id = explode('.', $name)[1];
		$event = Event::find($id);

		if($event->user->id == Auth::user()->id){

			$likes = Like::where('event_id', $event->id);

			$likes->delete();

			$event->delete();
			return redirect('/events');
		}

	}

}
