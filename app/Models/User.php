<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];
	public static $rules=[
		'name'=>'required',
		'email'=>'required|email',
		'password'=>'required|confirmed|min:8|max:20',
	];
	public static $loginrules=[
		'email'=>'required|email',
		'password'=>'required|min:8|max:20',
	];
	public static $profilrules=[
		'name'=>'required',
		'email'=>'required|email',
		'password'=>'required|confirmed|min:8|max:20',
	];
	public static $forgetpass=['email'=>'required|email'];
	public static $resetpass=[
		'password'=>'required|confirmed|min:8|max:20',
		'token'=>'required',
		'email'=>'required|email'
	];
}
