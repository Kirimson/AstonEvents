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

Route::get('/', 'WelcomeController@index');

//Event routes
Route::get('events/', 'EventController@main');

Route::get('event/{name}', 'EventController@show');
Route::get('event/{name}/edit', 'EventController@edit');
Route::post('event/{name}/update', 'EventController@updateEvent');

Route::post('events/like', 'EventController@like');

Route::get('events/create', 'EventController@create');
Route::post('events/create/new', 'EventController@createEvent');

Route::get('events/delete/{name}', 'EventController@deleteEvent');

Route::get('/unauthorised', function(){
	return view('auth.unauthorised');
});

Auth::routes();

//User routes
Route::get('/myAccount', 'HomeController@index');
Route::get('/registered', 'HomeController@registered');

Route::get('/signup', function(){
	return view('/auth/register');
});

Route::get('/logout', 'Auth\LoginController@logout');
