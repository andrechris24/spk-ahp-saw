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
				->withWarning('Tambahkan kriteria dulu sebelum menambah subkriteria');
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
		try {
			$subkriteria = SubKriteria::create($subs);
			$namakriteria=$subkriteria->kriteria->name;
			if ($subkriteria) {
				$cek = SubKriteriaComp::where('idkriteria', '=', $request->kriteria_id)
				->count();
				if ($cek > 0) {
					SubKriteriaComp::where('idkriteria', '=', $request->kriteria_id)
						->delete();
					SubKriteria::where('kriteria_id',$request->kriteria_id)
					->update(['bobot',0.0000]);
					return back()
						->withSuccess(
							'Subkriteria sudah ditambahkan. '.
							'Silahkan input ulang perbandingan subkriteria '.$namakriteria.'.'
						);
				}
				return back()->withSuccess('Subkriteria sudah ditambahkan');
			}
		} catch (QueryException $sql) {
			return back()->withError('Gagal menambah subkriteria')
				->withErrors($sql->getMessage());
		}
		return back()->withError('Gagal menambah subkriteria');
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
		try {
			$cek = SubKriteria::find($id);
			if (!$cek) return back()->withError('Data Subkriteria tidak ditemukan');
			$req = $request->all();
			$cek->update($req);
			return back()->withSuccess('Data Subkriteria sudah diupdate');
		} catch (QueryException $sql) {
			return back()->withError('Gagal mengupdate data subkriteria')
				->withErrors($sql->getMessage());
		}
		return back()->withError('Gagal mengupdate data subkriteria');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\SubKriteria  $subKriteria
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		try {
			$cek = SubKriteria::find($id);
			$idkriteria=$cek->kriteria_id;
			$namakriteria=$cek->kriteria->name;
			if (!$cek) return back()->withError('Data Subkriteria tidak ditemukan');
			$cek->delete();
			$subkrcomp=SubKriteriaComp::where('idkriteria',$cek->kriteria_id);
			if($subkrcomp->count()>0){
				$subkrcomp->delete();
				SubKriteria::where('kriteria_id',$idkriteria)->update(['bobot',0.0000]);
				return back()->withSuccess(
					'Data Subkriteria sudah dihapus. '.
					'Silahkan input ulang perbandingan sub kriteria '.$namakriteria.'.'
				);
			}
			return back()->withSuccess('Data Subkriteria sudah dihapus');
		} catch (QueryException $sql) {
			return back()->withError('Gagal hapus data subkriteria')
				->withErrors($sql->getMessage());
		}
		return back()->withError('Data subkriteria gagal dihapus');
	}
}
