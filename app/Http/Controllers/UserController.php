<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
	public function listUsers()
	{
		$users = User::all()->pluck('name', 'id');

    	return $users;
	}
}
