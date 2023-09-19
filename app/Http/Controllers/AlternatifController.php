<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class AlternatifController extends Controller
{
	public function index(): Factory|View|Application
	{
		return view('main.alternatif.index');
	}
	public function show(Request $request)
	{
		return DataTables::of(Alternatif::query())->make();
	}
	public function store(Request $request)
	{
		$request->validate(Alternatif::$rules, Alternatif::$message);
		$alterID = $request->id;
		try {
			if ($alterID) {
				$alter = Alternatif::updateOrCreate(
					['id' => $alterID],
					['name' => $request->name]
				);
				$querytype = "diupdate.";
			} else {
				$alter = Alternatif::create($request->all());
				$querytype = "ditambah.";
			}
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->getMessage()], 500);
		}
		if ($alter)
			return response()->json(['message' => 'Alternatif sudah ' . $querytype]);
		return response()->json(['message' => 'Kesalahan tidak diketahui'], 500);
	}
	public function edit($id)
	{
		try {
			$alter = Alternatif::where('id', $id)->firstOrFail();
			return response()->json($alter);
		} catch (QueryException $e) {
			return response()->json(["message" => $e->getMessage()], 500);
		} catch (ModelNotFoundException $e) {
			return response()->json([
				'message' => 'Data Alternatif tidak ditemukan',
				'exception'=>$e->getMessage()
			], 404);
		}
	}
	public function hapus($id)
	{
		try {
			Alternatif::findOrFail($id)->delete();
			return response()->json(['message' => 'Alternatif sudah dihapus']);
		} catch (ModelNotFoundException $e) {
			return response()->json([
				'message' => 'Alternatif tidak ditemukan',
				'exception'=>$e->getMessage()
			], 404);
		} catch (QueryException $sql) {
			Log::error($sql);
			return response()->json(['message' => $sql->getMessage()], 500);
		}
	}
}