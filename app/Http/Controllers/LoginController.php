<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Login\RememberMeExpiration;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
	use RememberMeExpiration;
	public function show(): View|Factory|Application|RedirectResponse
	{
		if (Auth::viaRemember() || Auth::check())
			return redirect()->intended();
		return view('admin.login');
	}
	public function login(Request $request): RedirectResponse
	{
		try {
			$credentials = $request->validate(User::$loginrules, [
				'email.required' => 'Email harus diisi',
				'email.email' => 'Format Email salah',
				'email.exists' => 'Akun dengan Email ' . $request->email . ' tidak ditemukan',
			]);
			if (Auth::attempt($credentials, $request->get('remember'))) {
				$user = User::where('email', '=', $request->email)->first();
				Auth::login($user, $request->get('remember'));
				$request->session()->regenerate();
				return redirect()->intended('home');
			}
			return back()->withErrors(['password' => 'Password salah'])->withInput();
		} catch (QueryException $e) {
			return back()->withInput()->withError('Gagal login: ' . $e->getMessage());
		}
	}
}