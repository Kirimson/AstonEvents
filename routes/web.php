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


//Events page
Route::get('events/', 'EventController@main');
Route::get('events/search', 'EventController@search');

Route::post('event/', 'EventController@showParser');
Route::get('event/{name}', 'EventController@show');
Route::get('event/{name}/edit', 'EventController@edit')->middleware('auth');
Route::post('event/{name}/update', 'EventController@updateEvent')->middleware('auth');

Route::post('events/like', 'EventController@like');

Route::get('events/create', 'EventController@create')->middleware('auth');
Route::post('events/create/new', 'EventController@createEvent')->middleware('auth');

Auth::routes();

Route::get('/myAccount', 'HomeController@index');

Route::get('/signup', function(){
	return view('/auth/register');
});

Route::get('/logout', 'Auth\LoginController@logout');
