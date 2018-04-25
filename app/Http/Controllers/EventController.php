<?php

namespace App\Http\Controllers;

use App\Event;
use App\Like;
use App\RelatedEvent;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EventController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth', ['except' => ['show', 'like', 'main']]);
	}

	/**
	 * Performs a complex search of events. Any attribute can be searched, with a set order and order type
	 * @param Request $request parameters for the search
	 * @return Object derived from Eloquent Model of Event
	 */
	public function findSpecifics(Request $request)
	{
		//Set default search terms if the input is empty, else set to the value in the parameter
		empty($request->input('atr')) ? $attribute = 'name' : $attribute = $request->input('atr');
		empty($request->input('search')) ? $search = '' : $search = $request->input('search');
		empty($request->input('orderBy')) ? $orderBy = 'name' : $orderBy = $request->input('orderBy');
		empty($request->input('order')) ? $sortType = '0' : $sortType = $request->input('order');

//		if sort type is set, convert it to its binary counterpart. done here so GET displays ascending/descending
		if ($sortType != '0') {
			$sortType = $sortType == 'ascending' ? 0 : 1;
		}

//		Set sorting to make human sense. Ascending words sorts A-Z, Ascending likes go 99-0
//		Uses the sort type binary to swap asc/desc around if the order is by likes, used to access the sort array later
		if ($orderBy == 'likes') {
			$sortType = ($sortType + 1) % 2;
		}

//		Make small array of the parameters needed for the Eloquent query
		$sort = array('asc', 'desc');

//		$upcomingEvents = Event::where('time', '>=', )->orderBy('time', 'asc')->paginate(3);

//		If searching for an organiser or category, make sure it equals that search, rather than like that query
//		Also check that events are in the future, don't display past events
		if ($attribute == 'organiser_id' || $attribute == 'category') {
			$events = Event::whereRaw($attribute." = ".$search." and `time` >= NOW()")->orderBy($orderBy, $sort[$sortType]);
		} else {
			$events = Event::whereRaw($attribute." like '%".$search."%' and `time` >= NOW()")->orderBy($orderBy, $sort[$sortType]);
		}
		return $events;
	}

	/**
	 * Return main event page, the search page
	 * @param Request $request parameters for the search
	 * @return \Illuminate\View\View Search view
	 */
	public function main(Request $request)
	{
//		Find default event search when first accessing the page. After the $request variable is populated, custom
//		searches will be returned here
		$events = $this->findSpecifics($request)->paginate(5);

//		List users by a key/value pair to be inserted into a select box for user to search by organiser easily
		$users = User::all()->pluck('name', 'id');

		return view('events.eventSearch', array('events' => $events->appends(Input::except('page')), 'users' => $users));
	}

	/**
	 * Displays a specific event, uses the urlname of the event, explodes it to get its id
	 * then find and send event to view
	 * @param $urlname String name of event
	 * @return \Illuminate\View\View Event page
	 */
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
//			check if you are owner, to allow for edit privileges
			$owner = Auth::user()->id == $event->organiser_id;

