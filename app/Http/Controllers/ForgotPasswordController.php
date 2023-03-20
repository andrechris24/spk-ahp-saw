<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
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
	public function showForgetPasswordForm()
	{
		// $this->clearResets();
		if (Auth::viaRemember() || Auth::check()) return redirect()->intended('/');
		return view('admin.forget-password');
	}

	public function submitForgetPasswordForm(Request $request)
	{
		$tanggal = DB::table('password_resets')->where('email', $request->email)->first();
		if (isset($tanggal)) {
			Carbon::setLocale('id');
			$dt_next = Carbon::parse($tanggal->created_at)
				->addHours(2)->translatedFormat('d F Y G:i');
		}
		$request->validate([
			'email' => 'required|email|exists:users|unique:password_resets,email',
		], [
			'email.unique' => 'Anda tidak bisa meminta reset password lagi sebelum ' .
				($dt_next ?? '...')
		]);
		try {
			$status = Password::sendResetLink($request->only('email'));
			if ($status === Password::RESET_LINK_SENT)
				return back()->withSuccess('Link reset password sudah dikirim.');
			return back()->withError('Pengiriman link reset password gagal');
		} catch (TransportException $err) {
			DB::table('password_resets')->where('email', $request->email)->delete();
			return back()->withErrors($err->getMessage());
		} catch (QueryException $sql) {
			return back()->withErrors($sql->getMessage());
		}
	}
	public function showResetPasswordForm($token)
	{
		if (Auth::viaRemember() || Auth::check()) return redirect()->intended('/');
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
		return back()->withError('Reset password gagal');
	}
}
