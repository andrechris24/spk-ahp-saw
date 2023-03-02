<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
	public function index()
	{
		return view('main.index', ['title' => 'Beranda']);
	}
	public function redirect()
	{
		return redirect('/login')->with(
			'error',
			'Anda harus login dulu untuk menggunakan Sistem Pendukung Keputusan.'
		);
	}
	public function profile(){
		return view('main.profil', ['title' => 'Edit Akun']);
	}
	public function updateProfil(){
		$request->validate(
			[
				'name' => 'required',
				'email' => 'required|email|unique:users,email,'.$this->user->id,
				'current_password' => 'required|min:8',
				'password' => 'confirmed|min:8',
				'password_confirmation' => 'required_with:password|same:password',
			]
		);
		$cekpass = Hash::check($request->current_password, auth()->user()->password);
		if(!$cekpass) return back()->with('error', 'Password salah');
		$req = $request->all();
		$req['password']=Hash::make($req['password']);
		$updprofil = User::find($id)->update($req);
		if ($updprofil) return back()->with('success', 'Akun sudah diupdate');
		return back()->withInput()->with('error', 'Akun gagal diupdate');
	}
}
