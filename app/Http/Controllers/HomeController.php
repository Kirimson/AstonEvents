<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$myEvents = Event::where('organiser_id', Auth::user()->id)->orderBy('time')->get();

		return view('myAccount', array('myEvents' => $myEvents));
	}

	public function registered()
	{
		return view('justRegistered');
	}
}
