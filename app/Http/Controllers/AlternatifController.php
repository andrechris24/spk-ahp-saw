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
			if ($request->id) {
				Alternatif::updateOrCreate(
					['id' => $request->id],
					['name' => $request->name, 'desc' => $request->desc]
				);
				$msg = 'Berhasil diupdate';
			} else {
				Alternatif::create($request->all());
				$msg = 'Berhasil diinput';
			}
			return response()->json(['message' => $msg]);
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