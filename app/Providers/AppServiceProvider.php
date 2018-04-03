<?php

namespace App\Providers;

use App\Category;
use App\Page;
use Illuminate\Support\ServiceProvider;

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
			$home = new Page('Home', '/'),
			$event = new Category('Events', '/events/', array(
				$main = new Page('Events', '/events/'),
				$create = new Page('Create', '/events/create'),
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
