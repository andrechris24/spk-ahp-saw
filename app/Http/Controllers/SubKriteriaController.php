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

class SubKriteriaController extends Controller
{
	public function getCount()
	{
		$criterias = Kriteria::get();
		$subcriterias = SubKriteria::get();
		$scUnique = $subcriterias->unique(['name']);
		$totalsub = [];
		foreach ($criterias as $kr) {
			$totalsub[] = SubKriteria::where('kriteria_id', $kr->id)->count();
		}
		return response()->json([
			'total' => $subcriterias->count(),
			'max' => collect($totalsub)->max(),
			'duplicates' => $subcriterias->diff($scUnique)->count()
		]);
	}
	public function index()
	{
		$kriteria = Kriteria::get();
		$compskr = SubKriteriaComp::count();
		if ($kriteria->isEmpty()) {
			return redirect()->route('kriteria.index')
				->withWarning('Tambahkan kriteria dulu sebelum menambah sub kriteria.');
		}
		return view('main.subkriteria.index', compact('kriteria', 'compskr'));
	}
	public function show(Request $request)
	{
		$subkriteria = SubKriteria::query();
		return DataTables::eloquent($subkriteria)
			->editColumn('kriteria_id', function (SubKriteria $skr) {
				return $skr->kriteria->name;
			})->addColumn('desc_kr', function (SubKriteria $kr) {
			return $kr->kriteria->desc;
		})->toJson();
	}
	public function store(Request $request)
	{
		$request->validate(SubKriteria::$rules, SubKriteria::$message);
		$req = $request->all();
		try {
			$namakriteria = SubKriteriaCompController::nama_kriteria($req['kriteria_id']);
			if (SubKriteria::where('kriteria_id', $req['kriteria_id'])->count() >= 20) {
				return response()->json([
					'message' => "Batas jumlah sub kriteria $namakriteria sudah tercapai."
				], 400);
			}
			SubKriteria::create($req);
			$querytype = "Sub Kriteria $namakriteria sudah diinput. ";
			$cek = SubKriteriaComp::where('idkriteria', $req['kriteria_id'])->count();
			if ($cek > 0) {
				SubKriteriaComp::where('idkriteria', $req['kriteria_id'])->delete();
				SubKriteria::where('kriteria_id', $req['kriteria_id'])
					->update(['bobot' => 0.00000]);
				$querytype .= "Silahkan input ulang perbandingan sub kriteria $namakriteria.";
			}
			return response()->json(['message' => $querytype]);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	public function update(Request $request)
	{
		$request->validate(SubKriteria::$rules, SubKriteria::$message);
		$req = $request->all();
		try {
			SubKriteria::updateOrCreate(
				['id' => $req['id']],
				['name' => $req['name'], 'kriteria_id' => $req['kriteria_id']]
			);
			return response()->json(['message' => 'Sub Kriteria sudah diupdate']);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	public function edit($id)
	{
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
	public function destroy($id)
	{
		try {
			$cek = SubKriteria::findOrFail($id);
			$idkriteria = $cek->kriteria_id;
			$namakriteria = $cek->kriteria->name;
			$cek->delete();
			$subkrcomp = SubKriteriaComp::where('idkriteria', $idkriteria);
			$message = 'Sub Kriteria sudah dihapus. ';
			if ($subkrcomp->count() > 0) {
				$subkrcomp->delete();
				SubKriteria::where('kriteria_id', $idkriteria)
					->update(['bobot' => 0.00000]);
				$message .= "Silahkan input ulang perbandingan sub kriteria $namakriteria.";
				if (SubKriteriaComp::count() == 0)
					SubKriteriaComp::truncate();
			}
			return response()->json(['message' => $message]);
		} catch (ModelNotFoundException) {
			return response()->json(['message' => 'Sub Kriteria tidak ditemukan.'], 404);
		} catch (QueryException $sql) {
			Log::error($sql);
			return response()->json(['message' => $sql->errorInfo[2]], 500);
		}
	}
}