<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
	/**
	 * Write code on Method
	 *
	 * @return response()
	 */
	public function showForgetPasswordForm()
	{
		return view('admin.forget-password');
	}

	/**
	 * Write code on Method
	 *
	 * @return response()
	 */
	public function submitForgetPasswordForm(Request $request)
	{
		$tanggal = DB::table('password_resets');
		$request->validate([
			'email' => 'required|email|exists:users|unique:password_resets,email',
		], [
			'email.unique' => 'Anda tidak bisa meminta reset password lagi sebelum ...'
		]);
		$token = Str::random(64);
		DB::table('password_resets')->insert([
			'email' => $request->email,
			'token' => $token,
			'created_at' => Carbon::now()
		]);
		$mailresult = Mail::send(
			'email.forgetPassword',
			['token' => $token],
			function ($message) use ($request) {
				$message->to($request->email);
				$message->subject('Reset Password');
			}
		);
		if ($mailresult)
			return back()->with('success', 'Link reset password sudah dikirim.');
		return back()->with('error', 'Pengiriman link reset password gagal');
	}
	/**
	 * Write code on Method
	 *
	 * @return response()
	 */
	public function showResetPasswordForm($token)
	{
		return view(
			'admin.reset-password',
			['token' => $token, 'title' => 'Reset Password']
		);
	}
	/**
	 * Write code on Method
	 *
	 * @return response()
	 */
	public function submitResetPasswordForm(Request $request)
	{
		$request->validate([
			'password' => 'required|string|min:8|confirmed',
			'password_confirmation' => 'required'
		]);
		$updatePassword = DB::table('password_resets')
			->where([
				'token' => $request->token
			])
			->first();
		if (!$updatePassword)
			return back()->withInput()->with('warning', 'Token tidak valid!');
		$user = User::where('email', $updatePassword->email)
			->update(['password' => Hash::make($request->password)]);
		if ($user) {
			DB::table('password_resets')->where(['token' => $request->token])->delete();
			return redirect('/login')->with('success', 'Reset password berhasil');
		}
		return back()->withInput()->with('error', 'Reset password gagal');
	}
}
