<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\Alternatif;
use App\Models\Hasil;;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
	public function normalisasi($arr,$type,$skor)
	{
		if($type=='cost') $hasil=min($arr)/$skor;
		else if($type=='benefit') $hasil=$skor/max($arr);
		else return "Invalid type: ".$type;
		return round($hasil,5);
	}
	public function getNilaiArr($kriteria_id)
	{
		$data=array();
		$kueri = Nilai::select('subkriteria.bobot as bobot')
		->join("subkriteria", "nilai.subkriteria_id",'=', "subkriteria.id")
			->where('nilai.kriteria_id','=',$kriteria_id)
			->get();
			foreach($kueri as $row){
				array_push($data,$row->bobot);
			}
		return $data;
	}
	public function getBobot($idkriteria){
		$kueri=Kriteria::find($idkriteria)->first();
		return $kueri->bobot;
	}
	public function simpanHasil($alt_id,$jumlah){
		try{
			$cek=Hasil::where('alternatif_id','=',$alt_id)->count();
			if($cek>0)
				Hasil::where('alternatif_id','=',$alt_id)->update(['hasil'=>$jumlah]);
			else Hasil::insert(['alternatif_id'=>$alt_id,'hasil'=>$jumlah]);
		}catch(QueryException $e){
			return false;
		}
		return true;
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$kriteria = Kriteria::get();
		if (count($kriteria) == 0) {
			return redirect('kriteria')
				->withWarning(
					'Tambahkan kriteria dan sub kriteria dulu sebelum melakukan penilaian alternatif'
				);
		}
		$subkriteria = SubKriteria::get();
		if (count($subkriteria) == 0) {
			return redirect('kriteria/sub')
				->withWarning(
					'Tambahkan sub kriteria dulu sebelum melakukan penilaian alternatif'
				);
		}
		$alternatif = Alternatif::get();
		if (count($alternatif) == 0) {
			return redirect('alternatif')
				->withWarning('Tambahkan alternatif dulu sebelum melakukan penilaian');
		}
		$nilaialt = Nilai::leftJoin('alternatif', 'alternatif.id', '=', 'nilai.alternatif_id')
			->leftJoin('kriteria', 'kriteria.id', '=', 'nilai.kriteria_id')
			->leftJoin('subkriteria', 'subkriteria.id', '=', 'nilai.subkriteria_id')->get();
		return view(
			'main.alternatif.nilai',
			compact('kriteria', 'subkriteria', 'alternatif', 'nilaialt')
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
		$request->validate(Nilai::$rules, Nilai::$message);
		$scores = $request->all();
		$cek = Nilai::where('alternatif_id', '=', $scores['alternatif_id'])->count();
		if ($cek > 0)
			return back()->withError('Alternatif sudah digunakan dalam penilaian');
		for ($a = 0; $a < count($scores['kriteria_id']); $a++) {
			$nilai = new Nilai();
			$nilai->alternatif_id = $scores['alternatif_id'];
			$nilai->kriteria_id = $scores['kriteria_id'][$a];
			$nilai->subkriteria_id = $scores['subkriteria_id'][$a];
			$nilai->save();
		}
		return back()->withSuccess('Penilaian alternatif sudah ditambahkan');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Nilai  $nilai
	 * @return \Illuminate\Http\Response
	 */
	public function show()
	{
		$alt = Alternatif::get();
		$kr = Kriteria::get();
		$skr = SubKriteria::get();
		$hasil = Nilai::leftJoin('alternatif', 'alternatif.id', '=', 'nilai.alternatif_id')
			->leftJoin('kriteria', 'kriteria.id', '=', 'nilai.kriteria_id')
			->leftJoin('subkriteria', 'subkriteria.id', '=', 'nilai.subkriteria_id')->get();
		$data = [
			'alternatif' => $alt,
			'kriteria' => $kr,
			'subkriteria' => $skr,
		];
		return view('main.alternatif.hasil', compact('hasil', 'data'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Nilai  $nilai
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$success = false;
		$cek = Nilai::where('alternatif_id', '=', $id)->get();
		if (!$cek)
			return back()->with('error', 'Penilaian alternatif tidak ditemukan');
		$request->validate(Nilai::$updrules, Nilai::$message);
		$scores = $request->all();
		for ($a = 0; $a < count($scores['kriteria_id']); $a++) {
			try {
				$upd = Nilai::where('alternatif_id', '=', $id)
					->where('kriteria_id', '=', $scores['kriteria_id'][$a])
					->update(['subkriteria_id' => $scores['subkriteria_id'][$a]]);
				if ($upd) $success = true;
			} catch (QueryException $ex) {
				return back()->withError('Gagal update penilaian alternatif')
					->withErrors($ex->getMessage());
				break;
			}
		}
		if ($success)
			return back()->withSuccess('Penilaian alternatif sudah diupdate');
		return back()->withError('Gagal update penilaian alternatif');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Nilai  $nilai
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		try {
			$cek = Nilai::where('alternatif_id', '=', $id);
			if (!$cek)
				return back()->withError('Penilaian alternatif tidak ditemukan');
			$del = $cek->delete();
			if ($del) return back()->withSuccess('Penilaian alternatif sudah dihapus');
		} catch (QueryException $err) {
			return back()->withError('Gagal hapus penilaian alternatif')
				->withErrors($err->getMessage());
		}
	}
}
