<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\KriteriaCompController;
use App\Http\Controllers\SubKriteriaController;
use App\Http\Controllers\SubKriteriaCompController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\LoginController;

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
		Route::get('/forget-password', 'ForgotPasswordController@showForgetPasswordForm')->name('forget-password.show');
		Route::post('/forget-password', 'ForgotPasswordController@submitForgetPasswordForm')->name('forget-password.perform');
		Route::get('/reset-password/{token}', 'ForgotPasswordController@showResetPasswordForm')->name('reset-password.show');
		Route::post('/reset-password', 'ForgotPasswordController@submitResetPasswordForm')->name('reset-password.perform');
	});

	Route::group(['middleware' => ['auth']], function () { //Authenticated users
		Route::get('/akun', 'HomeController@profile')->name('akun.show');
		Route::post('/akun', 'HomeController@updateProfil')->name('akun.perform');
		Route::prefix('kriteria')->group(function () {
			Route::get('/', [KriteriaController::class, 'index']);
			Route::post('add', [KriteriaController::class, 'tambah']);
			Route::post('update/{$id}', [KriteriaController::class, 'update']);
			Route::get('del/{$id}', [KriteriaController::class, 'hapus']);
			Route::prefix('sub')->group(function () {
				Route::get('/', [SubKriteriaController::class, 'index']);
				Route::post('add', [SubKriteriaController::class, 'store']);
				Route::post('update/{$id}', [SubKriteriaController::class, 'update']);
				Route::get('del/{$id}', [SubKriteriaController::class, 'destroy']);
			});
		});
		Route::prefix('bobot')->group(function () {
			Route::get('/', [KriteriaCompController::class, 'index']);
			Route::post('/', [KriteriaCompController::class, 'simpan']);
			Route::get('hasil', [KriteriaCompController::class, 'hasil']);
			Route::get('reset', [KriteriaCompController::class, 'destroy']);
			Route::prefix('sub')->group(function(){
				Route::get('/', [SubKriteriaCompController::class, 'index']);
				Route::get('/comp',[SubKriteriaCompController::class,'create']);
				Route::post('/{}', [SubKriteriaCompController::class, 'store']);
				Route::get('hasil/{}', [SubKriteriaCompController::class, 'show']);
				Route::get('reset/{}', [SubKriteriaCompController::class, 'destroy']);
			});
		});
		Route::prefix('alternatif')->group(function () {
			Route::get('/', [AlternatifController::class, 'index']);
			Route::post('add', [AlternatifController::class, 'tambah']);
			Route::post('update/{$id}', [AlternatifController::class, 'update']);
			Route::get('del/{$id}', [AlternatifController::class, 'hapus']);
		});
		Route::get('/logout', 'LogoutController@perform')->name('logout.perform');
		// Route::get('/login', 'LoginController@show')->name('login');
	});
});
