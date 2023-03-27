<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
	public function show(): View|Factory|Application|RedirectResponse
	{
		if (Auth::viaRemember() || Auth::check()) return redirect()->intended();
		return view('admin.register');
	}

	public function register(Request $request)
	{
		$credentials = $request->validate(User::$regrules, [
			'name.required' => 'Nama harus diisi',
			'name.regex' => 'Nama tidak boleh mengandung simbol dan angka',
			'name.min' => 'Nama minimal 5 huruf',
			'email.required' => 'Email harus diisi',
			'email.unique' => 'Email ' . $request->email . ' sudah digunakan',
			'password.required' => 'Password harus diisi',
			'password.between' => 'Panjang password harus 8-20 karakter',
			'password.confirmed' => 'Password konfirmasi salah',
		]);
		$credentials['password'] = Hash::make($credentials['password']);
		try {
			$user = User::create($credentials);
			Auth::login($user);
			$request->session()->regenerate();
			return redirect('/home')
				->withSuccess("Registrasi akun berhasil, selamat datang");
		} catch (QueryException $e) {
			return back()->withInput()->withErrors($e->getMessage());
		}
	}
}
