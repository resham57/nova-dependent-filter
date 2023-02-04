<?php

namespace Resham\NovaDependentFilter;

use Laravel\Nova\Nova;
use Illuminate\Support\Facades\Event;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @return void
     */
	public function boot()
	{
		Event::listen(ServingNova::class, function () {
		    Nova::script('dependent-filter', __DIR__.'/../dist/js/filter.js');
		});
		$this->registerRoutes();
	}

	/**
	 * Register the package routes.
	 *
	 * @return void
	 */
	protected function registerRoutes()
	{
	    Route::group($this->routeConfiguration(), function () {
	        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
	    });
	}

	/**
	 * Get the Nova route group configuration array.
	 *
	 * @return array{domain: string|null, as: string, prefix: string, middleware: string}
	 */
	protected function routeConfiguration()
	{
	    return [
	        'domain' => config('nova.domain', null),
	        'as' => 'nova.api.',
	        'prefix' => 'nova-api',
	        'middleware' => 'nova:api',
	    ];
	}
}
