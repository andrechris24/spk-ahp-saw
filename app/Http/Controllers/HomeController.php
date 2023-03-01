<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
	public function index()
	{
		return view('main.index',['title'=>'Beranda']);
	}
	public function redirect(){
		return redirect('/login')->with(
			'error', 
			'Anda harus login dulu untuk menggunakan Sistem Pendukung Keputusan.'
		);
	}
}
