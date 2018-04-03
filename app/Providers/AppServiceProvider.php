<?php

namespace App\Providers;

use App\Category;
use App\Page;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$pages = [
			new Page('Home', '/'),
			$event = new Category('Events', '/events/', array(
				new Page('Search', '/events/'),
				new Page('Most Liked', '/events/'),
				new Page('Upcoming', '/events/'),
				new Page('Create Event', '/events/create')
			))
		];

		view()->share('pages', $pages);
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
}
