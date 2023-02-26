<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AdminController extends Controller
{
	public function index(){
		return view('admin.dashboard');
	}
	public function register(){
		return view('admin.register');
	}
	public function edit($id){
		$use=User::find($id);
		return view('admin.profile',compact('use'));
	}
	public function postRegister(Request $request){
		$request->validate(User::$rules);
		$requests=$request->all();
		$requests['password']=Hash::make($request->password);
		$user=User::create($requests);
		if($user){
			return redirect('login')->with('status','Registrasi akun berhasil');
		}
		return redirect('register')->with('status','Registrasi akun gagal');
	}
	public function login(){
		return view('admin.login');
	}
	public function postLogin(Request $request){
		$request->validate(User::$loginrules);
		$requests=$request->all();
		$data=User::where('email',$requests['email'])->first();
		$cek=Hash::check($requests['password'],$data->password);
		if($cek){
			Session::put('admin',$data->email);
			Session::put('admin_id',$data->id);
			return redirect('admin');
		}else{
			return redirect('login')->with('status','Password salah');
		}
		return redirect('login')->with('status','E-mail salah atau akun tidak ditemukan');
	}
	public function logout(){
		Session::flush();
		return redirect('login')->with('status','Anda sudah logout.');
	}
	public function update(Request $request, $id){
		$request->validate(User::$profilrules);
		$d=User::find($id);
		if($d==null){
			return redirect('admin/profile/'.$id)->with('status','Akun tidak ditemukan');
		}
		$req=$request->all();
		$req['password']=Hash::make($request->password);
		$data=User::find($id)->update($req);
		if($data){
			return redirect('admin/profile/'.$id)->with('status','Profil sudah diupdate');
		}
		return redirect('admin/profile/'.$id)->with('status','Profil gagal diupdate');
	}
}
