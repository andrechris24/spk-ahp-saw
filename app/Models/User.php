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
		'name' => ['bail', 'required', 'min:5', 'regex:/^[\pL\s\-]+$/u'],
		'email' => ['bail', 'required', 'email', 'unique:users'],
		'password' => ['bail', 'required', 'between:8,20', 'confirmed'],
		'password_confirmation' => 'required'
	], $regmsg = [
		'name.required' => 'Nama harus diisi',
		'name.regex' => 'Nama tidak boleh mengandung simbol dan angka',
		'email.email' => 'Format Email salah'
	], $resetpass = [
		'token' => 'required',
		'email' => ['bail', 'required', 'email', 'exists:users'],
		'password' => ['bail', 'required', 'between:8,20', 'confirmed']
	], $loginrules = [
		'email' => ['bail', 'required', 'email', 'exists:users'],
		'password' => ['bail', 'required', 'between:8,20']
	], $forgetrule = ['email' => ['bail', 'required', 'email', 'exists:users']],
	$forgetmsg = ['email.email' => 'Format Email salah'],
	$resetmsg = ['token.required' => 'Token tidak valid'],
	$delakunrule = ['del_password' => ['bail', 'required', 'current_password']],
	$updrules = [
		'name' => ['bail', 'required', 'min:5', 'regex:/^[\pL\s\-]+$/u'],
		'email' => ['bail', 'required', 'email'],
		'current_password' => ['bail', 'required', 'current_password'],
		'password' => ['nullable', 'bail', 'confirmed', 'between:8,20'],
		'password_confirmation' => 'required_with:password'
	], $message = [
		'name.required' => 'Nama harus diisi',
		'name.regex' => 'Nama tidak boleh mengandung simbol dan angka',
		'current_password.required' => 'Password lama harus diisi'
	], $avatarbg = [
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