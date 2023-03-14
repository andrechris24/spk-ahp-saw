<?php

namespace App\Http\Controllers;

use App\Models\SubKriteria;
use App\Models\SubKriteriaComp;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

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
		$compskr = SubKriteriaComp::count();
		if (count($kriteria) == 0) {
			return redirect('kriteria')
				->withWarning('Tambahkan kriteria dulu sebelum menginput sub kriteria');
		}
		return view(
			'main.subkriteria.index',
			compact('kriteria', 'subkriteria', 'compskr')
		);
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
		try{
			$subkriteria = SubKriteria::create($subs);
			if ($subkriteria) {
				$cek = SubKriteriaComp::where('idkriteria', '=', $request->kriteria_id)->count();
				if ($cek > 0) {
					DB::table('subkriteria_banding')
						->where('idkriteria', '=', $request->kriteria_id)
						->delete();
					return back()
						->withSuccess(
							'Sub Kriteria sudah ditambahkan. Silahkan input ulang perbandingan sub kriteria.'
						);
				}
				return back()->withSuccess('Sub Kriteria sudah ditambahkan');
			}
		}catch(QueryException $sql){
			return back()->withError('Gagal menambah sub kriteria')
			->withErrors($sql->getMessage());
		}
		return back()->withError('Gagal menambah sub kriteria');
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
		try{
			$cek = SubKriteria::find($id);
			if (!$cek) return back()->withError('Data Sub Kriteria tidak ditemukan');
			$req = $request->all();
		 $cek->update($req);
			return back()->withSuccess('Data Sub Kriteria sudah diupdate');
		}catch(QueryException $sql){
			return back()->withError('Gagal mengupdate data sub kriteria')
			->withErrors($sql->getMessage());
		}
		return back()->withError('Gagal mengupdate data sub kriteria');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\SubKriteria  $subKriteria
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		try{
			$cek = SubKriteria::find($id);
			if (!$cek) return back()->withError('Data Sub Kriteria tidak ditemukan');
			$del=$cek->delete();
			if($del) return back()->withSuccess('Data Sub Kriteria sudah dihapus');
		}catch(QueryException $sql){
			return back()->withError('Gagal hapus data sub kriteria')
			->withErrors($sql->getMessage());
		}
		return back()->withError('Data sub kriteria gagal dihapus');
	}
}
