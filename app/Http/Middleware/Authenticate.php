<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Session;

class Authenticate extends Middleware
{
	/**
	 * Get the path the user should be redirected to when they are not authenticated.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return string|null
	 */
	protected function redirectTo($request)
	{
		if (!$request->expectsJson()) {
			Session::flash(
				'warning',
				'Anda harus login dulu. Jika Anda sebelumnya sudah login, ' .
				'silahkan login ulang untuk melanjutkan karena sesi Anda sudah habis.'
			);
			return route('login');
		}
	}
}