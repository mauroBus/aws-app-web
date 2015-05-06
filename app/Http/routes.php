<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

/*
 * CRUD Imagenes
 */
#Route to delete an image
Route::post('images/search', [
    'as' => 'images.search',
    'uses' => 'ImageController@report',
]); 

#Routes for all the servicies about IMAGES => http://laravel.com/docs/5.0/controllers
Route::resource('images','ImageController' );

#Route to delete an image
Route::get('images/{id}/delete', [
    'as' => 'images.delete',
    'uses' => 'ImageController@destroy',
]); 