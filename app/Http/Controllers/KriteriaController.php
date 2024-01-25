<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\Kriteria;
use App\Models\KriteriaComp;
use App\Models\Nilai;
use App\Models\SubKriteria;
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
		return response()->json([
			'total' => $criterias->count(),
			'unused' => $unused,
			'duplicates' => $criterias->diff($critUnique)->count()
		]);
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
			if ($request->id) {
				Kriteria::updateOrCreate(
					['id' => $request->id],
					[
						'name' => $request->name,
						'type' => $request->type,
						'desc' => $request->desc
					]
				);
				$msg = 'Berhasil diupdate';
			} else {
				if (Kriteria::count() >= 20) {
					return response()->json([
						'message' => 'Jumlah kriteria maksimal sudah tercapai'
					], 400);
				}
				Kriteria::create($request->all());
				$msg = "Berhasil diinput";
			}
			return response()->json(['message' => $msg]);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	public function edit(Kriteria $kr)
	{
		return response()->json($kr);
	}
	public function hapus(Kriteria $kr)
	{
		$kr->delete();
		Nilai::truncate();
		Hasil::truncate();
		if (!Kriteria::exists())
			KriteriaComp::truncate();
		$model = new Kriteria;
		HomeController::refreshDB($model);
		return response()->json(['message' => 'Dihapus']);
	}
}