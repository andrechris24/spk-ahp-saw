<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Hasil;
use App\Models\Kriteria;
use App\Models\Nilai;
use App\Models\SubKriteria;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
			return round($skor, 5);
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
			$kueri = Kriteria::where('id', $idkriteria)->first();
			return $kueri->bobot ?? 0;
		} catch (QueryException $err) {
			Log::error($err);
			return 0;
		}
	}
	public function simpanHasil($alt_id, $jumlah): void
	{
		try {
			Hasil::updateOrInsert(['alternatif_id' => $alt_id], ['skor' => $jumlah]);
		} catch (QueryException $e) {
			Log::error($e);
			return;
		}
	}
	public function datatables(){
		$kriteria=$subkriteria=[];
		return DataTables::Eloquent(Nilai::query())->addColumn('subkriteria',function(Nilai $nilai){
			$nilaialt = Nilai::leftJoin(
				'alternatif',
				'alternatif.id',
				'nilai.alternatif_id'
			)->leftJoin('kriteria', 'kriteria.id', 'nilai.kriteria_id')
				->leftJoin('subkriteria', 'subkriteria.id', 'nilai.subkriteria_id')
				->where('alternatif_id',$nilai->alternatif_id)->get();
			if(count($nilaialt)>0){
				foreach ($nilaialt as $key => $skor) {
					$subkriteria[$key]=$skor->subkriteria->name;
				}
				return $subkriteria;
			}
		})->toJson();
	}
	public function index()
	{
		$kriteria = Kriteria::get();
		if (count($kriteria) === 0) {
			return redirect('kriteria')->withWarning(
				'Tambahkan kriteria dan sub kriteria dulu ' .
				'sebelum melakukan penilaian alternatif.'
			);
		}
		$subkriteria = SubKriteria::get();
		if (count($subkriteria) === 0) {
			return redirect('kriteria/sub')
				->withWarning(
					'Tambahkan sub kriteria dulu sebelum melakukan penilaian alternatif.'
				);
		}
		$alternatif = Alternatif::get();
		if (count($alternatif) === 0) {
			return redirect('alternatif')
				->withWarning('Tambahkan alternatif dulu sebelum melakukan penilaian.');
		}
		$nilaialt = Nilai::leftJoin(
			'alternatif',
			'alternatif.id',
			'nilai.alternatif_id'
		)->leftJoin('kriteria', 'kriteria.id', 'nilai.kriteria_id')
			->leftJoin('subkriteria', 'subkriteria.id', 'nilai.subkriteria_id')
			->get();
		$data = [
			'kriteria' => $kriteria,
			'subkriteria' => $subkriteria,
			'alternatif' => $alternatif,
			'nilai' => $nilaialt
		];
		return view('main.alternatif.nilai', compact('data'));
	}
	public function store(Request $request)
	{
		$request->validate(Nilai::$rules, Nilai::$message);
		$scores = $request->all();
		try {
			$cek = Nilai::where('alternatif_id', $scores['alternatif_id'])->exists();
			if ($cek) {
				return response()->json([
					'message' => 'Alternatif sudah digunakan dalam penilaian'
				], 422);
			}
			for ($a = 0; $a < count($scores['kriteria_id']); $a++) {
				$nilai[] = Nilai::create([
					'alternatif_id' => $scores['alternatif_id'],
					'kriteria_id' => $scores['kriteria_id'][$a],
					'subkriteria_id' => $scores['subkriteria_id'][$a]
				]);
				$hasil[$a + 1] = $nilai[$a]->subkriteria->name;
				$datas[]['subkriteria'] = $scores['subkriteria_id'][$a];
				$datas[]['kriteria'] = $scores['subkriteria_id'][$a];
			}
			$hasil[0] = $nilai[0]->alternatif->name;
			$hasil[count($scores['kriteria_id']) + 1] =
				'<div class="btn-group" role="button">
				<button type="button" class="btn btn-primary edit-record"
					data-bs-toggle="modal" data-bs-target="#NilaiAlterModal"
					data-bs-name="' . $scores['alternatif_id'] . '" title="Edit"
					data-bs-score="' . json_encode($datas) . '">
					<i class="bi bi-pencil-square"></i>
				</button>
				<button type="button" class="btn btn-danger delete-record"
					data-bs-id="' . $scores['alternatif_id'] . '" title="Hapus"
					data-bs-name="' . $hasil[0] . '">
					<i class="bi bi-trash3-fill"></i>
				</button>
			</div>';
			$hasil['message'] = 'Penilaian alternatif sudah ditambahkan';
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
				return redirect('bobot')->withWarning(
					'Lakukan perbandingan kriteria secara konsisten ' .
					'sebelum melihat hasil penilaian alternatif.'
				);
			}
			if ($cekbobotskr > 0) {
				return redirect('bobot/sub')->withWarning(
					'Satu atau lebih perbandingan sub kriteria ' .
					'belum dilakukan secara konsisten.'
				);
			}
			if ($hasil->isEmpty()) {
				return redirect('alternatif/nilai')
					->withWarning('Masukkan data penilaian alternatif dulu');
			}
			$data = ['alternatif' => $alt, 'kriteria' => $kr, 'subkriteria' => $skr];
			return view('main.alternatif.hasil', compact('hasil', 'data'));
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withError('Gagal memuat hasil penilaian:')
				->withErrors($e->errorInfo[2]);
		}
	}
	public function edit($alt_id){
		try {
			$data=Nilai::where('alternatif_id',$alt_id)->firstOrFail();
			return response()->json($kriteria);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(["message" => $e->errorInfo[2]], 500);
		} catch (ModelNotFoundException $err) {
			return response()->json([
				'message' => 'Data Penilaian Alternatif tidak ditemukan',
				'exception' => $err->getMessage()
			], 404);
		}
	}
	public function update(Request $request)
	{
		try {
			$request->validate(Nilai::$rules, Nilai::$message);
			$scores = $request->all();
			for ($a = 0; $a < count($scores['kriteria_id']); $a++) {
				$Nilai[] = Nilai::updateOrCreate(
					[
						'alternatif_id' => $scores['alternatif_id'],
						'kriteria_id' => $scores['kriteria_id'][$a]
					],
					['subkriteria_id' => $scores['subkriteria_id'][$a]]
				);
				$hasil[$a + 1] = $Nilai[$a]->subkriteria->name;
				$datas[]['subkriteria'] = $scores['subkriteria_id'][$a];
				$datas[]['kriteria'] = $scores['subkriteria_id'][$a];
			}
			$hasil[0] = $Nilai[0]->alternatif->name;
			$hasil[count($scores['kriteria_id']) + 1] =
				'<div class="btn-group" role="button">
				<button type="button" class="btn btn-primary edit-record"
					data-bs-toggle="modal" data-bs-target="#NilaiAlterModal"
					data-bs-name="' . $scores["alternatif_id"] . '" title="Edit"
					data-bs-score="' . json_encode($datas) . '">
					<i class="bi bi-pencil-square"></i>
				</button>
				<button type="button" class="btn btn-danger delete-record"
					data-bs-id="' . $scores["alternatif_id"] . '" title="Hapus"
					data-bs-name="' . $hasil[0] . '">
					<i class="bi bi-trash3-fill"></i>
				</button>
			</div>';
			$hasil['message'] = "Nilai Alternatif sudah diupdate.";
			return response()->json($hasil);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	public function destroy($id)
	{
		try {
			$cek = Nilai::where('alternatif_id', $id);
			if (!$cek) {
				return response()->json([
					'message' => 'Penilaian alternatif tidak ditemukan'
				], 404);
			}
			$cek->delete();
			if (Nilai::count() === 0)
				Nilai::truncate();
			return response()->json(['message' => 'Penilaian alternatif sudah dihapus']);
		} catch (QueryException $err) {
			Log::error($err);
			return response()->json(['message' => $err->errorInfo[2]], 500);
		}
	}
	public function hasil()
	{
		try {
			$result = Hasil::get();
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