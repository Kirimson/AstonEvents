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
//		Provides pages with knowledge of pages ont he site and their page links. shared for the navbar to create links
		$pages = [
			new Page('Home', '/'),
			$event = new Category('Events', '/events/', array(
				new Page('Search', '/events/'),
				new Page('Most Liked', '/events?orderBy=likes&order=0&preset=true'),
				new Page('Upcoming', '/events?orderBy=time&order=0&preset=true'),
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
