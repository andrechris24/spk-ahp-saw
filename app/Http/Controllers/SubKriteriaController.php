<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\SubKriteriaComp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SubKriteriaController extends Controller {
	public static function nama_kriteria($id) {
		try {
			$kriteria = Kriteria::find($id);
			return $kriteria['name'];
		} catch (QueryException $e) {
			Log::error($e);
			return "E".$e->errorInfo[0].'/'.$e->errorInfo[1];
		}
	}
	public function getCount() {
		$criterias = Kriteria::get();
		$subcriterias = SubKriteria::get();
		// $scUnique = $subcriterias->unique(['name','kriteria_id']);
		$totalsub = [];
		foreach($criterias as $kr) {
			$totalsub[] = SubKriteria::where('kriteria_id', $kr->id)->count();
		}
		return response()->json([
			'total' => $subcriterias->count(),
			'max' => collect($totalsub)->max()
		]);
	}
	public function index() {
		$kriteria = Kriteria::get();
		if($kriteria->isEmpty()) {
			return to_route('kriteria.index')
				->withWarning('Tambahkan kriteria dulu sebelum menambah sub kriteria.');
		}
		return view('main.subkriteria.index', compact('kriteria'));
	}
	public function show() {
		return DataTables::eloquent(SubKriteria::query())
			->addColumn('kr_name', function (SubKriteria $skr) {
				return $skr->kriteria->name;
			})->addColumn('desc_kr', function (SubKriteria $kr) {
				return $kr->kriteria->desc;
			})->toJson();
	}
	public function store(Request $request) {
		$request->validate(SubKriteria::$rules, SubKriteria::$message);
		$req = $request->all();
		try {
			$namakriteria = $this->nama_kriteria($req['kriteria_id']);
			if(SubKriteria::where('kriteria_id', $req['kriteria_id'])->count() >= 20) {
				return response()->json([
					'message' => "Batas jumlah sub kriteria $namakriteria sudah tercapai."
				], 400);
			}
			SubKriteria::create($req);
			return response()->json([
				'message' => "Sub Kriteria $namakriteria sudah diinput."
			]);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	public function update(Request $request) {
		$request->validate(SubKriteria::$rules, SubKriteria::$message);
		$req = $request->all();
		try {
			if($request->has('reset')) {
				SubKriteria::updateOrCreate(['id' => $req['id']], [
						'name' => $req['name'],
						'kriteria_id' => $req['kriteria_id'],
						'bobot' => 0.00000
					]);
			} else {
				SubKriteria::updateOrCreate(
					['id' => $req['id']],
					['name' => $req['name'], 'kriteria_id' => $req['kriteria_id']]
				);
			}
			return response()->json(['message' => "Sub Kriteria sudah diupdate"]);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	public function edit($id) {
		try {
			$sub = SubKriteria::findOrFail($id);
			return response()->json($sub);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		} catch (ModelNotFoundException) {
			return response()->json([
				"message" => 'Sub Kriteria yang Anda cari tidak ditemukan.'
			], 404);
		}
	}
	public function destroy($id) {
		try {
			$cek = SubKriteria::findOrFail($id);
			$namakriteria = $cek->kriteria->name;
			$cek->delete();
			if(!SubKriteriaComp::exists())
				SubKriteriaComp::truncate();
			$model=new SubKriteria;
			HomeController::refreshDB($model);
			return response()->json([
				'message' => "Sub Kriteria $namakriteria sudah dihapus."
			]);
		} catch (ModelNotFoundException) {
			return response()->json([
				'message' => 'Sub Kriteria tidak ditemukan.'
			], 404);
		} catch (QueryException $sql) {
			Log::error($sql);
			return response()->json(['message' => $sql->errorInfo[2]], 500);
		}
	}
}