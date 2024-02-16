<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\Kriteria;
use App\Models\Nilai;
use App\Models\SubKriteria;
use App\Models\SubKriteriaComp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SubKriteriaController extends Controller
{
	public static function nama_kriteria($id)
	{
		try {
			$kriteria = Kriteria::find($id);
			return $kriteria['name'];
		} catch (QueryException $e) {
			Log::error($e);
			return "E{$e->errorInfo[0]}/" . $e->errorInfo[1];
		}
	}
	public static function resetNilai($subkr, $kr): void
	{
		try {
			$alt = Nilai::where('subkriteria_id', $subkr)
				->where('kriteria_id', '<>', $kr)->firstOrFail();
			Nilai::where('alternatif_id', $alt->alternatif_id)->delete();
			Hasil::where('alternatif_id', $alt->alternatif_id)->delete();
		} catch (QueryException $e) {
			Log::error($e);
		} catch (ModelNotFoundException) {
			return;
		}
	}
	public function getCount()
	{
		$criterias = Kriteria::get();
		$subcriterias = SubKriteria::get();
		$totalsub = [];
		$duplicate = 0;
		foreach ($criterias as $kr) {
			$totalsub[] = SubKriteria::where('kriteria_id', $kr->id)->count();
			$subs = SubKriteria::where('kriteria_id', $kr->id)->get();
			$subUnique = $subs->unique(['name']);
			$duplicate += $subs->diff($subUnique)->count();
		}
		return response()->json([
			'total' => $subcriterias->count(),
			'max' => collect($totalsub)->max(),
			'duplicate' => $duplicate
		]);
	}
	public function index()
	{
		$kriteria = Kriteria::get();
		if ($kriteria->isEmpty()) {
			return to_route('kriteria.index')
				->withWarning('Tambahkan kriteria dulu sebelum menambah sub kriteria.');
		}
		return view('main.subkriteria.index', compact('kriteria'));
	}
	public function datatables()
	{
		return DataTables::of(SubKriteria::query())
			->addColumn('kr_name', function (SubKriteria $skr) {
				return $skr->kriteria->name;
			})->addColumn('desc_kr', function (SubKriteria $kr) {
				return $kr->kriteria->desc;
			})->make();
	}
	public function store(Request $request)
	{
		$request->validate(SubKriteria::$rules, SubKriteria::$message);
		try {
			if ($request->id) {
				SubKriteria::updateOrCreate(
					['id' => $request->id],
					['name' => $request->name, 'kriteria_id' => $request->kriteria_id]
				);
				$this->resetNilai($request->id, $request->kriteria_id);
				$msg = "Berhasil diupdate";
			} else {
				if (SubKriteria::where('kriteria_id', $request->kriteria_id)->count() >= 20) {
					$namakriteria = $this->nama_kriteria($request->kriteria_id);
					return response()->json([
						'message' => "Batas jumlah sub kriteria $namakriteria sudah tercapai"
					], 400);
				}
				SubKriteria::create($request->all());
				$msg = "Berhasil diinput";
			}
			return response()->json(['message' => $msg]);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	public function edit(SubKriteria $subkriterium)
	{
		return response()->json($subkriterium);
	}
	public function destroy(SubKriteria $subkriterium)
	{
		$subkriterium->delete();
		if (!SubKriteriaComp::exists())
			SubKriteriaComp::truncate();
		$model = new SubKriteria;
		HomeController::refreshDB($model);
		return response()->json(['message' => "Dihapus"]);
	}
}