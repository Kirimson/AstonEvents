<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show($id){
        $event = Event::find($id);
        return view('/show', array('event' => $event));
    }

    public function list(){
        return view('/list', array('events' => Event::all()));
    }
}
