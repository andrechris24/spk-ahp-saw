<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\KriteriaComp;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KriteriaController extends Controller
{
	public function index(): Factory|View|Application
	{
		$compkr = KriteriaComp::count();
		return view('main.kriteria.index', compact('compkr'));
	}
	public function show(Request $request)
	{
		try {
			return DataTables::of(Kriteria::query())->make();
		} catch (QueryException $e) {
			return response()->json(['message' => $e->getMessage()], 500);
		}
	}
	public function store(Request $request)
	{
		$request->validate(Kriteria::$rules, Kriteria::$message);
		$kritID = $request->id;
		try {
			if ($kritID) {
				$krit = Kriteria::updateOrCreate(
					['id' => $kritID],
					['name' => $request->name, 'type' => $request->type, 'desc' => $request->desc]
				);
				$querytype = "diupdate.";
			} else {
				$krit = Kriteria::create($request->all());
				$querytype = "diinput. ";
				if (KriteriaComp::exists()) {
					KriteriaComp::truncate();
					Kriteria::where('bobot', '<>', 0.0000)->update(['bobot' => 0.0000]);
					$querytype .= "Silahkan input ulang perbandingan kriteria.";
				}
			}
		} catch (QueryException $e) {
			return response()->json(['message' => $e->getMessage()], 500);
		}
		if ($krit)
			return response()->json(['message' => 'Kriteria sudah ' . $querytype]);
		return response()->json(['message' => 'Kesalahan tidak diketahui'], 500);
	}
	public function edit($id)
	{
		try {
			$kriteria = Kriteria::where('id', $id)->firstOrFail();
			return response()->json($kriteria);
		} catch (QueryException $e) {
			return response()->json(["message" => $e->getMessage()], 500);
		} catch (ModelNotFoundException $err) {
			return response()->json(['message' => $err->getMessage()], 404);
		}
	}
	public function hapus($id)
	{
		try {
			$del = Kriteria::findOrFail($id)->delete();
			if (KriteriaComp::exists()) {
				KriteriaComp::truncate();
				Kriteria::where('bobot', '<>', 0.0000)->update(['bobot' => 0.0000]);
				return response()->json([
					'message' => 'Kriteria sudah dihapus. Silahkan input ulang perbandingan.'
				]);
			}
			return response()->json(['message' => 'Kriteria sudah dihapus']);
		} catch (ModelNotFoundException $e) {
			return response()->json(['message' => 'Kriteria tidak ditemukan'], 404);
		} catch (QueryException $e) {
			return response()->json(['message' => $e->getMessage()], 500);
		}
	}
}