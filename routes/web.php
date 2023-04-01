<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'App\Http\Controllers'], function () {
	/**
	 * Home Routes
	 */
	Route::get('/', 'HomeController@index')->name('home.index');
	Route::get('/home', 'HomeController@index')->name('home.index');
	Route::group(['middleware' => ['guest']], function () {
		/**
		 * Register Routes
		 */
		Route::get('/register', 'RegisterController@show')->name('register.show');
		Route::post('/register', 'RegisterController@register')->name('register.perform');

		/**
		 * Login Routes
		 */
		Route::get('/login', 'LoginController@show')->name('login');
		Route::post('/login', 'LoginController@login')->name('login.perform');

		/**
		 * Reset Password Routes
		 */
		Route::get('/forget-password', 'ForgotPasswordController@showForgetPasswordForm')
			->name('password.request');
		Route::post(
			'/forget-password', 'ForgotPasswordController@submitForgetPasswordForm'
		)->name('password.email');
		Route::get(
			'/reset-password/{token}', 'ForgotPasswordController@showResetPasswordForm'
		)->name('password.reset');
		Route::post('/reset-password', 'ForgotPasswordController@submitResetPasswordForm')
			->name('password.update');
	});

	Route::group(['middleware' => ['auth']], function () { //Authenticated users
		Route::prefix('akun')->group(function () {
			Route::get('/', 'HomeController@profile')->name('akun.show');
			Route::post('/', 'HomeController@updateProfil')->name('akun.perform');
			Route::post('/del', 'HomeController@delAkun')->name('akun.delete');
		});
		Route::prefix('kriteria')->group(function () {
			Route::get('/', 'KriteriaController@index')->name('kriteria.show');
			Route::post('add', 'KriteriaController@tambah')->name('kriteria.create');
			Route::post('update/{id}', 'KriteriaController@update')->name('kriteria.update');
			Route::get('del/{id}', 'KriteriaController@hapus')->name('kriteria.delete');
			Route::prefix('sub')->group(function () {
				Route::get('/', 'SubKriteriaController@index')->name('subkriteria.show');
				Route::post('add', 'SubKriteriaController@store')->name('subkriteria.create');
				Route::post('update/{id}', 'SubKriteriaController@update')
					->name('subkriteria.update');
				Route::get('del/{id}', 'SubKriteriaController@destroy')
					->name('subkriteria.delete');
			});
		});
		Route::prefix('bobot')->group(function () {
			Route::get('/', 'KriteriaCompController@index')->name('bobotkriteria.index');
			Route::post('/', 'KriteriaCompController@simpan')->name('bobotkriteria.store');
			Route::get('hasil', 'KriteriaCompController@hasil')->name('bobotkriteria.result');
			Route::get('reset', 'KriteriaCompController@destroy')
				->name('bobotkriteria.reset');
			Route::prefix('sub')->group(function () {
				Route::get('/', 'SubKriteriaCompController@index')
					->name('bobotsubkriteria.pick');
				Route::get('comp', 'SubKriteriaCompController@create')
					->name('bobotsubkriteria.index');
				Route::post('comp', 'SubKriteriaCompController@store')
					->name('bobotsubkriteria.store');
				Route::get('hasil/{id}', 'SubKriteriaCompController@show')
					->name('bobotsubkriteria.result');
				Route::get('reset/{id}', 'SubKriteriaCompController@destroy')
					->name('bobotsubkriteria.reset');
			});
		});
		Route::prefix('alternatif')->group(function () {
			Route::get('/', 'AlternatifController@index')->name('alternatif.index');
			Route::post('add', 'AlternatifController@tambah')->name('alternatif.add');
			Route::post('update/{id}', 'AlternatifController@update')
				->name('alternatif.update');
			Route::get('del/{id}', 'AlternatifController@hapus')->name('alternatif.delete');
			Route::get('hasil', 'NilaiController@show')->name('nilai.show');
			Route::prefix('nilai')->group(function () {
				Route::get('/', 'NilaiController@index')->name('nilai.index');
				Route::post('add', 'NilaiController@store')->name('nilai.add');
				Route::post('update/{id}', 'NilaiController@update')->name('nilai.update');
				Route::get('del/{id}', 'NilaiController@destroy')->name('nilai.delete');
			});
		});
		Route::get('ranking', 'HasilController@index')->name('ranking.show');
		Route::get('/logout', 'LogoutController@perform')->name('logout.perform');
	});

	Route::get('/test','HomeController@test');
});
