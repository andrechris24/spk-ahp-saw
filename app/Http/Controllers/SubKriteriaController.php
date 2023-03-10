<?php

namespace App\Http\Controllers;

use App\Models\SubKriteria;
use App\Models\SubKriteriaComp;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class SubKriteriaController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$kriteria = Kriteria::get();
		$subkriteria = SubKriteria::get();
		return view('main.subkriteria.index', compact('kriteria', 'subkriteria'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$request->validate(SubKriteria::$rules, SubKriteria::$message);
		$subs = $request->all();
		$subkriteria = SubKriteria::create($subs);
		if ($subkriteria) {
			$cek=SubKriteriaComp::where('idkriteria','=',$request->kriteria_id)->count();
			if($cek>0){
				DB::table('subkriteria_banding')
				->where('idkriteria','=',$request->kriteria_id)
				->delete();
				return back()
				->with(
					'success',
					'Sub Kriteria sudah ditambahkan. Silahkan input ulang perbandingan sub kriteria.'
				);
			}
			return back()->with('success', 'Sub Kriteria sudah ditambahkan');
		}
		return back()->with('error', 'Gagal menambah sub kriteria');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\SubKriteria  $subKriteria
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$cek = SubKriteria::find($id);
		if (!$cek) return back()->with('error', 'Data Sub Kriteria tidak ditemukan');
		$req = $request->all();
		$upd = $cek->update($req);
		if ($upd) return back()->with('success', 'Data Sub Kriteria sudah diupdate');
		return back()->with('error', 'Gagal mengupdate data sub kriteria');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\SubKriteria  $subKriteria
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(SubKriteria $subKriteria)
	{
		$cek = SubKriteria::find($subKriteria->id);
		if (!$cek) return back()->with('error', 'Data Sub Kriteria tidak ditemukan');
		$del = $cek->delete();
		if ($del) {
			// $cekhasil = SubKriteriaComp::where('idkriteria','=','')->count();
			// if ($cekhasil > 0) {
			// 	DB::table('subkriteria_banding')->where('idkriteria','=','')->delete();
			// 	return back()
			// 		->with(
			// 			'success', 
			// 			'Data Sub Kriteria sudah dihapus. Silahkan input ulang perbandingan.'
			// 		);
			// }
			return back()->with('success', 'Data Sub Kriteria sudah dihapus');
		}
		return back()->with('error', 'Data sub kriteria gagal dihapus');
	}
}
