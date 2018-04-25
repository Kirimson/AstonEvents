<?php

namespace App\Http\Controllers;

use App\Event;
use Carbon\Carbon;

class WelcomeController extends Controller
{
	/**
	 * Find top 3 liked events and 3 upcoming events and display them on main page
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
    public function index(){
//    	Get the top 3 highest rated events
    	$topEvents = Event::all()->sortByDesc('likes')->take(3);
//    	Get the 3 most recently created events
	    $newestEvents = Event::where('time', '>=', Carbon::now())->orderBy('created_at', 'desc')->paginate(3);

    	return view('main', array('topEvents' => $topEvents, 'newestEvents' => $newestEvents));
    }
}
