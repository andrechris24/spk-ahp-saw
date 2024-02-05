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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class HomeController extends Controller
{
	public static function refreshDB($model): void
	{ //Atur ulang id kriteria, sub kriteria, dan alternatif
		try {
			$max = $model->max('id') + 1;
			DB::statement("ALTER TABLE users AUTO_INCREMENT = $max");
		} catch (QueryException $e) {
			Log::error($e);
		}
	}
	public function index()
	{
		$jml = [];
		if (Auth::check()) {
			try {
				$jml = [
					'kriteria' => Kriteria::count(),
					'subkriteria' => SubKriteria::count(),
					'alternatif' => Alternatif::count()
				];
			} catch (QueryException $e) {
				Log::error($e);
				$jml['error'] = "Kesalahan SQLState #{$e->errorInfo[1]}/{$e->errorInfo[0]}. " .
					$e->errorInfo[2];
			}
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
			$req = $request->validate([
				'name' => ['bail', 'required', 'min:5', 'regex:/^[\pL\s\-]+$/u'],
				'email' => [
					'bail',
					'required',
					'email',
					'unique:users,email,' . Auth::id()
				],
				'current_password' => ['bail', 'required', 'current_password'],
				'password' => ['nullable', 'bail', 'confirmed', 'between:8,20'],
				'password_confirmation' => 'required_with:password'
			], User::$message);
			$req['name'] = Str::title($req['name']);
			$req['email'] = Str::lower($req['email']);
			if (empty($req['password'])) {
				unset($req['password']);
				unset($req['password_confirmation']);
			} else
				$req['password'] = Hash::make($req['password']);
			User::findOrFail(Auth::id())->update($req);
			return response()->json(['message' => 'Akun sudah diupdate']);
		} catch (ModelNotFoundException $e) {
			return response()->json(['message' => 'Akun tidak ditemukan'], 404);
		} catch (QueryException $db) {
			if ($db->errorInfo[1] === 1062) {
				return response()->json([
					'message' => "Email \"$request->email\" sudah digunakan",
					'errors' => ['email' => 'Email sudah digunakan']
				]);
			}
			Log::error($db);
			return response()->json(['message' => $db->errorInfo[2]], 500);
		}
	}
	public function delAkun(Request $request)
	{
		try {
			$request->validate(User::$delakunrule);
			User::findOrFail(Auth::id())->delete();
			Auth::logout();
			Session::invalidate();
			Session::regenerateToken();
			return response()->json(['message' => 'Akun sudah dihapus']);
		} catch (ModelNotFoundException) {
			return response()->json(['message' => 'Akun tidak ditemukan'], 404);
		} catch (QueryException $db) {
			Log::error($db);
			return response()->json(['message' => $db->errorInfo[2]], 500);
		}
	}
}