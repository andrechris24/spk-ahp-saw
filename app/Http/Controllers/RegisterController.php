<?php

namespace App\Http\Controllers;

use App\Models\User;
// use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
	/**
	 * Display register page.
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function show()
	{
		return view('admin.register');
	}

	/**
	 * Handle account registration request
	 * 
	 * @param RegisterRequest $request
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function register(RegisterRequest $request)
	{
		$request->validate(User::$regrules,[
			'name.required'=>'Nama harus diisi',
			'name.regex'=>'Nama tidak boleh mengandung simbol dan angka',
			'email.required'=>'Email harus diisi',
			'email.unique'=>'Email '.$request->email.' sudah digunakan',
			'password.required'=>'Password harus diisi',
			'password.in'=>'Panjang password harus 8-20 karakter',
			'password.confirmed'=>'Password konfirmasi salah',
		]);
		$user = User::create($request->validated());
		auth()->login($user);
		return redirect('/login')->with('success', "Registrasi akun berhasil");
	}
}
