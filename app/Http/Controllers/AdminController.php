<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

class AdminController extends Controller
{
	public function index(){
		return view('admin.dashboard');
	}
	public function register(){
		return view('admin.register');
	}
	public function forgetPassword(){
		return view('admin.forget-password');
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
		return redirect()->back()->withInput()->with('status','Registrasi akun gagal');
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
			return redirect()->back()->with('status','Password salah');
		}
		return redirect()->back()->with('status','Email '.$requests['email'].' tidak ditemukan');
	}
	public function logout(){
		Session::flush();
		return redirect('login')->with('status','Anda sudah logout.');
	}
	public function update(Request $request, $id){
		$request->validate(User::$profilrules);
		$d=User::find($id);
		if($d==null){
			return redirect('admin/profile/'.$id)->withInput()->with('status','Akun tidak ditemukan');
		}
		$req=$request->all();
		$req['password']=Hash::make($request->password);
		$data=User::find($id)->update($req);
		if($data){
			return redirect('admin/profile/'.$id)->with('status','Profil sudah diupdate');
		}
		return redirect('admin/profile/'.$id)->withInput()->with('status','Profil gagal diupdate');
	}
	public function postForgetPassword(Request $request){
		$request->validate(User::$forgetpass);
		$currequest=$request->all();
		$result=User::find($currequest['email']);
		if($result==null)
			return redirect('forget-password')->with('status','Email '.$currequest['email'].' tidak ditemukan');
		$status = Password::sendResetLink(
			$request->only('email')
		);
		return $status === Password::RESET_LINK_SENT
			? back()->with(['status' => 'Link reset password sudah dikirim. Cek folder spam jika belum masuk.'])
			: back()->withInput()->withErrors(['email' =>'Gagal mengirim link reset password']);
	}
	public function resetPassword($token){
		return view('admin.reset-password',['token'=>$token]);
	}
	public function postResetPassword(Request $request){
		$request->validate(User::$resetpass);
		$currequest=$request->all();
		$cek=User::find();
		if($cek==null)
			return redirect()->back()->with('status','Akun tidak ditemukan');
		$status=Password::reset(
			$request->only('email','password','password_confirmation','token'),
			function($user,$password){
				//
			}
		);
		return $status === Password::PASSWORD_RESET
			? redirect()->route('login')->with('status', 'Reset password berhasil. Silahkan login dengan password baru.')
			: back()->withErrors(['email' => [__($status)]]);
	}
}
