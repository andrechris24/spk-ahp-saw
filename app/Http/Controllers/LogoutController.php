<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
	/**
	 * Log out account user.
	 *
	 * @return \Illuminate\Routing\Redirector
	 */
	public function perform(): \Illuminate\Routing\Redirector
	{
		User::find(auth()->user()->id)->update(['remember_token' => null]);
		Session::flush();
		Auth::logout();
		return redirect('/login');
	}
}
