<?php

namespace App\Models;

use Carbon\Carbon;
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
	public static $regrules = [
		'name' => 'bail|required|regex:/^[\pL\s\-]+$/u',
		'email' => 'bail|required|email|unique:users,email',
		'password' => 'bail|required|between:8,20|confirmed',
		// 'password_confirmation' => 'same:password',
	];
	public static $regmsg = [
		'name.required' => 'Nama akun diperlukan',
		'name.regex' => 'Nama tidak boleh mengandung simbol dan angka',
		'email.required' => 'Email diperlukan',
		'email.unique' => 'Email :email sudah digunakan',
		'password.required' => 'Password diperlukan dengan panjang 8-20 karakter',
		'password.in' => 'Panjang password harus 8-20 karakter',
		'password.confirmed' => 'Password konfirmasi salah',
	];
	public static $loginrules=[
			'email' => 'required|email',
			'password' => 'required|min:8'
		];
	/**
	 * Always encrypt password when it is updated.
	 *
	 * @param $value
	 * @return string
	 */
	public function setPasswordAttribute($value)
	{
		$this->attributes['password'] = bcrypt($value);
	}
}
