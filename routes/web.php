<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace' => 'App\Http\Controllers'], function()
{
	/**
	 * Home Routes
	 */
	Route::get('/', 'HomeController@index')->name('home.index');
	Route::get('/home', 'HomeController@index')->name('home.index');
	Route::group(['middleware' => ['guest']], function() {
		/**
		 * Register Routes
		 */
		Route::get('/register', 'RegisterController@show')->name('register.show');
		Route::post('/register', 'RegisterController@register')->name('register.perform');

		/**
		 * Login Routes
		 */
		Route::get('/login', 'LoginController@show')->name('login.show');
		Route::post('/login', 'LoginController@login')->name('login.perform');

		/**
		 * Reset Password Routes
		 */
		Route::get('/forget-password', 'ForgotPasswordController@showForgetPasswordForm')->name('forget-password.show');
		Route::post('/forget-password', 'ForgotPasswordController@submitForgetPasswordForm')->name('forget-password.perform');
		Route::get('/reset-password/{token}', 'ForgotPasswordController@showResetPasswordForm')->name('reset-password.show');
		Route::post('/reset-password', 'ForgotPasswordController@submitResetPasswordForm')->name('reset-password.perform');
		Route::get('/kriteria',function(){
			return redirect();
		});
	});

	Route::group(['middleware' => ['auth']], function() {
		/**
		 * Logout Routes
		 */
		Route::get('/logout', 'LogoutController@perform')->name('logout.perform');
	});
});
