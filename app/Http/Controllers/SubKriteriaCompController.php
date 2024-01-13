<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\SubKriteriaComp;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SubKriteriaCompController extends Controller
{
	private function getSubKriteriaPerbandingan($id)
	{
		try {
			return SubKriteriaComp::join(
				"subkriteria",
				"subkriteria_banding.subkriteria1",
				"subkriteria.id"
			)->select(
					"subkriteria_banding.subkriteria1 as idsubkriteria",
					"subkriteria.name"
				)->groupBy("subkriteria1", 'name')->where('kriteria_id', $id)->get();
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withError('Gagal memuat hasil perbandingan:')
				->withErrors("Kesalahan SQLState #" . $e->errorInfo[1])
				->withErrors($e->errorInfo[2]);
		}
	}
	private function getPerbandinganBySubKriteria1($subkriteria1, $id)
	{
		try {
			return SubKriteriaComp::select('nilai', 'subkriteria2', 'subkriteria1')
				->where("subkriteria2", $subkriteria1)->where('idkriteria', $id)->get();
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withError('Gagal memuat hasil perbandingan:')
				->withErrors("Kesalahan SQLState #" . $e->errorInfo[1])
				->withErrors($e->errorInfo[2]);
		}
	}
	private function getNilaiPerbandingan($kode_kriteria, $id)
	{
		try {
			return SubKriteriaComp::select("nilai", "subkriteria1")
				->where("subkriteria1", $kode_kriteria)->where('idkriteria', $id)->get();
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withError('Gagal memuat hasil perbandingan:')
				->withErrors("Kesalahan SQLState #" . $e->errorInfo[1])
				->withErrors($e->errorInfo[2]);
		}
	}
	public function datatables()
	{
		return DataTables::eloquent(Kriteria::query())
			->addColumn('result', function (Kriteria $kriteria) {
				$jmlbanding = SubKriteriaComp::where('idkriteria', $kriteria->id)
					->count();
				$jmlsub = SubKriteria::where('kriteria_id', $kriteria->id)->count();
				return $jmlbanding > $jmlsub;
			})->toJson();
	}
	public function index()
	{
		$allkrit = Kriteria::get();
		if ($allkrit->isEmpty()) {
			return to_route('kriteria.index')->withWarning(
				'Masukkan kriteria dulu untuk melakukan perbandingan sub kriteria.'
			);
		}
		if (SubKriteria::count() === 0) {
			return to_route('subkriteria.index')->withWarning(
				'Masukkan data sub kriteria dulu ' .
				'untuk melakukan perbandingan sub kriteria.'
			);
		}
		return view('main.subkriteria.select', compact('allkrit'));
	}
	public function create($kriteria_id)
	{
		try {
			Kriteria::findOrFail($kriteria_id);
			$subkriteria = SubKriteria::where('kriteria_id', $kriteria_id)->get();
			$jmlsubkriteria = count($subkriteria);
			$array = $value = [];
			$counter = 0;
			for ($a = 0; $a < $jmlsubkriteria; $a++) {
				for ($b = $a; $b < $jmlsubkriteria; $b++) {
					$array[$counter]["baris"] = $subkriteria[$a]->name;
					$array[$counter]["kolom"] = $subkriteria[$b]->name;
					$value[$counter] = SubKriteriaComp::select('nilai')
						->where('subkriteria1', $subkriteria[$a]->id)
						->where('subkriteria2', $subkriteria[$b]->id)->firstOr(function () {
							return ['nilai' => 0]; //jika tidak ada
						});
					$counter++;
				}
			}
			return view(
				'main.subkriteria.comp',
				compact('array', 'jmlsubkriteria', 'value', 'kriteria_id')
			);
		} catch (ModelNotFoundException) {
			return back()->withError(
				'Gagal memuat form perbandingan sub kriteria: Kriteria tidak ditemukan'
			);
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withError(
				'Gagal memuat form perbandingan sub kriteria ' .
				SubKriteriaController::nama_kriteria($kriteria_id) . ':'
			)->withErrors("Kesalahan SQLState #" . $e->errorInfo[1])
				->withErrors($e->errorInfo[2]);
		}
	}
	public function store(Request $request, $kriteria_id)
	{
		$request->validate(SubKriteriaComp::$rules, SubKriteriaComp::$message);
		try {
			$subkriteria = SubKriteria::where('kriteria_id', $kriteria_id)->get();
			$a = 0;
			for ($i = 0; $i < count($subkriteria); $i++) {
				for ($j = $i; $j < count($subkriteria); $j++) {
					if ($subkriteria[$i]->id === $subkriteria[$j]->id)
						$nilai = 1;
					else {
						$nilai = $request->skala[$a];
						if ($request->subkriteria[$a] === "right")
							$nilai = 0 - $request->skala[$a];
					}
					SubKriteriaComp::updateOrCreate([
						'idkriteria' => $kriteria_id,
						'subkriteria1' => $subkriteria[$i]->id,
						'subkriteria2' => $subkriteria[$j]->id
					], ['nilai' => $nilai]);
					$a++;
				}
			}
			return to_route('bobotsubkriteria.result', $kriteria_id);
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withError(
				'Gagal menyimpan nilai perbandingan sub kriteria ' .
				SubKriteriaController::nama_kriteria($kriteria_id) . ':'
			)->withErrors("Kesalahan SQLState #" . $e->errorInfo[1])
				->withErrors($e->errorInfo[2])->withInput();
		}
	}
	public function show($kriteria_id)
	{
		$compsubcount = SubKriteriaComp::where('idkriteria', $kriteria_id)->count();
		$subcount = SubKriteria::where('kriteria_id', $kriteria_id)->count();
		if ($compsubcount <= $subcount) {
			return to_route('bobotsubkriteria.index', $kriteria_id)->withWarning(
				'Perbandingan sub kriteria ' .
				SubKriteriaController::nama_kriteria($kriteria_id) .
				' belum dilakukan atau tidak lengkap'
			);
		}
		$subkriteria = $this->getSubKriteriaPerbandingan($kriteria_id);
		$a = 0;
		$matriks_perbandingan = $matriks_awal = [];
		foreach ($subkriteria as $k) {
			$kode_kriteria = $k->idsubkriteria;
			$perbandingan = $this->getPerbandinganBySubKriteria1(
				$kode_kriteria,
				$kriteria_id
			);
			if ($perbandingan) {
				foreach ($perbandingan as $hk) {
					if ($hk->subkriteria2 !== $hk->subkriteria1) {
						if ($hk->nilai < 0) {
							$nilai = abs($hk->nilai / 1);
							$nilai2 = "<sup>" . abs($hk->nilai) . "</sup>/<sub>1</sub>";
						} else {
							$nilai = abs(1 / $hk->nilai);
							$nilai2 = "<sup>1</sup>/<sub>" . abs($hk->nilai) . "</sub>";
						}
						$matriks_perbandingan[$a] = [
							"nilai" => $nilai,
							"kode_kriteria" => $kode_kriteria
						];
						$matriks_awal[$a] = [
							"nilai" => $nilai2,
							"kode_kriteria" => $kode_kriteria
						];
						$a++;
					}
				}
				$nilaiPerbandingan = $this->getNilaiPerbandingan(
					$kode_kriteria,
					$kriteria_id
				);
				foreach ($nilaiPerbandingan as $hb) {
					if ($hb->nilai < 0) {
						$nilai = abs(1 / $hb->nilai);
						$nilai2 = "<sup>1</sup>/<sub>" . abs($hb->nilai) . "</sub>";
					} else {
						$nilai = abs(($hb->nilai > 1) ? $hb->nilai / 1 : $hb->nilai);
						$nilai2 = "<sup>" . abs($hb->nilai) . "</sup>/<sub>1</sub>";
					}
					$matriks_perbandingan[$a] = [
						"nilai" => $nilai,
						"kode_kriteria" => $kode_kriteria
					];
					$matriks_awal[$a] = [
						"nilai" => $nilai2,
						"kode_kriteria" => $kode_kriteria
					];
					$a++;
				}
			}
		}
		$array_jumlah = [];
		for ($j = 0; $j < count($subkriteria); $j++) {
			$jumlah = 0;
			for ($i = $j; $i < count($matriks_perbandingan); $i += count($subkriteria)) {
				$jumlah += $matriks_perbandingan[$i]["nilai"];
			}
			$array_jumlah[$j] = $jumlah;
		}
		$a = 0;
		$array_normalisasi = $array_filter = [];
		for ($i = 0; $i < count($subkriteria); $i++) {
			for ($j = 0; $j < count($matriks_perbandingan); $j++) {
				if ($subkriteria[$i]->idsubkriteria === $matriks_perbandingan[$j]["kode_kriteria"])
					$array_filter[] = $matriks_perbandingan[$j]["nilai"];
			}
			for ($k = 0; $k < count($matriks_perbandingan); $k++) {
				for ($m = 0; $m < count($array_filter); $m++) {
					$array_normalisasi[$a] = [
						"nilai" => $matriks_perbandingan[$a]['nilai'] / $array_jumlah[$m],
						"kode_kriteria" => $subkriteria[$i]->idsubkriteria
					];
					$a++;
				}
				$array_filter = [];
			}
		}
		$total_jumlah_baris = 0;
		foreach ($array_normalisasi as $an) {
			$total_jumlah_baris += $an["nilai"];
		}
		$array_BobotPrioritas = [];
		$jumlah_baris = $index_kriteria = $j = 0;
		for ($i = 0; $i < count($array_normalisasi); $i++) {
			$jumlah_baris += $array_normalisasi[$i]["nilai"];
			if ($index_kriteria === count($subkriteria) - 1) {
				$array_BobotPrioritas[$j] = [
					"jumlah_baris" => $jumlah_baris,
					"bobot" => $jumlah_baris / $total_jumlah_baris,
					"kode_kriteria" => $subkriteria[$j]->idsubkriteria
				];
				$j++;
				$jumlah_baris = $index_kriteria = 0;
			} else
				$index_kriteria++;
		}
		$array_CM = [];
		$cm = $indexbobot = $j = 0;
		for ($i = 0; $i < count($matriks_perbandingan); $i++) {
			$cm += ($matriks_perbandingan[$i]["nilai"] *
				$array_BobotPrioritas[$indexbobot]["bobot"]);
			if ($indexbobot === count($subkriteria) - 1) {
				$array_CM[$j] = [
					"cm" => $cm / $array_BobotPrioritas[$j]["bobot"],
					"kode_kriteria" => $subkriteria[$j]->idsubkriteria,
					"kali_matriks" => $cm
				];
				$j++;
				$cm = $indexbobot = 0;
			} else
				$indexbobot++;
		}
		$total_cm = 0;
		foreach ($array_CM as $cm) {
			$total_cm += $cm["cm"];
		}
		$average_cm = $total_cm / count($array_CM);
		$total_ci = ($average_cm - count($subkriteria)) / (count($subkriteria) - 1);
		$ratio = Kriteria::$ratio_index[count($subkriteria)];
		$result = $ratio === 0 ? '-' : $total_ci / $ratio;
		try {
			if ($result <= 0.1 || !is_numeric($result)) {
				for ($i = 0; $i < count($subkriteria); $i++) {
					SubKriteria::where("id", $subkriteria[$i]->idsubkriteria)
						->where('kriteria_id', $kriteria_id)
						->update(["bobot" => round($array_BobotPrioritas[$i]["bobot"], 5)]);
				}
				$subbobotkosong = SubKriteria::where('bobot', 0.00000)->count();
			} else {
				SubKriteria::where('kriteria_id', $kriteria_id)
					->update(['bobot' => 0.00000]);
				$subbobotkosong = -1;
			}
			$data = [
				"subkriteria" => $subkriteria,
				"matriks_perbandingan" => $matriks_perbandingan,
				"matriks_awal" => $matriks_awal,
				"average_cm" => $average_cm,
				"bobot_prioritas" => $array_BobotPrioritas,
				"matriks_normalisasi" => $array_normalisasi,
				"jumlah" => $array_jumlah,
				"cm" => $array_CM,
				"ci" => $total_ci,
				"result" => $result,
				"bobot_sub_kosong" => $subbobotkosong
			];
			return view('main.subkriteria.hasil', compact('data', 'kriteria_id'));
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withError(
				'Gagal memuat hasil perbandingan sub kriteria ' .
				SubKriteriaController::nama_kriteria($kriteria_id) . ':'
			)->withErrors("Kesalahan SQLState #" . $e->errorInfo[1])
				->withErrors($e->errorInfo[2])->withInput();
		}
	}
	public function destroy(Request $request, $kriteria_id)
	{
		try {
			$kr = Kriteria::find($kriteria_id);
			if (SubKriteriaComp::where('idkriteria', '<>', $kriteria_id)->count() === 0)
				SubKriteriaComp::truncate();
			else
				SubKriteriaComp::where('idkriteria', $kriteria_id)->delete();
			SubKriteria::where('kriteria_id', $kriteria_id)
				->update(['bobot' => 0.00000]);
			if ($request->ajax())
				return response()->json(['message' => "Direset"]);
			return to_route('bobotsubkriteria.pick')
				->withSuccess("Perbandingan Sub kriteria $kr->name sudah direset");
		} catch (QueryException $e) {
			if ($request->ajax())
				return response()->json(['message' => $e->errorInfo[2]], 500);
			return back()
				->withError("Perbandingan Sub kriteria $kr->name gagal direset")
				->withErrors("Kesalahan SQLState #" . $e->errorInfo[1])
				->withErrors($e->errorInfo[2]);
		}
	}
}