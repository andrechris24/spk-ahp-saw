<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
	public function index()
	{
		$jml = [];
		if (Auth::check()) {
			$jml = [
				'kriteria' => Kriteria::count(),
				'subkriteria' => SubKriteria::count(),
				'alternatif' => Alternatif::count()
			];
		}
		return view('main.index', compact('jml'));
	}
	public function profile()
	{
		return view('main.profil');
	}
	public function updateProfil(Request $request)
	{
		try {
			$req = $request->validate(
				[
					'name' => 'bail|required|min:5|regex:/^[\pL\s\-]+$/u',
					'email' => 'bail|required|email|unique:users,email,' . $id,
					'current_password' => 'bail|required|min:8',
					'password' => 'nullable|confirmed|between:8,20',
					'password_confirmation' => 'required_with:password'
				],
				[
					'name.required' => 'Nama harus diisi',
					'name.min' => 'Nama minimal 5 huruf',
					'name.regex' => 'Nama tidak boleh mengandung simbol dan angka',
					'email.required' => 'Email harus diisi',
					'email.unique' => 'Email ' . $request->email . ' sudah digunakan',
					'current_password.required' => 'Password lama harus diisi',
					'password.confirmed' => 'Password konfirmasi salah'
				]
			);
			if (!Hash::check($req['current_password'], Auth::user()->password)) {
				return response()->json([
					'message' => 'Password salah',
					'current_password' => 'Password salah'
				], 422);
			}
			// $req = $request->all();
			if (empty($req['password'])) {
				unset($req['password']);
				unset($req['password_confirmation']);
			} else
				$req['password'] = Hash::make($req['password']);
			User::findOrFail(Auth::id())->update($req);
			return response()->json(['message' => 'Akun sudah diupdate']);
		} catch (ModelNotFoundException $e) {
			return response()->json([
				'message' => 'Akun tidak ditemukan',
				'exception' => $e->getMessage()
			], 404);
		} catch (QueryException $db) {
			Log::error($db);
			return response()->json(['message' => $db->errorInfo[2]], 500);
		}
	}
	public function delAkun(Request $request)
	{
		$id = Auth::id();
		try {
			$req = $request->validate(User::$delakunrule);
			if (!Hash::check($req['del_password'], Auth::user()->password))
				return back()->withError('Gagal hapus akun: Password salah');
			User::findOrFail($id)->delete();
			Auth::logout();
			Session::invalidate();
			Session::regenerateToken();
			return redirect('/')->withSuccess(
				'Akun sudah dihapus. ' .
				'Terima kasih Anda telah menggunakan Aplikasi Sistem Pendukung Keputusan.'
			);
		} catch (ModelNotFoundException $e) {
			return back()->withError('Gagal hapus: Akun tidak ditemukan')
				->withErrors($e->getMessage());
		} catch (QueryException $db) {
			Log::error($db);
			return back()->withError('Gagal hapus:' . $db->errorInfo[2]);
		}
	}
}