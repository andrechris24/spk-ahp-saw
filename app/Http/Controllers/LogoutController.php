<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
	public function perform()
	{
		User::find(Auth::user()->id)->update(['remember_token' => null]);
		Session::flush();
		Auth::logout();
		return redirect('/login')->withWarning('Anda sudah logout.');
	}
}