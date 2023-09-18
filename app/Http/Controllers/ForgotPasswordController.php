<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Symfony\Component\Mailer\Exception\TransportException;

class ForgotPasswordController extends Controller
{
	public function showForgetPasswordForm(): View|Factory|Application|RedirectResponse
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
			DB::table('password_resets')->where('email', $request->email)->delete();
			return back()->withInput()
				->withError("Gagal mengirim link reset password: " . $err->getMessage());
		} catch (QueryException $sql) {
			return back()->withInput()
				->withError("Gagal mengirim link reset password: " . $sql->getMessage());
		}
		return back()
			->withError('Gagal mengirim link reset password: Kesalahan tidak diketahui');
	}
	public function showResetPasswordForm($token): View|Factory|Application|RedirectResponse
	{
		if (Auth::viaRemember() || Auth::check())
			return redirect()->intended();
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
				->withError('Kesalahan: ' . $e->getMessage());
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
			return redirect('/login')->withSuccess(
				'Reset password berhasil. Silahkan login menggunakan password yang Anda buat.'
			);
		} else if ($status === Password::INVALID_TOKEN)
			return back()->withError('Reset password gagal: Token tidak valid');
		else if ($status === Password::INVALID_USER)
			return back()->withError('Reset password gagal: Akun tidak ditemukan');
		return back()->withError('Reset password gagal: Kesalahan tidak diketahui');
	}
}