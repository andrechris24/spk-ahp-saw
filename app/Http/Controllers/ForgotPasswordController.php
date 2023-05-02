<?php

namespace App\Http\Controllers;

use App\Models\User;
//use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Database\QueryException;
use Symfony\Component\Mailer\Exception\TransportException;

class ForgotPasswordController extends Controller
{
	// private function clearResets(){
	// 	$dt=Carbon::now()->addHours(2);
	// 	dd(DB::table('password_resets')->where('created_at','>',$dt)->first());
	// }
	public function showForgetPasswordForm(): View|Factory|Application|RedirectResponse
	{
		// $this->clearResets();
		if (Auth::viaRemember() || Auth::check())
			return redirect()->intended();
		return view('admin.forget-password');
	}

	public function submitForgetPasswordForm(Request $request)
	{
		$request->validate(['email' => 'bail|required|email|exists:users']);
		try {
			$status = Password::sendResetLink($request->only('email'));
			if ($status === Password::RESET_LINK_SENT)
				return back()->withSuccess('Link reset password sudah dikirim.');
			else if ($status === Password::RESET_THROTTLED) {
				return back()
					->withError('Tunggu sebentar sebelum meminta reset password lagi.');
			}
			return back()->withError('Pengiriman link reset password gagal');
		} catch (TransportException $err) {
			DB::table('password_resets')->where('email', $request->email)->delete();
			return back()->withErrors($err->getMessage());
		} catch (QueryException $sql) {
			return back()->withErrors($sql->getMessage());
		}
	}
	public function showResetPasswordForm($token): View|Factory|Application|RedirectResponse
	{
		if (Auth::viaRemember() || Auth::check())
			return redirect()->intended();
		$enctoken = DB::table('password_resets')->where('email', $_GET['email'])->first();
		if (!$enctoken) {
			return redirect('/forget-password')->withError(
				'Link reset password sudah kedaluarsa. Silahkan minta reset password lagi.'
			);
		}
		$cek = Hash::check($token, $enctoken->token);
		if (!$cek)
			return redirect('/login')->withError('Token reset password tidak valid');
		return view(
			'admin.reset-password',
			['token' => $token, 'email' => $_GET['email']]
		);
	}
	public function submitResetPasswordForm(Request $request)
	{
		$request->validate(User::$resetpass, User::$resetmsg);
		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function (User $user, string $password) {
				$user->forceFill([
					'password' => Hash::make($password)
				]);
				$user->save();
				event(new PasswordReset($user));
			}
		);
		if ($status === Password::PASSWORD_RESET)
			return redirect('/login')->withSuccess('Reset password berhasil');
		else if ($status === Password::INVALID_TOKEN)
			return back()->withErrors('Token tidak valid');
		else if ($status === Password::INVALID_USER)
			return back()->withErrors('Akun tidak ditemukan');
		return back()->withError('Reset password gagal');
	}
}