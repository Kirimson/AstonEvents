<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;

class WelcomeController extends Controller
{
    public function index(){

    	$topEvents = Event::all()->sortByDesc('likes')->take(3);
    	$upcomingEvents = Event::all()->sortBy('time')->take(3);

    	return view('main', array('topEvents' => $topEvents, 'upcomingEvents' => $upcomingEvents));

    }
}
