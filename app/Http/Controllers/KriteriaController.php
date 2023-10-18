<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\KriteriaComp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class KriteriaController extends Controller
{
	public function index()
	{
		$compkr = KriteriaComp::count();
		return view('main.kriteria.index', compact('compkr'));
	}
	public function show(Request $request)
	{
		return DataTables::of(Kriteria::query())->make();
	}
	public function store(Request $request)
	{
		$request->validate(Kriteria::$rules, Kriteria::$message);
		try {
			Kriteria::create($request->all());
			$querytype = "Kriteria sudah diinput. ";
			if (KriteriaComp::exists()) {
				KriteriaComp::truncate();
				Kriteria::where('bobot', '<>', 0.00000)->update(['bobot' => 0.00000]);
				$querytype .= "Silahkan input ulang perbandingan kriteria.";
			}
			return response()->json(['message' => $querytype]);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	public function update(Request $request)
	{
		$request->validate(Kriteria::$rules, Kriteria::$message);
		$req=$request->all();
		try {
			Kriteria::updateOrCreate(
				['id' => $req['id']],
				['name' => $req['name'], 'type' => $req['type'], 'desc' => $req['desc']]
			);
			return response()->json(['message' => 'Kriteria sudah diupdate.']);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	public function edit($id)
	{
		try {
			$kriteria = Kriteria::where('id', $id)->firstOrFail();
			return response()->json($kriteria);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(["message" => $e->errorInfo[2]], 500);
		} catch (ModelNotFoundException $err) {
			return response()->json([
				'message' => 'Data Kriteria tidak ditemukan',
				'exception' => $err->getMessage()
			], 404);
		}
	}
	public function hapus($id)
	{
		try {
			Kriteria::findOrFail($id)->delete();
			$message = 'Kriteria sudah dihapus. ';
			if (KriteriaComp::exists()) {
				KriteriaComp::truncate();
				Kriteria::where('bobot', '<>', 0.00000)->update(['bobot' => 0.00000]);
				$message .= 'Silahkan input ulang perbandingan Kriteria.';
			}
			return response()->json(['message' => $message]);
		} catch (ModelNotFoundException $e) {
			return response()->json([
				'message' => 'Kriteria tidak ditemukan',
				'exception' => $e->getMessage()
			], 404);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
}