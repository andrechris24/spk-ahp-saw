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
			return redirect()->route('home.index');
		return view('admin.login');
	}
	public function login(Request $request)
	{
		try {
			$credentials = $request->validate(User::$loginrules, User::$loginmsg);
			if (Auth::attempt($credentials, $request->get('remember'))) {
				$user = User::firstWhere('email', $request->email);
				Auth::login($user, $request->get('remember'));
				Session::put('avatar-bg', User::$avatarbg[random_int(0, 8)]);
				Session::regenerate();
				return redirect()->route('home.index');
			}
			return back()->withInput()->withErrors(['password' => __('auth.password')]);
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
		} catch (ModelNotFoundException) {
			return back()->withError("Gagal logout: Akun tidak ditemukan");
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withError('Gagal logout: ' . $e->errorInfo[2]);
		}
	}
	public function showregister()
	{
		if (Auth::viaRemember() || Auth::check())
			return redirect()->route('home.index');
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
			User::create($credentials);
			return redirect('/login')->withSuccess("Akun sudah dibuat. ".
					"Silahkan login menggunakan akun yang sudah didaftarkan.");
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withInput()
				->withError("Gagal membuat akun: " . $e->errorInfo[2]);
		}
	}
	public function showForgetPasswordForm()
	{
		if (Auth::viaRemember() || Auth::check())
			return redirect()->route('home.index');
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
				->withError("Gagal mengirim link reset password: " . $sql->errorInfo[2]);
		}
		return back()
			->withError('Gagal mengirim link reset password: Kesalahan tidak diketahui');
	}
	public function showResetPasswordForm($token)
	{
		if (Auth::viaRemember() || Auth::check())
			return redirect()->route('home.index');
		try {
			$enctoken = DB::table('password_resets')->where('email', $_GET['email'])
				->first();
			if ($enctoken === null) {
				return redirect('/forget-password')->withError(
					'Token tidak valid atau Link sudah kedaluarsa. ' .
					'Silahkan minta reset password lagi.'
				);
			}
			if (!Hash::check($token, $enctoken->token))
				return redirect('/login')->withError(__('passwords.token'));
			return view(
				'admin.reset-password',
				['token' => $token, 'email' => $_GET['email']]
			);
		} catch (QueryException $e) {
			Log::error($e);
			return redirect('/forget-password')
				->withError('Kesalahan: ' . $e->errorInfo[2]);
		// }catch(ModelNotFoundException){
		// 	return redirect('/forget-password')->withError(__(''));
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
				return redirect('/login')->withSuccess('Reset password berhasil.
					Silahkan login menggunakan password yang Anda buat.');
			} else if ($status === Password::INVALID_TOKEN)
				return back()->withError('Reset password gagal: '.__('passwords.token'));
			else if ($status === Password::INVALID_USER)
				return back()->withError('Reset password gagal: '.__('passwords.user'));
			return back()->withError('Reset password gagal: Kesalahan tidak diketahui');
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withError('Reset password gagal: ' . $e->errorInfo[2]);
		}
	}
}