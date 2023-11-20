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
		'email.exists' => 'Akun tidak ditemukan',
		'password.between' => 'Panjang password harus 8-20 karakter'
	];
	public static array $forgetrule = ['email' => 'bail|required|email|exists:users'];
	public static array $forgetmsg = [
		'email.required' => 'Email harus diisi',
		'email.email' => 'Format Email salah',
		'email.exists' => 'Akun tidak ditemukan'
	];
	public static array $resetmsg = [
		'token.required' => 'Token tidak valid',
		'email.required' => 'Akun tidak ditemukan',
		'email.exists' => 'Akun tidak ditemukan',
		'password.required' => 'Password harus diisi',
		'password.between' => 'Panjang password harus 8-20 karakter'
	];
	public static array $delakunrule = ['del_password' => 'required'];
	public static array $avatarbg = [
		0 => 'text-bg-primary',
		1 => 'text-bg-secondary',
		2 => 'text-bg-success',
		3 => 'text-bg-danger',
		4 => 'text-bg-warning',
		5 => 'text-bg-info',
		6 => 'text-bg-light',
		7 => 'text-bg-dark',
		8 => 'bg-black'
	];
}