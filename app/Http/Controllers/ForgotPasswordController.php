<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

class ForgotPasswordController extends Controller
{
	// private function clearResets(){
	// 	$dt=Carbon::now()->addHours(2);
	// 	dd(DB::table('password_resets')->where('created_at','>',$dt)->first());
	// }
	/**
	 * Write code on Method
	 *
	 * @return response()
	 */
	public function showForgetPasswordForm()
	{
		// $this->clearResets();
		if(Auth::viaRemember() || Auth::check()) return redirect()->intended('/');
		return view('admin.forget-password');
	}

	/**
	 * Write code on Method
	 *
	 * @return response()
	 */
	public function submitForgetPasswordForm(Request $request)
	{
		$tanggal = DB::table('password_resets')->where('email',$request->email)->first();
		if(isset($tanggal)) {
			Carbon::setLocale('id');
			$dt_next=Carbon::parse($tanggal->created_at)
			->addHours(2)->translatedFormat('d F Y G:i');
		}
		$request->validate([
			'email' => 'required|email|exists:users|unique:password_resets,email',
		], [
			'email.unique' => 'Anda tidak bisa meminta reset password lagi sebelum '.
			($dt_next??'...')
		]);
		$token = Str::random(64);
		try {
			DB::table('password_resets')->insert([
				'email' => $request->email,
				'token' => $token,
				'created_at' => Carbon::now()
			]);
			Mail::send(
				'email.forgetPassword',
				['token' => $token],
				function ($message) use ($request) {
					$message->to($request->email);
					$message->subject('Reset Password');
				}
			);
			return back()->withSuccess('Link reset password sudah dikirim.');
		} catch (\Exception $th) {
			DB::table('password_resets')->where('email', '=', $request->email)->delete();
			return back()->withError('Pengiriman link reset password gagal:')
				->withErrors($th->getMessage());
		} catch (QueryException $err) {
			return back()->withError('Gagal membuat token reset password:')
				->withErrors($err->getMessage());
		}
		return back()->withError('Pengiriman link reset password gagal');
	}
	/**
	 * Write code on Method
	 *
	 * @return response()
	 */
	public function showResetPasswordForm($token)
	{
		if(Auth::viaRemember() || Auth::check()) return redirect()->intended('/');
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
			'password' => 'required|string|between:8,20|confirmed',
			'password_confirmation' => 'required'
		]);
		try {
			$updatePassword = DB::table('password_resets')
				->where(['token' => $request->token])->first();
			if (!$updatePassword)
				return back()->withInput()->withWarning('Token tidak valid!');
			$user = User::where('email', $updatePassword->email)
				->update([
					'password' => Hash::make($request->password),
					'remember_token'=>null
				]);
			if ($user) {
				DB::table('password_resets')->where('token', $request->token)->delete();
				return redirect('/login')->withSuccess('Reset password berhasil');
			}
		} catch (QueryException $sql) {
			return back()->withError('Reset password gagal:')
				->withErrors($sql->getMessage());
		}
		return back()->withError('Reset password gagal');
	}
}