//			Check if user liked this event (only done for logged in users, guests use localstorage, done inside view)
			$liked = (Like::whereRaw('`organiser_id` = ' . Auth::user()->id . ' and `event_id` = ' . $event->id)->first() != null);
		}

		return view('/events/event', array('create' => false, 'event' => $event, 'owner' => $owner, 'liked' => $liked));
	}

	/**
	 * Displays the create event page, gets a key/value pairs of all events for related events
	 * @return \Illuminate\View\View Creation page
	 */
	public function create()
	{
		$events = Event::all('id', 'name');
		return view('/events/event', array('create' => true, 'event' => null, 'eventList' => $events));
	}

	/**
	 * Load the event page in editing mode, uses the same page as creating an event/viewing an event
	 * Checks if current user has permission to edit this event
	 * @param $urlname String name of event to edit
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View Edit page
	 */
	public function edit($urlname)
	{
		$id = explode('.', $urlname)[1];

		$event = Event::find($id);

		$events = Event::all('id', 'name');

		if (Auth::user()->id == $event->organiser_id) {
			return view('/events/event', array('create' => true, 'event' => $event, 'eventList' => $events));
		}
		return view('/unauthorised');
	}

	/**
	 * Sets up fields to save event to database, such as event image and checks for event relations
	 * @param Request $request event information
	 * @return \Illuminate\Http\RedirectResponse redirect to created event
	 */
	public function createEvent(Request $request)
	{
//		Validate user fields and create a new, empty event
		$this->validateName($request);
		$this->validateFields($request);

		$event = new Event();

		//create image details, if an image was uploaded
		if ($request->file('picture') != null) {
			$path = $request->file('picture')->store('img/events', 'public');
		} else {
			$path = null;
		}

//		Generic save event method, used for creating and updating events
		$event = $this->saveEvent($event, $request, $path);

//		if related events are not empty, add related events to this event
		if (!empty($request->input('related_events'))) {
			$this->createRelated($event->id, $request->input('related_events'));
		}

//		Redirect user to their event page, with a success banner
		return redirect('event/' . $event->urlname)->with('status', 'Event Created Successfully!');
	}

	/**
	 * Checks edited fields to update event on database
	 * Removes old image if a new one is provided and validate name again, only if it was changed
	 * @param $urlname String name of event in its url format
	 * @param Request $request updated event details
	 * @return \Illuminate\Http\RedirectResponse event page redirect
	 */
	public function updateEvent($urlname, Request $request)
	{
		$id = explode('.', $urlname)[1];
		$event = Event::find($id);

//		If name has been changed
		if ($event->name != $request->name) {
			$this->validateName($request);
		}

		$this->validateFields($request);

//		If an image was passed to update event image
		if ($request->file('picture') != null) {
//			if there was a picture before, delete old image
			if ($event->picture != null) {
				Storage::disk('public')->delete($event->picture);
			}
//			Create a new file for the image, and store in img/events, save its path to be added to event
			$path = $request->file('picture')->store('img/events', 'public');
		} else {
//			There is no image, keep it the same
			$path = $event->picture;
		}

//		Save event and return it into $event
		$event = $this->saveEvent($event, $request, $path);

//		Re-save related events
		$related = RelatedEvent::where('event_id', $event->id);
		$related->delete();
		if (!empty($request->input('related_events'))) {
			$this->createRelated($event->id, $request->input('related_events'));
		}

//		Return user to updated event with success banner
		return redirect('event/' . $event->urlname)->with('status', 'Event Updated Successfully!');
	}

	/**
	 * Save an event to the database
	 * @param Event $event event to be saved
	 * @param Request $request event data
	 * @param $path path for image file
	 * @return Event saved event with all information
	 */
	public function saveEvent(Event $event, Request $request, $path)
	{

//		Concatenate the date and time together to create a valid timestamp
		$datetime = Input::get('date') . ' ' . Input::get('time');

//		Add all event fields. organiser id is set server side, stops users making events for another organiser
		$event->organiser_id = Auth::user()->id;
		$event->name = $request->name;
		$event->description = $request->description;
		$event->category = Input::get('category');
		$event->time = $datetime;
		$event->picture = $path;
		$event->contact = $request->contact;
		$event->venue = $request->venue;

//		Save event to database
		$event->save();

		return $event;
	}

	/**
	 * Add/remove a like to the event/ likes table if an authenticated user is liking an event
	 * @param Request $request event id to like
	 * @return integer of amount of likes the event has now
	 */
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

	/**
	 * Check that the name of an event is unique
	 * Only ran if event is being created, or edited event has changed its name, otherwise validate would fail for
	 * edited event with it's name staying the same
	 * @param Request $request request object containing the event name
	 */
	public function validateName(Request $request)
	{
//		Require name to be unique, and not contain '/' as it messes with routes
		$request->validate([
			'name' => 'required|unique:events|max:100'
		]);
	}

	/**
	 * Validate all fields apart from name for the event
	 * @param $request request object containing the event information
	 */
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

//		Clean the description of any dirty html. stops any scripts being added in ckeditor
//		ckeditor is pretty safe, as source editing has been disables, however this doesn't stop malicious users editing
//		the source inside ckeditor directly using their browsers inspector to inject malicious code. clean will remove
//		any unauthorised elements from the html
		$request->description = clean($request->description);
	}

	/**
	 * Deletes an event and all foreign key's it is included in the database
	 * Checks if user has the correct privileges to perform this action
	 * @param $name
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function deleteEvent($name)
	{
		$id = explode('.', $name)[1];
		$event = Event::find($id);

		if ($event->user->id == Auth::user()->id) {

//			Delete likes for the event, so there are no foreign key constraint fails
			$likes = Like::where('event_id', $id);
			$likes->delete();

			//delete all its related events the user made and events that were related to this event
			$related = RelatedEvent::whereRaw('event_id = ' . $id . ' or related_event_id = ' . $id);
			$related->delete();

//			Delete the event
			$event->delete();
			return redirect('/myAccount')->with('status', 'Event Deleted Successfully!');
		}
		return redirect('unauthorised');
	}

	/**
	 * Creates related events for a new/modifies event and events it is related to
	 * @param $id Integer id of the event
	 * @param $relations String[] id of related events, from user input
	 */
	private function createRelated($id, $relations)
	{
//		Loop through each event id set in the input field and link it as a related event to this event
		foreach ($relations as $relatedEvent) {
			$relation = new RelatedEvent();
			$relation->event_id = $id;
			$relation->related_event_id = $relatedEvent;
			$relation->save();
		}
	}
}
