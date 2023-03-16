<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
	/**
	 * Display register page.
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function show()
	{
		if(Auth::viaRemember() || Auth::check()) return redirect()->intended('/');
		return view('admin.register');
	}

	/**
	 * Handle account registration request
	 * 
	 * @param RegisterRequest $request
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function register(Request $request)
	{
		$credentials=$request->validate(User::$regrules, [
			'name.required' => 'Nama harus diisi',
			'name.regex' => 'Nama tidak boleh mengandung simbol dan angka',
			'email.required' => 'Email harus diisi',
			'email.unique' => 'Email ' . $request->email . ' sudah digunakan',
			'password.required' => 'Password harus diisi',
			'password.between' => 'Panjang password harus 8-20 karakter',
			'password.confirmed' => 'Password konfirmasi salah',
		]);
		try {
			$user = User::create($credentials);
			Auth::login($user);
			$request->session()->regenerate();
			return redirect('/home')->withSuccess("Registrasi akun berhasil, selamat datang");
		} catch (QueryException $e) {
			return back()->withInput()->withErrors($e->getMessage());
		}
	}
}
