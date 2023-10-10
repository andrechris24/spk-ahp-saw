<?php

namespace App\Models;

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
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = ['email_verified_at' => 'datetime'];
	public static array $regrules = [
		'name' => 'bail|required|min:5|regex:/^[\pL\s\-]+$/u',
		'email' => 'bail|required|email|unique:users,email',
		'password' => 'bail|required|between:8,20|confirmed'
	];
	public static array $resetpass = [
		'token' => 'required',
		'email' => 'bail|required|email|exists:users',
		'password' => 'bail|required|between:8,20|confirmed'
	];
	public static array $loginrules = [
		'email' => 'bail|required|email|exists:users',
		'password' => 'bail|required|between:8,20'
	];
	public static array $loginmsg = [
		'email.required' => 'Email harus diisi',
		'email.email' => 'Format Email salah',
		'email.exists' => 'Akun tidak ditemukan',
		'password.required' => 'Password harus diisi',
		'password.between' => 'Panjang password harus 8-20 karakter',
	];
	public static array $resetmsg = [
		'token.required' => 'Token tidak valid',
		'email.required' => 'Akun tidak ditemukan',
		'email.email' => 'Format Email salah',
		'email.exists' => 'Akun tidak ditemukan',
		'password.required' => 'Password harus diisi',
		'password.between' => 'Panjang password harus 8-20 karakter',
		'password.confirmed' => 'Password konfirmasi salah'
	];
	public static array $delakunrule = ['del_password' => 'required'];
	public static array $avatarbg=[
		0=>'bg-primary',
		1=>'bg-secondary',
		2=>'bg-success',
		3=>'bg-danger',
		4=>'bg-warning text-black',
		5=>'bg-info text-black',
		6=>'bg-light text-black',
		7=>'bg-dark',
		8=>'bg-black',
		9=>'bg-white text-black'
	];
}