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
*/
Route::get('/phpinfo', function () {
	phpinfo();
})->name('php.info');
Route::view('/laravel-info', 'welcome')->name('laravel.welcome');
Route::group(['namespace' => 'App\Http\Controllers'], function () {
	Route::get('/', 'HomeController@index')->name('home.index');
	Route::redirect('/home', '/')->name('home.failsafe');
	Route::middleware(['guest'])->controller('AuthController')->group(function () {
		Route::prefix('register')->group(function () {
			Route::get('/', 'showregister')->name('register.show');
			Route::post('/', 'register')->name('register.perform');
		});
		Route::prefix('login')->group(function () {
			Route::get('/', 'showlogin')->name('login');
			Route::post('/', 'login')->middleware(['throttle:3,2'])
				->name('login.perform');
		});
		Route::prefix('forget-password')->group(function () {
			Route::get('/', 'showForgetPasswordForm')->name('password.request');
			Route::post('/', 'submitForgetPasswordForm')->name('password.email');
		});
		Route::prefix('reset-password')->group(function () {
			Route::get('{token}', 'showResetPasswordForm')->name('password.reset');
			Route::post('/', 'submitResetPasswordForm')->name('password.update');
		});
	});
	Route::middleware(['auth'])->group(function () { //Authenticated users
		Route::controller('HomeController')->prefix('akun')->group(function () {
			Route::get('/', 'profile')->name('akun.show');
			Route::post('/', 'updateProfil')->middleware(['throttle:3,2'])
				->name('akun.perform');
			Route::delete('/del', 'delAkun')->middleware(['throttle:3,2'])
				->name('akun.delete');
		});
		Route::prefix('kriteria')->group(function () {
			Route::controller('KriteriaController')->group(function () {
				Route::get('/', 'index')->name('kriteria.index')->block();
				Route::get('count', 'getCount')->name('kriteria.count')->block();
				Route::post('data', 'show')->name('kriteria.data')->block();
				Route::post('store', 'store')->name('kriteria.store')->block();
				Route::get('edit/{kr}', 'edit')->name('kriteria.edit')->block();
				Route::delete('del/{kr}', 'hapus')->name('kriteria.delete')->block();
			});
			Route::controller('SubKriteriaController')->prefix('sub')
				->group(function () {
					Route::get('/', 'index')->name('subkriteria.index')->block();
					Route::get('count', 'getCount')->name('subkriteria.count')->block();
					Route::post('data', 'show')->name('subkriteria.data')->block();
					Route::get('edit/{skr}', 'edit')->name('subkriteria.edit')->block();
					Route::post('store', 'store')->name('subkriteria.store')->block();
					Route::delete('del/{skr}', 'destroy')->name('subkriteria.delete')
						->block();
				});
		});
		Route::prefix('bobot')->group(function () {
			Route::controller('KriteriaCompController')->group(function () {
				Route::get('/', 'index')->name('bobotkriteria.index');
				Route::post('/', 'simpan')->name('bobotkriteria.store');
				Route::get('hasil', 'hasil')->name('bobotkriteria.result');
				Route::delete('reset', 'destroy')->name('bobotkriteria.reset');
			});
			Route::controller('SubKriteriaCompController')->prefix('sub')
				->group(function () {
					Route::post('data', 'datatables')->name('bobotsubkriteria.data')
						->block();
					Route::get('/', 'index')->name('bobotsubkriteria.pick')->block();
					Route::prefix('{kriteria_id}')->group(function () {
						Route::get('/', 'create')->name('bobotsubkriteria.index');
						Route::post('/', 'store')->name('bobotsubkriteria.store');
						Route::get('hasil', 'show')->name('bobotsubkriteria.result');
						Route::delete('del', 'destroy')->name('bobotsubkriteria.reset');
					});
				});
		});
		Route::controller('AlternatifController')->prefix('alternatif')
			->group(function () {
				Route::get('/', 'index')->name('alternatif.index')->block();
				Route::get('count', 'getCount')->name('alternatif.count')->block();
				Route::post('data', 'show')->name('alternatif.data')->block();
				Route::get('edit/{alt}', 'edit')->name('alternatif.edit')->block();
				Route::post('store', 'store')->name('alternatif.store')->block();
				Route::delete('del/{alt}', 'hapus')->name('alternatif.delete')->block();
			});
		Route::controller('NilaiController')->group(function () {
			Route::prefix('nilai')->group(function () {
				Route::get('/', 'index')->name('nilai.index')->block();
				Route::get('count', 'getCount')->name('nilai.count')->block();
				Route::post('data', 'datatables')->name('nilai.data')->block();
				Route::get('edit/{id}', 'edit')->name('nilai.edit')->block();
				Route::post('store', 'store')->name('nilai.store')->block();
				Route::delete('del/{id}', 'destroy')->name('nilai.delete')->block();
				Route::get('hasil', 'show')->name('nilai.show')->block();
			});
			Route::get('ranking', 'hasil')->name('hasil.ranking')->block();
		});
		Route::post('logout', 'AuthController@logout')->name('logout');
	});
});