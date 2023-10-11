<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Login\RememberMeExpiration;
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
	use RememberMeExpiration;
	public function showlogin()
	{
		if (Auth::viaRemember() || Auth::check())
			return redirect('/');
		return view('admin.login');
	}
	public function login(Request $request)
	{
		try {
			$credentials = $request->validate(User::$loginrules, [
				'email.required' => 'Email harus diisi',
				'email.email' => 'Format Email salah',
				'email.exists' => 'Akun dengan Email ' . $request->email . ' tidak ditemukan',
			]);
			if (Auth::attempt($credentials, $request->get('remember'))) {
				$user = User::firstWhere('email', $request->email);
				Auth::login($user, $request->get('remember'));
				Session::put('avatar-bg', User::$avatarbg[random_int(0, 8)]);
				Session::regenerate();
				return redirect('/');
			}
			return back()->withInput()->withErrors(['password' => 'Password salah']);
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withInput()->withError('Gagal login: ' . $e->errorInfo[2]);
		}
	}
	public function logout()
	{
		try {
			User::findOrFail(Auth::id())->update(['remember_token' => null]);
			Auth::logout();
			Session::invalidate();
			Session::regenerateToken();
			return redirect('/login')->withSuccess('Anda sudah logout.');
		} catch (ModelNotFoundException $e) {
			return back()->withErrors($e->getMessage());
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withError('Gagal logout: ' . $e->errorInfo[2]);
		}
	}
	public function showregister()
	{
		if (Auth::viaRemember() || Auth::check())
			return redirect('/');
		return view('admin.register');
	}

	public function register(Request $request)
	{
		try {
			$credentials = $request->validate(User::$regrules, [
				'name.required' => 'Nama harus diisi',
				'name.regex' => 'Nama tidak boleh mengandung simbol dan angka',
				'name.min' => 'Nama minimal 5 huruf',
				'email.required' => 'Email harus diisi',
				'email.unique' => 'Email ' . $request->email . ' sudah digunakan',
				'password.required' => 'Password harus diisi',
				'password.between' => 'Panjang password harus 8-20 karakter',
				'password.confirmed' => 'Password konfirmasi salah'
			]);
			$credentials['password'] = Hash::make($credentials['password']);
			$user = User::create($credentials);
			return redirect('/login')
				->withSuccess("Registrasi akun berhasil, selamat datang");
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withInput()
				->withError("Registrasi akun gagal: " . $e->errorInfo[2]);
		}
	}
	public function showForgetPasswordForm()
	{
		if (Auth::viaRemember() || Auth::check())
			return redirect('/');
		return view('admin.forget-password');
	}
	public function submitForgetPasswordForm(Request $request)
	{
		try {
			$request->validate(['email' => 'bail|required|email|exists:users']);
			$status = Password::sendResetLink($request->only('email'));
			if ($status === Password::RESET_LINK_SENT)
				return back()->withSuccess('Link reset password sudah dikirim.');
			else if ($status === Password::RESET_THROTTLED) {
				return back()->withInput()
					->withError('Tunggu sebentar sebelum meminta reset password lagi.');
			}
		} catch (TransportException $err) {
			Log::error($err);
			DB::table('password_resets')->where('email', $request->email)->delete();
			return back()->withInput()
				->withError("Gagal mengirim link reset password: " . $err->getMessage());
		} catch (QueryException $sql) {
			Log::error($sql);
			return back()->withInput()
				->withError("Gagal mengirim link reset password: " .
					$sql->errorInfo[2]);
		}
		return back()
			->withError('Gagal mengirim link reset password: Kesalahan tidak diketahui');
	}
	public function showResetPasswordForm($token)
	{
		if (Auth::viaRemember() || Auth::check())
			return redirect('/');
		try {
			$enctoken = DB::table('password_resets')->where('email', $_GET['email'])
				->first();
			if ($enctoken === null) {
				return redirect('/forget-password')->withError(
					'Link reset password sudah kedaluarsa. ' .
					'Silahkan minta reset password lagi.'
				);
			}
			if (!Hash::check($token, $enctoken->token))
				return redirect('/login')->withError('Token reset password tidak valid');
			return view(
				'admin.reset-password',
				['token' => $token, 'email' => $_GET['email']]
			);
		} catch (QueryException $e) {
			return redirect('/forget-password')
				->withError('Kesalahan: ' . $e->errorInfo[2]);
		}
	}
	public function submitResetPasswordForm(Request $request)
	{
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
			return redirect('/login')->withSuccess('Reset password berhasil.
				Silahkan login menggunakan password yang Anda buat.');
		} else if ($status === Password::INVALID_TOKEN)
			return back()->withError('Reset password gagal: Token tidak valid');
		else if ($status === Password::INVALID_USER)
			return back()->withError('Reset password gagal: Akun tidak ditemukan');
		return back()->withError('Reset password gagal: Kesalahan tidak diketahui');
	}
}