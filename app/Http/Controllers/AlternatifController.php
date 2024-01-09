<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class AlternatifController extends Controller
{
	public function getCount()
	{
		$alternatives = Alternatif::get();
		$altUnique = $alternatives->unique(['name']);
		return response()->json([
			'total' => $alternatives->count(),
			'duplicates' => $alternatives->diff($altUnique)->count()
		]);
	}
	public function index()
	{
		return view('main.alternatif.index');
	}
	public function show()
	{
		return DataTables::of(Alternatif::query())->make();
	}
	public function store(Request $request)
	{
		$request->validate(Alternatif::$rules, Alternatif::$message);
		try {
			Alternatif::create($request->all());
			return response()->json(['message' => 'Berhasil diinput']);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	public function update(Request $request)
	{
		$request->validate(Alternatif::$rules, Alternatif::$message);
		$req = $request->all();
		try {
			Alternatif::updateOrCreate(['id' => $req['id']], 
				['name' => $req['name'], 'desc' => $req['desc']]);
			return response()->json(['message' => 'Berhasil diupdate']);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	public function edit(Alternatif $alt)
	{
		return response()->json($alt);
	}
	public function hapus(Alternatif $alt)
	{
		$alt->delete();
		$model = new Alternatif;
		HomeController::refreshDB($model);
		return response()->json(['message' => 'Dihapus']);
	}
}