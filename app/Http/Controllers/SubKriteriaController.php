<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Nilai;
use App\Models\SubKriteria;
use App\Models\SubKriteriaComp;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubKriteriaController extends Controller
{
	public function index()
	{
		$kriteria = Kriteria::get();
		$subkriteria = SubKriteria::get();
		$compskr = SubKriteriaComp::count();
		$ceknilai = Nilai::count();
		if (count($kriteria) === 0) {
			return redirect('kriteria')
				->withWarning('Tambahkan kriteria dulu sebelum menambah sub kriteria');
		}
		return view(
			'main.subkriteria.index',
			compact('kriteria', 'subkriteria', 'compskr', 'ceknilai')
		);
	}
	public function show(Request $request)
	{
		try {
			$subkriteria = SubKriteria::query();
			return DataTables::eloquent($subkriteria)
				->editColumn('kriteria_id', function (SubKriteria $skr) {
					return $skr->kriteria->name;
				})->toJson();
		} catch (QueryException $e) {
			return response()->json(['message' => $e->getMessage()], 500);
		}
	}
	public function store(Request $request)
	{
		$request->validate(SubKriteria::$rules, SubKriteria::$message);
		$subID = $request->id;
		try {
			if ($subID) {
				$sub = SubKriteria::updateOrCreate(
					['id' => $subID],
					['name' => $request->name, 'kriteria_id' => $request->kriteria_id]
				);
				$querytype = "diupdate.";
			} else {
				$sub = SubKriteria::create($request->all());
				$namakriteria = $sub->kriteria->name;
				$querytype = "ditambah. ";
				$cek = SubKriteriaComp::where('idkriteria', '=', $request->kriteria_id)
					->count();
				if ($cek > 0) {
					SubKriteriaComp::where('idkriteria', '=', $request->kriteria_id)
						->delete();
					SubKriteria::where('kriteria_id', '=', $request->kriteria_id)
						->update(['bobot', 0.0000]);
					$querytype .= "Silahkan input ulang perbandingan sub kriteria $namakriteria.";
				}
			}
		} catch (QueryException $e) {
			return response()->json(['message' => $e->getMessage()], 500);
		}
		if ($sub) {
			return response()->json(['message' => 'Sub Kriteria sudah ' . $querytype]);
		}
		return response()->json(['message' => 'Kesalahan tidak diketahui'], 500);
	}
	public function edit($id)
	{
		try {
			$sub = SubKriteria::where('id', $id)->firstOrFail();
			return response()->json($sub);
		} catch (QueryException $e) {
			return response()->json(["message" => $e->getMessage()], 500);
		} catch (ModelNotFoundException $err) {
			return response()->json(['message' => $err->getMessage()], 404);
		}
	}

	public function destroy($id)
	{
		try {
			$cek = SubKriteria::findOrFail($id);
			$idkriteria = $cek->kriteria_id;
			$namakriteria = $cek->kriteria->name;
			$getalt = Nilai::where('kriteria_id', $id)->first();
			$cek->delete();
			Nilai::where('alternatif_id', $getalt->alternatif_id)->delete();
			$subkrcomp = SubKriteriaComp::where('idkriteria', $cek->kriteria_id);
			if ($subkrcomp->count() > 0) {
				$subkrcomp->delete();
				SubKriteria::where('kriteria_id', $idkriteria)
					->update(['bobot' => 0.0000]);
				return response()->json([
					'message' =>
					'Data Sub Kriteria sudah dihapus. ' .
					'Silahkan input ulang perbandingan sub kriteria ' . $namakriteria . '.'
				]);
			}
			return response()->json(['message' => 'Data Sub Kriteria sudah dihapus']);
		} catch (ModelNotFoundException $e) {
			return response()->json(['message' => 'Data Sub Kriteria tidak ditemukan'], 404);
		} catch (QueryException $sql) {
			return response()->json(['message' => $sql->getMessage()], 500);
		}
	}
}