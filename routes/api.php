<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Assign api namespace
Route::group(['namespace' => 'Api'], function () {
	// Not authenticated routes
	Route::post('login', 'AuthController@login')->name('api.login');
	Route::post('register', 'AuthController@register')->name('api.register');

	Route::post('token/check', 'TokenController@checkToken')->name('api.token.check');
	Route::post('token/refresh', 'TokenController@refreshToken')->name('api.token.refresh');

	// Authenticated routes
	Route::group(['middleware' => 'auth:api'], function () {
		Route::post('logout', 'AuthController@logout')->name('api.logout');

		Route::get('profile', 'ProfileController@index')->name('api.profile.index');
		Route::patch('profile', 'ProfileController@update')->name('api.profile.update');

		Route::resource('tweet', 'TweetController')->except(['create', 'edit'])->names('api.tweet');
	});
});