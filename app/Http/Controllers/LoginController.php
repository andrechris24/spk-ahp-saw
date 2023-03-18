<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\Login\RememberMeExpiration;

class LoginController extends Controller
{
	use RememberMeExpiration;
	/**
	 * Display login page.
	 * 
	 * @return Renderable
	 */
	public function show()
	{
		if(Auth::viaRemember() || Auth::check()) return redirect()->intended('/');
		return view('admin.login');
	}
	/**
	 * Handle account login request
	 * 
	 * @param LoginRequest $request
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function login(Request $request)
	{
		$credentials = $request->validate(User::$loginrules);
		if(Auth::attempt($credentials,$request->get('remember'))){
			$user=User::where('email','=',$request->email)->first();
			Auth::login($user,$request->get('remember'));
			$request->session()->regenerate();
 			return redirect()->intended('home');
		}
		return back()->withError('Email atau Password salah')
		->withInput($request->all());
	}

	/**
	 * Handle response after user authenticated
	 * 
	 * @param Request $request
	 * @param Auth $user
	 * 
	 * @return \Illuminate\Http\Response
	 */
	protected function authenticated(Request $request, $user)
	{
		return redirect()->intended('/');
	}
}
