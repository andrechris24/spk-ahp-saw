<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
	public function index(): Factory|View|Application
	{
		if (Auth::check()) {
			$jml['kriteria'] = Kriteria::count();
			$jml['subkriteria'] = SubKriteria::count();
			$jml['alternatif'] = Alternatif::count();
			return view('main.index', compact('jml'));
		}
		return view('main.index');
	}
	public function profile(): Factory|View|Application
	{
		return view('main.profil');
	}
	public function updateProfil(Request $request)
	{
		$id = Auth::user()->id;
		try {
			$request->validate(
				[
					'name' => 'bail|required|min:5|regex:/^[\pL\s\-]+$/u',
					'email' => 'bail|required|email|unique:users,email,' . $id,
					'current_password' => 'bail|required|min:8',
					'password' => 'nullable|confirmed|between:8,20',
					'password_confirmation' => 'required_with:password',
				],
				[
					'name.required' => 'Nama harus diisi',
					'name.min' => 'Nama minimal 5 huruf',
					'name.regex' => 'Nama tidak boleh mengandung simbol dan angka',
					'email.required' => 'Email harus diisi',
					'email.unique' => 'Email ' . $request->email . ' sudah digunakan',
					'current_password.required' => 'Password lama harus diisi',
					'password.confirmed' => 'Password konfirmasi salah',
				]
			);
			$cekpass = Hash::check($request->current_password, Auth::user()->password);
			if (!$cekpass)
				return back()->withErrors(['current_password' => 'Password salah']);
			$req = $request->all();
			if (empty($req['password'])) {
				unset($req['password']);
				unset($req['password_confirmation']);
			} else $req['password'] = Hash::make($req['password']);
			$updprofil = User::findOrFail($id)->update($req);
			if ($updprofil) return back()->withSuccess('Akun sudah diupdate');
		} catch (ModelNotFoundException $e) {
			return back()->withError('Gagal update: Akun tidak ditemukan')
				->withErrors($e->getMessage());
		} catch (QueryException $db) {
			return back()->withError('Gagal update akun: ' . $db->getMessage());
		}
		return back()->withError('Gagal update akun: Kesalahan tidak diketahui');
	}
	public function delAkun(Request $request)
	{
		$id = Auth::user()->id;
		try {
			$request->validate(User::$delakunrule);
			$cekpass = Hash::check($request->del_password, Auth::user()->password);
			if (!$cekpass)
				return back()->withError('Gagal hapus akun: Password salah');
			Auth::logout();
			Session::flush();
			$delacc=User::findOrFail($id)->delete();
			if ($delacc) return redirect('/')->withSuccess('Akun sudah dihapus');
		} catch (ModelNotFoundException $e) {
			return back()->withError('Gagal hapus: Akun tidak ditemukan')
				->withErrors($e->getMessage());
		} catch (QueryException $db) {
			return back()->withError('Gagal hapus:' . $db->getMessage());
		}
		return back()->withError('Gagal hapus akun: Kesalahan tidak diketahui');
	}
}