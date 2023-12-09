<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class AlternatifController extends Controller {
	public function getCount() {
		$alternatives = Alternatif::get();
		$altUnique = $alternatives->unique(['name']);
		return response()->json([
			'total' => $alternatives->count(),
			'duplicates' => $alternatives->diff($altUnique)->count()
		]);
	}
	public function index() {
		return view('main.alternatif.index');
	}
	public function show() {
		return DataTables::of(Alternatif::query())->make();
	}
	public function store(Request $request) {
		$request->validate(Alternatif::$rules, Alternatif::$message);
		try {
			Alternatif::create($request->all());
			return response()->json(['message' => 'Alternatif sudah diinput']);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	public function update(Request $request) {
		$request->validate(Alternatif::$rules, Alternatif::$message);
		$req = $request->all();
		try {
			Alternatif::updateOrCreate(['id' => $req['id']], ['name' => $req['name']]);
			return response()->json(['message' => 'Alternatif sudah diupdate']);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	public function edit($id) {
		try {
			$alter = Alternatif::findOrFail($id);
			return response()->json($alter);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(["message" => $e->errorInfo[2]], 500);
		} catch (ModelNotFoundException) {
			return response()->json([
				'message' => 'Alternatif yang Anda cari tidak ditemukan.'
			], 404);
		}
	}
	public function hapus($id) {
		try {
			Alternatif::findOrFail($id)->delete();
			$model=new Alternatif;
			HomeController::refreshDB($model);
			return response()->json(['message' => 'Alternatif sudah dihapus']);
		} catch (ModelNotFoundException) {
			return response()->json(['message' => 'Alternatif tidak ditemukan.'], 404);
		} catch (QueryException $sql) {
			Log::error($sql);
			return response()->json(['message' => $sql->errorInfo[2]], 500);
		}
	}
}