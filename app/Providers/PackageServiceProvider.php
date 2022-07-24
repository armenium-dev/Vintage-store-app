<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Components\SalesCount;

class PackageServiceProvider extends ServiceProvider{
	
	/**
	 * Register services.
	 * @return void
	 */
	public function register(){
		//
	}
	
	/**
	 * Bootstrap services.
	 * @return void
	 */
	public function boot(){
		Blade::component('sales-count', SalesCount::class);
	}
}
