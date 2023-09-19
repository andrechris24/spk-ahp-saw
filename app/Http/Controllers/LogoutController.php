<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class LogoutController extends Controller
{
	public function perform()
	{
		try {
			User::findOrFail(Auth::user()->id)->update(['remember_token' => null]);
			Session::flush();
			Auth::logout();
			return redirect('/login')->withSuccess('Anda sudah logout.');
		} catch (ModelNotFoundException $e) {
			return back()->withErrors($e->getMessage());
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withError('Gagal logout: ' . $e->getMessage());
		}
	}
}