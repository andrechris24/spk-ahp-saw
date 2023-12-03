<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Mailer\Exception\TransportException;

class AuthController extends Controller
{
	public function showlogin()
	{
		if (Auth::viaRemember() || Auth::check())
			return to_route('home.index');
		return view('admin.login');
	}
	public function login(Request $request)
	{
		try {
			$credentials = $request->validate(User::$loginrules);
			if (Auth::attempt($credentials, $request->get('remember'))) {
				$user = User::firstWhere('email', $request->email);
				Auth::login($user, $request->get('remember'));
				Session::put('avatar-bg', User::$avatarbg[random_int(0, 8)]);
				Session::regenerate();
				return to_route('home.index');
			}
			return back()->withInput()->withErrors(['password' => __('auth.password')]);
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withInput()->withError('Gagal login: ' .
				"Kesalahan SQLState #" . $e->errorInfo[0]);
		}
	}
	public function logout()
	{
		try {
			User::find(Auth::id())->update(['remember_token' => null]);
			Auth::logout();
			Session::invalidate();
			Session::regenerateToken();
			return to_route('login')->withSuccess('Anda sudah logout.');
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withError('Gagal logout: ' .
				"Kesalahan SQLState #" . $e->errorInfo[0]);
		}
	}
	public function showregister()
	{
		if (Auth::viaRemember() || Auth::check())
			return to_route('home.index');
		return view('admin.register');
	}
	public function register(Request $request)
	{
		try {
			$credentials = $request->validate(User::$regrules, User::$regmsg);
			$credentials['password'] = Hash::make($credentials['password']);
			User::create($credentials);
			return to_route('login')->withSuccess("Akun sudah dibuat. " .
				"Silahkan login menggunakan akun yang sudah didaftarkan.");
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withInput()->withError("Gagal membuat akun: " .
				"Kesalahan SQLState #" . $e->errorInfo[0]);
		}
	}
	public function showForgetPasswordForm()
	{
		if (Auth::viaRemember() || Auth::check())
			return to_route('home.index');
		return view('admin.forget-password');
	}
	public function submitForgetPasswordForm(Request $request)
	{
		try {
			$request->validate(User::$forgetrule, User::$forgetmsg);
			$status = Password::sendResetLink($request->only('email'));
			if ($status === Password::RESET_LINK_SENT) {
				return back()->withSuccess(__('passwords.sent'));
			} else if ($status === Password::RESET_THROTTLED) {
				return back()->withInput()->withError(__('passwords.throttled'));
			}
		} catch (TransportException $err) {
			Log::error($err);
			DB::table('password_resets')->where('email', $request->email)->delete();
			return back()->withInput()
				->withError("Gagal mengirim link reset password: " . $err->getMessage());
		} catch (QueryException $sql) {
			Log::error($sql);
			return back()->withInput()
				->withError("Gagal membuat token reset password: " .
					"Kesalahan SQLState #" . $sql->errorInfo[0]);
		}
		return back()
			->withError('Gagal mengirim link reset password: Kesalahan tidak diketahui');
	}
	public function showResetPasswordForm($token)
	{
		if (Auth::viaRemember() || Auth::check())
			return to_route('home.index');
		try {
			$enctoken = DB::table('password_resets')->where('email', $_GET['email'])
				->firstOrFail();
			if (!Hash::check($token, $enctoken->token))
				return to_route('login')->withError(__('passwords.token'));
			return view(
				'admin.reset-password',
				['token' => $token, 'email' => $_GET['email']]
			);
		} catch (QueryException $e) {
			Log::error($e);
			return to_route('password.request')
				->withError("Terjadi Kesalahan SQLState #" . $e->errorInfo[0]);
		} catch (ModelNotFoundException) {
			return to_route('password.request')->withError(
				'Token tidak valid atau Link sudah kedaluarsa. ' .
				'Silahkan minta reset password lagi.'
			);
		}
	}
	public function submitResetPasswordForm(Request $request)
	{
		try {
			$request->validate(User::$resetpass, User::$resetmsg);
			$status = Password::reset(
				$request->only('email', 'password', 'password_confirmation', 'token'),
				function (User $user, string $password) {
					$user->forceFill(['password' => Hash::make($password)]);
					$user->save();
					event(new PasswordReset($user));
				}
			);
			if ($status === Password::PASSWORD_RESET) {
				return to_route('login')->withSuccess('Reset password berhasil. ' .
					'Silahkan login menggunakan password yang Anda buat.');
			} else if ($status === Password::INVALID_TOKEN)
				return back()->withError('Reset password gagal: ' . __('passwords.token'));
			else if ($status === Password::INVALID_USER)
				return back()->withError('Reset password gagal: ' . __('passwords.user'));
			return back()->withError('Reset password gagal: Kesalahan tidak diketahui');
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withError('Reset password gagal: ' .
				"Kesalahan SQLState #" . $e->errorInfo[0]);
		}
	}
}