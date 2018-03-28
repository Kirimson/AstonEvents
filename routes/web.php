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

//events
Route::get('events/list', 'EventController@list');
Route::post('events/show', 'EventController@showParser');
Route::get('events/show/{id}', 'EventController@show');
Route::get('events/create', function(){
	return view('events/create');
})->middleware('auth');
Route::post('events/create/new', 'EventController@createEvent');
Auth::routes();

Route::get('/myAccount', 'HomeController@index');

Route::get('/signup', function(){
	return view('/auth/register');
});

Route::get('/logout', 'Auth\LoginController@logout');
