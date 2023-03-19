<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\Alternatif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class HomeController extends Controller
{
	public function index()
	{
		if (auth()) {
			$jml['kriteria'] = Kriteria::count();
			$jml['subkriteria'] = SubKriteria::count();
			$jml['alternatif'] = Alternatif::count();
			return view('main.index', compact('jml'));
		}
		return view('main.index');
	}
	public function profile()
	{
		return view('main.profil');
	}
	public function updateProfil(Request $request)
	{
		$id = auth()->user()->id;
		try {
			$cek = User::find($id);
			if (!$cek)
				return back()->withInput()->withError('Gagal update: Akun tidak ditemukan');
			$request->validate(
				[
					'name' => 'required',
					'email' => 'bail|required|email|unique:users,email,' . $id,
					'current_password' => 'bail|required|min:8',
					'password' => 'nullable|confirmed|between:8,20',
					'password_confirmation' => 'required_with:password',
				],
				[
					'name.required' => 'Nama harus diisi',
					'email.required' => 'Email harus diisi',
					'email.unique' => 'Email ' . $request->email . ' sudah digunakan',
					'current_password.required' => 'Password lama harus diisi',
					'password.confirmed' => 'Password konfirmasi salah',
				]
			);
			$cekpass = Hash::check($request->current_password, auth()->user()->password);
			if (!$cekpass)
				return back()->withError('Gagal update akun: Password salah');
			$req = $request->all();
			$req['password'] = Hash::make($req['password']);
			$updprofil = $cek->update($req);
			if ($updprofil) return back()->withSuccess('Akun sudah diupdate');
		} catch (QueryException $db) {
			return back()->withInput()->withError('Gagal update akun:')
				->withErrors($db->getMessage());
		}
		return back()->withInput()->withError('Akun gagal diupdate');
	}
}
