<?php

namespace App\Providers;

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
			$event = new Page('Events', '/events')
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
