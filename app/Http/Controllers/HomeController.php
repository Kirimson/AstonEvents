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
	public function index(Request $request)
	{

		$searchQuery = empty($request->input('search')) ? '' : $request->input('search');

		$myEvents = Event::whereRaw("organiser_id = ".Auth::user()->id." and name like '%".$searchQuery."%'")->orderBy('time')->paginate(5);

		return view('myAccount', array('myEvents' => $myEvents));
	}

	public function registered()
	{
		return view('justRegistered');
	}
}
