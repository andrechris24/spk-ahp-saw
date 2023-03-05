<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
	public function index()
	{
		return view('main.index');
	}
	public function redirect()
	{
		return redirect('/login')->with(
			'error',
			'Anda harus login dulu untuk menggunakan Sistem Pendukung Keputusan.'
		);
	}
	public function profile()
	{
		return view('main.profil');
	}
	public function updateProfil(Request $request)
	{
		$id = auth()->user()->id;
		$cek= User::find($id);
		if(!$cek) 
			return back()->withInput()->with('error', 'Gagal update akun: Akun tidak ditemukan');
		$request->validate(
			[
				'name' => 'required',
				'email' => 'required|email|unique:users,email,' . $id,
				'current_password' => 'required|min:8',
				'password' => 'confirmed|between:8,20',
				'password_confirmation' => 'required_with:password|same:password',
			]
		);
		$cekpass = Hash::check($request->current_password, auth()->user()->password);
		if (!$cekpass) 
			return back()->with('error', 'Gagal update akun: Password salah');
		$req = $request->all();
		$req['password'] = Hash::make($req['password']);
		$updprofil =$cek->update($req);
		if ($updprofil) return back()->with('success', 'Akun sudah diupdate');
		return back()->withInput()->with('error', 'Akun gagal diupdate');
	}
}
