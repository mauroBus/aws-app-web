<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		/*
		 * CONFIGURATION FOR MONGODB
		 */
        $_mongodb = $this->app->config->get('app.mongodb');

		\Purekid\Mongodm\MongoDB::setConfigBlock('default', array(
	    	'connection' => array(
	       	'hostnames' => $_mongodb['hostnames'],
	        'database'  => $_mongodb['database'],
            'options'  => array('username' => $_mongodb['username'], 'password' => $_mongodb['password'], 'authSource' => $_mongodb['database']),
	    )
		));
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'App\Services\Registrar'
		);
	}

}
