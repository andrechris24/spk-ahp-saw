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
Route::get('/phpinfo', function () {
	phpinfo();
})->name('php.info');
Route::get('/laravel-info', function () {
	return view('welcome');
})->name('laravel.welcome');
Route::group(['namespace' => 'App\Http\Controllers'], function () {
	/**
	 * Home Routes
	 */
	Route::get('/', 'HomeController@index')->name('home.index');
	Route::get('home', function () {
		return redirect()->route('home.index');
	})->name('home.failsafe');
	Route::middleware(['guest'])->group(function () {
		/**
		 * Register Routes
		 */
		Route::get('/register', 'AuthController@showregister')->name('register.show');
		Route::post('/register', 'AuthController@register')->name('register.perform');

		/**
		 * Login Routes
		 */
		Route::get('/login', 'AuthController@showlogin')->name('login');
		Route::post('/login', 'AuthController@login')->name('login.perform');

		/**
		 * Reset Password Routes
		 */
		Route::get('/forget-password', 'AuthController@showForgetPasswordForm')
			->name('password.request');
		Route::post('/forget-password', 'AuthController@submitForgetPasswordForm')
			->name('password.email');
		Route::get('/reset-password/{token}', 'AuthController@showResetPasswordForm')
			->name('password.reset');
		Route::post('/reset-password', 'AuthController@submitResetPasswordForm')
			->name('password.update');
	});
	Route::middleware(['auth'])->group(function () { //Authenticated users
		Route::prefix('akun')->group(function () {
			Route::get('/', 'HomeController@profile')->name('akun.show');
			Route::post('/', 'HomeController@updateProfil')->name('akun.perform');
			Route::delete('/del', 'HomeController@delAkun')->name('akun.delete');
		});
		Route::prefix('kriteria')->group(function () {
			Route::get('/', 'KriteriaController@index')->name('kriteria.index');
			Route::post('data', 'KriteriaController@show')->name('kriteria.data');
			Route::post('store', 'KriteriaController@store')->name('kriteria.store');
			Route::post('update', 'KriteriaController@update')->name('kriteria.update');
			Route::get('edit/{id}', 'KriteriaController@edit')->name('kriteria.edit');
			Route::delete('del/{id}', 'KriteriaController@hapus')
				->name('kriteria.delete');
			Route::prefix('sub')->group(function () {
				Route::get('/', 'SubKriteriaController@index')->name('subkriteria.index');
				Route::post('data', 'SubKriteriaController@show')
					->name('subkriteria.data');
				Route::get('edit/{id}', 'SubKriteriaController@edit')
					->name('subkriteria.edit');
				Route::post('store', 'SubKriteriaController@store')
					->name('subkriteria.store');
				Route::post('update', 'SubKriteriaController@update')
					->name('subkriteria.update');
				Route::delete('del/{id}', 'SubKriteriaController@destroy')
					->name('subkriteria.delete');
			});
		});
		Route::prefix('bobot')->group(function () {
			Route::get('/', 'KriteriaCompController@index')->name('bobotkriteria.index');
			Route::post('/', 'KriteriaCompController@simpan')
				->name('bobotkriteria.store');
			Route::get('hasil', 'KriteriaCompController@hasil')
				->name('bobotkriteria.result');
			Route::delete('reset', 'KriteriaCompController@destroy')
				->name('bobotkriteria.reset');
			Route::prefix('sub')->group(function () {
				Route::get('/', 'SubKriteriaCompController@index')
					->name('bobotsubkriteria.pick');
				Route::get('comp', 'SubKriteriaCompController@create')
					->name('bobotsubkriteria.index');
				Route::post('comp/{kriteria_id}', 'SubKriteriaCompController@store')
					->name('bobotsubkriteria.store');
				Route::get('hasil/{id}', 'SubKriteriaCompController@show')
					->name('bobotsubkriteria.result');
				Route::delete('reset/{id}', 'SubKriteriaCompController@destroy')
					->name('bobotsubkriteria.reset');
			});
		});
		Route::prefix('alternatif')->group(function () {
			Route::get('/', 'AlternatifController@index')->name('alternatif.index');
			Route::post('data', 'AlternatifController@show')->name('alternatif.data');
			Route::get('edit/{id}', 'AlternatifController@edit')
				->name('alternatif.edit');
			Route::post('store', 'AlternatifController@store')->name('alternatif.store');
			Route::post('update', 'AlternatifController@update')
				->name('alternatif.update');
			Route::delete('del/{id}', 'AlternatifController@hapus')
				->name('alternatif.delete');
			Route::get('hasil', 'NilaiController@show')->name('nilai.show');
			Route::prefix('nilai')->group(function () {
				Route::get('/', 'NilaiController@index')->name('nilai.index');
				Route::post('data', 'NilaiController@datatables')->name('nilai.data');
				Route::get('edit/{id}', 'NilaiController@edit')->name('nilai.edit');
				Route::post('store', 'NilaiController@store')->name('nilai.store');
				Route::post('update', 'NilaiController@update')->name('nilai.update');
				Route::delete('del/{id}', 'NilaiController@destroy')->name('nilai.delete');
			});
		});
		Route::get('/ranking', 'NilaiController@hasil')->name('hasil.ranking');
		Route::post('/logout', 'AuthController@logout')->name('logout');
	});
});