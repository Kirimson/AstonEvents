<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


//Events page
Route::get('events', function(){
	return view('events');
});

Route::get('events/list', 'EventController@list');
Route::get('events/show', 'EventController@showParser');
Route::get('events/show/{id}', 'EventController@show');