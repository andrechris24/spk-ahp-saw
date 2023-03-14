<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\Alternatif;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
	private function getAlternatif()
	{
		$alternatif = DB::table("hasil")
			->join("nilai", "hasil.id_nilai", "nilai.id")
			->join("alternatif", "nilai.alternatif_id", "alternatif.id")
			->select("alternatif.name", "alternatif.id")
			->groupBy("nilai.alternatif_id")
			->orderBy("alternatif.id")
			->get();
		return $alternatif;
	}

	private function getKriteria()
	{
		$kriteria = DB::table("hasil")
			->join("nilai", "hasil.id_nilai", "penilaian.kode_hasil")
			->join(
				"kriteria",
				"nilai.kriteria_id",
				"kriteria.id"
			)
			->select(
				"kriteria.name",
				"kriteria.bobot",
				"kriteria.id"
			)
			->groupBy("nilai.kriteria_id")
			->get();
		return $kriteria;
	}
	public function getNilaiAwal()
	{
		$nilai_awal = Hasil::join(
			"nilai",
			"hasil.id_nilai",
			"=",
			"nilai.id"
		)
			->groupBy("penilaian.kode_penilaian")
			->get(["nilai.*"]);

		return $nilai_awal;
	}

	public function getNilaiAkhir()
	{
		$hasil_akhir = Hasil::join(
			"penilaian",
			"hasil.kode_hasil",
			"penilaian.kode_hasil"
		)
			->join("guru", "penilaian.kode_guru", "guru.kode_guru")
			->groupBy("penilaian.kode_hasil")
			->get([
				"guru.nama",
				"guru.kode_guru",
				"hasil.nilai_saw",
				"hasil.keterangan",
				"hasil.kode_hasil",
			])
			->toArray();

		foreach ($hasil_akhir as $key => $isi) {
			$nama[$key] = $isi["nama"];
			$keterangan[$key] = $isi["keterangan"];
			$kode_hasil[$key] = $isi["kode_hasil"];
			$nilai_saw[$key] = $isi["nilai_saw"];
		}

		$nama = array_column($hasil_akhir, "nama");
		$keterangan = array_column($hasil_akhir, "keterangan");
		$kode_hasil = array_column($hasil_akhir, "kode_hasil");
		$nilai_saw = array_column($hasil_akhir, "nilai_saw");

		array_multisort($nilai_saw, SORT_DESC, $hasil_akhir);

		// $hasil_akhir = DB::table("hasil")
		//     ->join("penilaian", "hasil.kode_hasil", "penilaian.kode_hasil")
		//     ->join("guru", "penilaian.kode_guru", "guru.kode_guru")
		//     ->select(
		//         "guru.nama",
		//         "hasil.nilai_saw",
		//         "hasil.keterangan",
		//         "hasil.kode_hasil"
		//     )
		//     ->where("periode", $periode)
		//     ->groupBy("penilaian.kode_hasil")
		//     ->get();

		return $hasil_akhir;
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
		$nilaialt = Nilai::leftJoin('alternatif','alternatif.id','=','nilai.alternatif_id')
		->leftJoin('kriteria','kriteria.id','=','nilai.kriteria_id')
		->leftJoin('subkriteria','subkriteria.id','=','nilai.subkriteria_id')->get();
		return view(
			'main.alternatif.nilai',
			compact('kriteria', 'subkriteria', 'alternatif', 'nilaialt')
		);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
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
		$cek=DB::table('nilai')
		->where('alternatif_id','=',$scores['alternatif_id'])->count();
		if($cek>0)
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
		$hasil = Nilai::leftJoin('alternatif','alternatif.id','=','nilai.alternatif_id')
		->leftJoin('kriteria','kriteria.id','=','nilai.kriteria_id')
		->leftJoin('subkriteria','subkriteria.id','=','nilai.subkriteria_id')->get();
		$hasils = Nilai::leftJoin('alternatif','alternatif.id','=','nilai.alternatif_id')
			->leftJoin('kriteria','kriteria.id','=','nilai.kriteria_id')
			->leftJoin('subkriteria','subkriteria.id','=','nilai.subkriteria_id')->get();
		foreach($alt as $alts){
			// $afilter=$hasil->where('alternatif.id','=',$alts->id)->values()->all();
			$arr=array();
			foreach($hasil as $skor){
				if($alts->id==$skor->alternatif_id){
					echo $alts->name;
				}
				// Get all rating value for each criteria
				// $rates = $hasils->map(function($val) use ($cw){
				// 	if($cw->id == $val->id ){
				// 		return $val->bobot;
				// 	}
				// })->toArray();
				// print_r($rates);
				// array_filter for removing null value caused by map,
				// array_values for reiindex the array
				// $rates = array_values(array_filter($rates));
				// if ($cw->type == 'benefit') {
				// 	$result = $afilter[$icw]->bobot / max($rates);
				// 	$msg = 'rate ' . $afilter[$icw]->bobot . ' max ' . max($rates) . ' res ' . $result;
				// } elseif ($cw->type == 'cost') {
				// 	$result = min($rates) / $afilter[$icw]->bobot;
				// }
				// $afilter[$icw]->bobot = round($result, 2);
			}
			die();
		}
		return view('main.alternatif.hasil', compact('skr','kr', 'alt', 'hasil'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Nilai  $nilai
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Nilai $nilai)
	{
		//
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
		$success=false;
		$cek = DB::table('nilai')->where('alternatif_id', '=', $id)->get();
		if (!$cek)
			return back()->with('error', 'Penilaian alternatif tidak ditemukan');
		$request->validate(Nilai::$updrules, Nilai::$message);
		$scores = $request->all();
		for ($a = 0; $a < count($scores['kriteria_id']); $a++) {
			try {
				$upd=DB::table('nilai')->where('alternatif_id', '=', $id)
					->where('kriteria_id', '=', $scores['kriteria_id'][$a])
					->update(['subkriteria_id' => $scores['subkriteria_id'][$a]]);
				if($upd) $success=true;
			} catch (QueryException $ex) {
				return back()->withError('Gagal update penilaian alternatif')
				->withErrors($ex->getMessage());
				break;
			}
		}
		if($success)
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
		try{
			$cek=Nilai::where('alternatif_id','=',$id);
			if(!$cek)
				return back()->withError('Penilaian alternatif tidak ditemukan');
			$del=$cek->delete();
			if($del) return back()->withSuccess('Penilaian alternatif sudah dihapus');
		}catch(QueryException $err){
			return back()->withError('Gagal hapus penilaian alternatif')
				->withErrors($err->getMessage());
		}
	}
}
