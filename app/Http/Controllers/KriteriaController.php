<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class KriteriaController extends Controller
{
	public function getCount()
	{
		$criterias = Kriteria::get();
		$critUnique = $criterias->unique(['name']);
		$unused = 0;
		foreach ($criterias as $kr) {
			if (SubKriteria::where('kriteria_id', $kr->id)->count() === 0)
				$unused++;
		}
		return response()->json(['total' => $criterias->count(),
			'unused' => $unused,
			'duplicates' => $criterias->diff($critUnique)->count()]);
	}
	public function index()
	{
		return view('main.kriteria.index');
	}
	public function show()
	{
		return DataTables::of(Kriteria::query())
			->editColumn('type', function (Kriteria $krit) {
				return ucfirst($krit->type);
			})->make();
	}
	public function store(Request $request)
	{
		$request->validate(Kriteria::$rules, Kriteria::$message);
		try {
			if (Kriteria::count() >= 20) {
				return response()->json([
					'message' => 'Jumlah kriteria maksimal sudah tercapai.'
				], 400);
			}
			Kriteria::create($request->all());
			return response()->json(['message' => "Kriteria sudah diinput."]);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	public function update(Request $request)
	{
		$request->validate(Kriteria::$rules, Kriteria::$message);
		$req = $request->all();
		try {
			Kriteria::updateOrCreate(['id' => $req['id']], [
				'name' => $req['name'], 'type' => $req['type'], 'desc' => $req['desc']
			]);
			return response()->json(['message' => 'Kriteria sudah diupdate']);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	public function edit($id)
	{
		try {
			$kriteria = Kriteria::findOrFail($id);
			return response()->json($kriteria);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(["message" => $e->errorInfo[2]], 500);
		} catch (ModelNotFoundException) {
			return response()->json([
				'message' => 'Kriteria yang Anda cari tidak ditemukan.'
			], 404);
		}
	}
	public function hapus($id)
	{
		try {
			Kriteria::findOrFail($id)->delete();
			$model = new Kriteria;
			HomeController::refreshDB($model);
			return response()->json(['message' => 'Kriteria sudah dihapus.']);
		} catch (ModelNotFoundException) {
			return response()->json(['message' => 'Kriteria tidak ditemukan.'], 404);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
}