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

Route::get('/', function () {
    return view('welcome');
});
//Admin route
Route::get('register',[AdminController::class,'register']);
Route::post('register',[AdminController::class,'postRegister']);
Route::get('login',[AdminController::class,'login']);
Route::post('login',[AdminController::class,'postLogin']);
Route::get('logout',[AdminController::class,'logout']);