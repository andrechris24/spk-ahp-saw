<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Hasil;
use App\Models\Kriteria;
use App\Models\Nilai;
use App\Models\SubKriteria;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class NilaiController extends Controller
{
	public function normalisasi($arr, $type, $skor)
	{
		if ($type === 'cost')
			$hasil = min($arr) / $skor;
		else if ($type === 'benefit')
			$hasil = $skor / max($arr);
		else
			return round($skor, 5); //jika tipe salah
		return round($hasil, 5);
	}
	public function getNilaiArr($kriteria_id): array
	{
		$data = array();
		$kueri = Nilai::select('subkriteria.bobot as bobot')
			->join("subkriteria", "nilai.subkriteria_id", "subkriteria.id")
			->where('nilai.kriteria_id', $kriteria_id)->get();
		foreach ($kueri as $row) {
			$data[] = $row->bobot;
		}
		return $data;
	}
	public function getBobot($idkriteria)
	{
		try {
			$kueri = Kriteria::find($idkriteria);
			return $kueri->bobot ?? 0;
		} catch (QueryException $err) {
			Log::error($err);
			return 0;
		}
	}
	public function simpanHasil($alt_id, $jumlah): void
	{
		try {
			Hasil::updateOrCreate(['alternatif_id' => $alt_id], ['skor' => $jumlah]);
		} catch (QueryException $e) {
			Log::error($e);
			return;
		}
	}
	public function datatables()
	{
		return DataTables::Eloquent(Alternatif::query())
			->addColumn('subkriteria', function (Alternatif $alt) {
				$kriteria = Kriteria::get();
				foreach ($kriteria as $kr) {
					$subkriteria[Str::slug($kr->name, '-')] = '';
				}
				$nilaialt = Nilai::select(
					'nilai.*',
					'alternatif.name',
					'kriteria.name',
					'subkriteria.name'
				)->leftJoin(
						'alternatif',
						'alternatif.id',
						'nilai.alternatif_id'
					)->leftJoin('kriteria', 'kriteria.id', 'nilai.kriteria_id')
					->leftJoin('subkriteria', 'subkriteria.id', 'nilai.subkriteria_id')
					->where('alternatif_id', $alt->id)->get();
				if (count($nilaialt) > 0) {
					foreach ($nilaialt as $skor) {
						$subkriteria[Str::slug($skor->kriteria->name, '-')] =
							$skor->subkriteria->name;
					}
					return $subkriteria;
				}
			})->toJson();
	}
	public function getCount()
	{
		$alternatives = Alternatif::count();
		$dinilai = Nilai::join('alternatif', 'nilai.alternatif_id', 'alternatif.id')
			->select('nilai.alternatif_id as idalt', 'alternatif.name')
			->groupBy('idalt', 'name')->get()->count();
		return response()->json(['unused' => $alternatives - $dinilai]);
	}
	public function index()
	{
		$kriteria = Kriteria::get();
		if ($kriteria->isEmpty()) {
			return to_route('kriteria.index')->withWarning(
				'Tambahkan kriteria dan sub kriteria dulu ' .
				'sebelum melakukan penilaian alternatif.'
			);
		}
		$subkriteria = SubKriteria::get();
		if ($subkriteria->isEmpty()) {
			return to_route('subkriteria.index')->withWarning(
				'Tambahkan sub kriteria dulu sebelum melakukan penilaian alternatif.'
			);
		}
		$alternatif = Alternatif::get();
		if ($alternatif->isEmpty()) {
			return to_route('alternatif.index')
				->withWarning('Tambahkan alternatif dulu sebelum melakukan penilaian.');
		}
		$data = [
			'kriteria' => $kriteria,
			'subkriteria' => $subkriteria,
			'alternatif' => $alternatif
		];
		return view('main.alternatif.nilai', compact('data'));
	}
	public function store(Request $request)
	{
		$request->validate(Nilai::$rules, Nilai::$message);
		$scores = $request->all();
		try {
			$cek = Nilai::where('alternatif_id', $scores['alternatif_id'])->count();
			$jmlkr = Kriteria::count();
			if ($cek >= $jmlkr) {
				return response()->json([
					'message' => 'Alternatif sudah digunakan dalam penilaian',
					'errors' => [
						'alternatif_id' => 'Alternatif sudah digunakan'
					]
				], 422);
			}
			for ($a = 0; $a < count($scores['kriteria_id']); $a++) {
				Nilai::updateOrCreate([
					'alternatif_id' => $scores['alternatif_id'],
					'kriteria_id' => $scores['kriteria_id'][$a]
				], [
					'subkriteria_id' => $scores['subkriteria_id'][$a]
				]);
			}
			$hasil['message'] = 'Nilai Alternatif sudah diinput';
			return response()->json($hasil);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	public function show()
	{
		try {
			$alt = Alternatif::get();
			$kr = Kriteria::get();
			$skr = SubKriteria::get();
			$hasil = Nilai::leftJoin(
				'alternatif',
				'alternatif.id',
				'nilai.alternatif_id'
			)->leftJoin('kriteria', 'kriteria.id', 'nilai.kriteria_id')
				->leftJoin('subkriteria', 'subkriteria.id', 'nilai.subkriteria_id')
				->get();
			$cekbobotkr = Kriteria::where('bobot', 0.00000)->count();
			$cekbobotskr = SubKriteria::where('bobot', 0.00000)->count();
			if ($cekbobotkr > 0) {
				return to_route('bobotkriteria.index')->withWarning(
					'Lakukan perbandingan kriteria secara konsisten ' .
					'sebelum melihat hasil penilaian alternatif.'
				);
			}
			if ($cekbobotskr > 0) {
				return to_route('bobotsubkriteria.pick')->withWarning(
					'Satu atau lebih perbandingan sub kriteria ' .
					'belum dilakukan secara konsisten.'
				);
			}
			if ($hasil->isEmpty()) {
				return to_route('nilai.index')
					->withWarning('Masukkan data penilaian alternatif dulu');
			}
			$data = ['alternatif' => $alt, 'kriteria' => $kr, 'subkriteria' => $skr];
			return view('main.alternatif.hasil', compact('hasil', 'data'));
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withError('Gagal memuat hasil penilaian:')
				->withErrors("Kesalahan SQLState #" . $e->errorInfo[0]);
		}
	}
	public function edit($id)
	{
		try {
			$nilai = Nilai::where('alternatif_id', $id)->get();
			if ($nilai->isEmpty()) {
				return response()->json([
					'message' => 'Nilai Alternatif tidak ditemukan atau belum diisi.'
				], 404);
			}
			$data['alternatif_id'] = $id;
			foreach ($nilai as $skor) {
				$data['subkriteria'][Str::slug($skor->kriteria->name, '_')] =
					$skor->subkriteria_id;
			}
			return response()->json($data);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(["message" => $e->errorInfo[2]], 500);
		}
	}
	public function update(Request $request)
	{
		try {
			$request->validate(Nilai::$rules, Nilai::$message);
			$scores = $request->all();
			for ($a = 0; $a < count($scores['kriteria_id']); $a++) {
				Nilai::updateOrCreate([
					'alternatif_id' => $scores['alternatif_id'],
					'kriteria_id' => $scores['kriteria_id'][$a]
				], [
					'subkriteria_id' => $scores['subkriteria_id'][$a]
				]);
			}
			return response()->json(['message' => "Nilai Alternatif sudah diupdate"]);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	public function destroy($id)
	{
		try {
			$cek = Nilai::where('alternatif_id', $id);
			if (!$cek->exists()) {
				return response()->json([
					'message' => 'Nilai Alternatif tidak ditemukan.'
				], 404);
			}
			$cek->delete();
			Hasil::where('alternatif_id', $id)->delete();
			if (Nilai::count() === 0)
				Nilai::truncate();
			if (Hasil::count() === 0)
				Hasil::truncate();
			return response()->json(['message' => 'Nilai Alternatif sudah dihapus']);
		} catch (QueryException $err) {
			Log::error($err);
			return response()->json(['message' => $err->errorInfo[2]], 500);
		}
	}
	public function hasil()
	{
		try {
			$result = Hasil::get();
			if ($result->isEmpty())
				return response()->json(['message' => 'Ranking penilaian kosong'], 400);
			foreach ($result as $index => $hasil) {
				$data['alternatif'][$index] = $hasil->alternatif_id;
				$data['skor'][$index] = $hasil->skor;
			}
			$highest = Hasil::orderBy('skor', 'desc')->first();
			return response()->json([
				'result' => $data,
				'score' => $highest->skor,
				'nama' => $highest->alternatif->name
			]);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(["message" => $e->errorInfo[2]], 500);
		}
	}
}