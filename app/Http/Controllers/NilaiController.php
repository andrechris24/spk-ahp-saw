<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Hasil;
use App\Models\Kriteria;
use App\Models\Nilai;
use App\Models\SubKriteria;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class NilaiController extends Controller
{
	public function idxDataTables(Request $request)
	{
		$nilaialternatif=Nilai::query()->leftJoin(
			'alternatif',
			'alternatif.id',
			'=',
			'nilai.alternatif_id'
		)->leftJoin('kriteria', 'kriteria.id', '=', 'nilai.kriteria_id')
			->leftJoin('subkriteria', 'subkriteria.id', '=', 'nilai.subkriteria_id');
			return DataTables::eloquent($nilaialternatif)
				->editColumn('subkriteria_id', function (Nilai $skr) {
					return $skr->subkriteria->name;
				})->setRowId('alternatif_id')->toJson();
	}

	public function normalisasi($arr, $type, $skor): float|string
	{
		if ($type === 'cost')
			$hasil = min($arr) / $skor;
		else if ($type === 'benefit')
			$hasil = $skor / max($arr);
		else {
			return $skor;
		}
		return round($hasil, 4);
	}
	public function getNilaiArr($kriteria_id): array
	{
		$data = array();
		$kueri = Nilai::select('subkriteria.bobot as bobot')
			->join("subkriteria", "nilai.subkriteria_id", '=', "subkriteria.id")
			->where('nilai.kriteria_id', '=', $kriteria_id)
			->get();
		foreach ($kueri as $row) {
			$data[] = $row->bobot;
		}
		return $data;
	}
	public function getBobot($idkriteria)
	{
		try {
			$kueri = Kriteria::findOrFail($idkriteria)->first();
			return $kueri->bobot;
		} catch (ModelNotFoundException | QueryException) {
			return 0;
		}
	}
	public function simpanHasil($alt_id, $jumlah): void
	{
		try {
			Hasil::updateOrInsert(['alternatif_id' => $alt_id], ['skor' => $jumlah]);
		} catch (QueryException) {
			return;
		}
	}
	public function index()
	{
		$kriteria = Kriteria::get();
		if (count($kriteria) === 0) {
			return redirect('kriteria')->withWarning(
				'Tambahkan kriteria dan subkriteria dulu ' .
				'sebelum melakukan penilaian alternatif'
			);
		}
		$subkriteria = SubKriteria::get();
		if (count($subkriteria) === 0) {
			return redirect('kriteria/sub')
				->withWarning(
					'Tambahkan sub kriteria dulu sebelum melakukan penilaian alternatif'
				);
		}
		$alternatif = Alternatif::get();
		if (count($alternatif) === 0) {
			return redirect('alternatif')
				->withWarning('Tambahkan alternatif dulu sebelum melakukan penilaian');
		}
		$nilaialt = Nilai::leftJoin(
			'alternatif',
			'alternatif.id',
			'=',
			'nilai.alternatif_id'
		)->leftJoin('kriteria', 'kriteria.id', '=', 'nilai.kriteria_id')
			->leftJoin('subkriteria', 'subkriteria.id', '=', 'nilai.subkriteria_id')
			->get();
		return view(
			'main.alternatif.nilai',
			compact('kriteria', 'subkriteria', 'alternatif', 'nilaialt')
		);
	}
	public function store(Request $request)
	{
		$request->validate(Nilai::$rules, Nilai::$message);
		$scores = $request->all();
		try {
			$cek = Nilai::where('alternatif_id', '=', $scores['alternatif_id'])
			->exists();
			if ($cek){
				return response()->json([
					'message'=>'Alternatif sudah digunakan dalam penilaian'
				],422);
			}
			for ($a = 0; $a < count($scores['kriteria_id']); $a++) {
				$nilai = new Nilai();
				$nilai->alternatif_id = $scores['alternatif_id'];
				$nilai->kriteria_id = $scores['kriteria_id'][$a];
				$nilai->subkriteria_id = $scores['subkriteria_id'][$a];
				$nilai->save();
			}
			return response()->json(['message'=>'Penilaian alternatif sudah ditambahkan']);
		} catch (QueryException $e) {
			return response()->json(['message'=>$e->getMessage()],500);
		}
	}

	public function show()
	{
		$alt = Alternatif::get();
		$kr = Kriteria::get();
		$skr = SubKriteria::get();
		$hasil = Nilai::leftJoin(
			'alternatif',
			'alternatif.id',
			'=',
			'nilai.alternatif_id'
		)->leftJoin('kriteria', 'kriteria.id', '=', 'nilai.kriteria_id')
			->leftJoin('subkriteria', 'subkriteria.id', '=', 'nilai.subkriteria_id')
			->get();
		$cekbobotkr = Kriteria::where('bobot', 0.0000)->count();
		$cekbobotskr = SubKriteria::where('bobot', 0.0000)->count();
		if ($cekbobotkr > 0) {
			return redirect('bobot')->withWarning(
				'Lakukan perbandingan kriteria secara konsisten ' .
				'sebelum melihat hasil penilaian alternatif.'
			);
		}
		if ($cekbobotskr > 0) {
			return redirect('bobot/sub')->withWarning(
				'Satu atau lebih perbandingan sub kriteria ' .
				'belum dilakukan secara konsisten'
			);
		}
		if ($hasil->isEmpty()) {
			return redirect('alternatif/nilai')->withWarning(
				'Masukkan data penilaian alternatif dulu'
			);
		}
		$data = [
			'alternatif' => $alt,
			'kriteria' => $kr,
			'subkriteria' => $skr,
		];
		return view('main.alternatif.hasil', compact('hasil', 'data'));
	}

	public function update(Request $request)
	{
		try {
			$request->validate(Nilai::$updrules, Nilai::$message);
			$scores = $request->all();
			for ($a = 0; $a < count($scores['kriteria_id']); $a++) {
				$Nilai[]=Nilai::updateOrCreate(
					['alternatif_id' => $scores['alternatif_id'], 'kriteria_id' => $scores['kriteria_id'][$a]],
					['subkriteria_id' => $scores['subkriteria_id'][$a]]
				);
			}
			// for($b=0;$b<count($scores['kriteria_id']);$b++){
			// 	$scores['subkriteria_id'][$b]=(int)$scores['subkriteria_id'][$b];
			// 	$scores['subkriteria_id'][$b]=$request->input('subkriteria.'.$b)->name;
			// }
			$scores['message']="Nilai Alternatif sudah diupdate";
			return response()->json($scores);
		} catch (QueryException $e) {
			return response()->json(['message'=>$e->getMessage()],500);
		}
	}

	public function destroy($id)
	{
		try {
			$cek = Nilai::where('alternatif_id', '=', $id);
			if (!$cek)
				return response()->json(['message'=>'Penilaian alternatif tidak ditemukan'],404);
			$cek->delete();
			return response()->json(['message'=>'Penilaian alternatif sudah dihapus']);
		} catch (QueryException $err) {
			return response()->json(['message'=>$err->getMessage()],500);
		}
	}
}