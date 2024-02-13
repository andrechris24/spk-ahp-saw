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
			Route::get('/', 'showregister')->name('register');
			Route::post('/', 'register')->middleware(['throttle:3,5'])
				->name('register.perform');
		});
		Route::prefix('login')->group(function () {
			Route::get('/', 'showlogin')->name('login');
			Route::post('/', 'login')->middleware(['throttle:3,3'])
				->name('login.perform');
		});
		Route::name('password.')->group(function () {
			Route::prefix('forget-password')->group(function () {
				Route::get('/', 'showForgetPasswordForm')->name('request');
				Route::post('/', 'submitForgetPasswordForm')->name('email');
			});
			Route::prefix('reset-password')->group(function () {
				Route::get('{token}', 'showResetPasswordForm')->name('reset');
				Route::post('/', 'submitResetPasswordForm')->name('update');
			});
		});
	});
	Route::middleware(['auth'])->group(function () { //Authenticated users
		Route::controller('HomeController')->prefix('akun')->name('akun.')
			->group(function () {
				Route::get('/', 'profile')->name('show');
				Route::middleware(['throttle:3,5'])->group(function () {
					Route::post('/', 'updateProfil')->name('perform');
					Route::delete('/del', 'delAkun')->name('delete');
				});
			});
		Route::prefix('kriteria')->group(function () {
			Route::controller('KriteriaController')->name('kriteria.')->group(function () {
				Route::get('/', 'index')->name('index')->block();
				Route::get('count', 'getCount')->name('count')->block();
				Route::get('data', 'show')->name('data')->block();
				Route::post('store', 'store')->name('store')->block();
				Route::get('edit/{kr}', 'edit')->name('edit')->block();
				Route::delete('del/{kr}', 'hapus')->name('delete')->block();
			});
			Route::controller('SubKriteriaController')->name('subkriteria.')
				->prefix('sub')->group(function () {
					Route::get('/', 'index')->name('index')->block();
					Route::get('count', 'getCount')->name('count')->block();
					Route::get('data', 'show')->name('data')->block();
					Route::get('edit/{skr}', 'edit')->name('edit')->block();
					Route::post('store', 'store')->name('store')->block();
					Route::delete('del/{skr}', 'destroy')->name('delete')->block();
				});
		});
		Route::prefix('bobot')->group(function () {
			Route::controller('KriteriaCompController')->name('bobotkriteria.')
			->group(function () {
				Route::get('/', 'index')->name('index');
				Route::post('/', 'simpan')->name('store');
				Route::get('hasil', 'hasil')->name('result');
				Route::delete('reset', 'destroy')->name('reset');
			});
			Route::controller('SubKriteriaCompController')->name('bobotsubkriteria.')
				->prefix('sub')->group(function () {
					Route::get('data', 'datatables')->name('data')->block();
					Route::get('/', 'index')->name('pick')->block();
					Route::prefix('{kriteria_id}')->group(function () {
						Route::get('/', 'create')->name('index');
						Route::post('/', 'store')->name('store');
						Route::get('hasil', 'show')->name('result');
						Route::delete('del', 'destroy')->name('reset');
					});
				});
		});
		Route::controller('AlternatifController')->prefix('alternatif')
			->name('alternatif.')->group(function () {
				Route::get('/', 'index')->name('index')->block();
				Route::get('count', 'getCount')->name('count')->block();
				Route::get('data', 'show')->name('data')->block();
				Route::get('edit/{alt}', 'edit')->name('edit')->block();
				Route::post('store', 'store')->name('store')->block();
				Route::delete('del/{alt}', 'hapus')->name('delete')->block();
			});
		Route::controller('NilaiController')->group(function () {
			Route::prefix('nilai')->name('nilai.')->group(function () {
				Route::get('/', 'index')->name('index')->block();
				Route::get('count', 'getCount')->name('count')->block();
				Route::get('data', 'datatables')->name('data')->block();
				Route::get('edit/{id}', 'edit')->name('edit')->block();
				Route::post('store', 'store')->name('store')->block();
				Route::delete('del/{id}', 'destroy')->name('delete')->block();
				Route::get('hasil', 'show')->name('show')->block();
			});
			Route::get('ranking', 'hasil')->name('hasil.ranking')->block();
		});
		Route::post('logout', 'AuthController@logout')->name('logout');
	});
});