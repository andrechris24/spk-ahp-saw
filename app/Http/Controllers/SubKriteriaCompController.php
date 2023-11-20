<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\SubKriteriaComp;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
				->withErrors("Kesalahan #$e->errorInfo[0]/$e->errorInfo[1]. " .
					$e->errorInfo[2]);
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
				->withErrors("Kesalahan #$e->errorInfo[0]/$e->errorInfo[1]. " .
					$e->errorInfo[2]);
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
				->withErrors("Kesalahan SQLState #" . $e->errorInfo[0]);
		}
	}
	public static function nama_kriteria($id)
	{
		try {
			$kriteria = Kriteria::firstWhere('id', $id);
			return $kriteria['name'];
		} catch (QueryException $e) {
			Log::error($e);
			return "E" . $e->errorInfo[0] . '/' . $e->errorInfo[1];
		}
	}
	public function index()
	{
		$allkrit = Kriteria::get();
		if ($allkrit->isEmpty()) {
			return redirect('/kriteria')->withWarning(
				'Masukkan kriteria dulu untuk melakukan perbandingan sub kriteria.'
			);
		}
		if (SubKriteria::count() === 0) {
			return redirect('/kriteria/sub')->withWarning(
				'Masukkan data sub kriteria dulu ' .
				'untuk melakukan perbandingan sub kriteria.'
			);
		}
		return view('main.subkriteria.select', compact('allkrit'));
	}
	public function create(Request $request)
	{
		try {
			$request->validate(
				SubKriteriaComp::$selectrules,
				SubKriteriaComp::$selectmessage
			);
			$idkriteria = $request->kriteria_id;
			Kriteria::findOrFail($idkriteria);
			$subkriteria = SubKriteria::where('kriteria_id', $idkriteria)->get();
			$jmlsubkriteria = count($subkriteria);
			$array = $value = [];
			$counter = 0;
			for ($a = 0; $a < $jmlsubkriteria; $a++) {
				for ($b = $a; $b < $jmlsubkriteria; $b++) {
					$array[$counter]["baris"] = $subkriteria[$a]->name;
					$array[$counter]["kolom"] = $subkriteria[$b]->name;
					$value[$counter] = SubKriteriaComp::select('nilai')
						->where('subkriteria1', $subkriteria[$a]->id)
						->where('subkriteria2', $subkriteria[$b]->id)->first();
					$counter++;
				}
			}
			$cek = SubKriteriaComp::where('idkriteria', $idkriteria)->count();
			return view(
				'main.subkriteria.comp',
				compact('array', 'cek', 'jmlsubkriteria', 'value')
			)->with(['kriteria_id' => $idkriteria]);
		} catch (ModelNotFoundException) {
			return redirect('/bobot/sub')
				->withErrors(['kriteria_id' => 'Kriteria tidak ditemukan']);
		}
	}
	public function store(Request $request, $kriteria_id)
	{
		$request->validate(SubKriteriaComp::$rules, SubKriteriaComp::$message);
		try {
			SubKriteriaComp::where('idkriteria', $kriteria_id)->delete();
			if (SubKriteriaComp::count() === 0)
				SubKriteriaComp::truncate();
			$subkriteria = SubKriteria::where('kriteria_id', $kriteria_id)->get();
			$a = 0;
			for ($i = 0; $i < count($subkriteria); $i++) {
				for ($j = $i; $j < count($subkriteria); $j++) {
					SubKriteriaComp::updateOrCreate(
						[
							'idkriteria' => $kriteria_id,
							'subkriteria1' => $subkriteria[$i]->id,
							'subkriteria2' => $subkriteria[$j]->id
						],
						['nilai' => $subkriteria[$i]->id === $subkriteria[$j]->id ? 1 : $request->skala[$a]]
					);
					$a++;
				}
			}
			return redirect('/bobot/sub/hasil/' . $kriteria_id);
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withError(
				'Gagal menyimpan nilai perbandingan sub kriteria ' .
				$this->nama_kriteria($kriteria_id)
			)->withErrors("Kesalahan SQLState #" . $e->errorInfo[0])
				->with(['kriteria_id' => $kriteria_id])->withInput();
		}
	}
	public function show($id)
	{
		$subkriteria = $this->getSubKriteriaPerbandingan($id);
		$a = 0;
		$matriks_perbandingan = $matriks_awal = [];
		foreach ($subkriteria as $k) {
			$kode_kriteria = $k->idsubkriteria;
			$perbandingan = $this->getPerbandinganBySubKriteria1($kode_kriteria, $id);
			if ($perbandingan) {
				foreach ($perbandingan as $hk) {
					if ($hk->subkriteria2 !== $hk->subkriteria1) {
						if ($hk->nilai < 0) {
							$nilai = round(abs($hk->nilai / 1), 5);
							$nilai2 = "<sup>" . abs($hk->nilai) . "</sup>/<sub>1</sub>";
						} else {
							$nilai = round(abs(1 / $hk->nilai), 5);
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
				$nilaiPerbandingan = $this->getNilaiPerbandingan($kode_kriteria, $id);
				foreach ($nilaiPerbandingan as $hb) {
					if ($hb->nilai < 0) {
						$nilai = round(abs(1 / $hb->nilai), 5);
						$nilai2 = "<sup>1</sup>/<sub>" . abs($hb->nilai) . "</sub>";
					} else {
						$nilai = round(abs(($hb->nilai > 1) ? $hb->nilai / 1 : $hb->nilai), 5);
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
			$array_jumlah[$j] = round(abs($jumlah), 5);
		}
		$a = 0;
		$array_normalisasi = $array_filter = [];
		for ($i = 0; $i < count($subkriteria); $i++) {
			for ($j = 0; $j < count($matriks_perbandingan); $j++) {
				if (
					$subkriteria[$i]->idsubkriteria ===
					$matriks_perbandingan[$j]["kode_kriteria"]
				)
					$array_filter[] = $matriks_perbandingan[$j]["nilai"];
			}
			for ($k = 0; $k < count($matriks_perbandingan); $k++) {
				for ($m = 0; $m < count($array_filter); $m++) {
					$array_normalisasi[$a] = [
						"nilai" => round(
							abs($matriks_perbandingan[$a]['nilai'] / $array_jumlah[$m]),
							5
						),
						"kode_kriteria" => $subkriteria[$i]->idsubkriteria
					];
					$a++;
				}
				$array_filter = [];
			}
		}
		$total_jumlah_baris = 0;
		foreach ($array_normalisasi as $an) {
			$total_jumlah_baris = round(abs($total_jumlah_baris + $an["nilai"]), 5);
		}
		$array_BobotPrioritas = [];
		$jumlah_baris = $index_kriteria = $j = 0;
		for ($i = 0; $i < count($array_normalisasi); $i++) {
			$jumlah_baris = $jumlah_baris + $array_normalisasi[$i]["nilai"];
			if ($index_kriteria === count($subkriteria) - 1) {
				$array_BobotPrioritas[$j] = [
					"jumlah_baris" => round(abs($jumlah_baris), 5),
					"bobot" => round(abs($jumlah_baris / $total_jumlah_baris), 5),
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
			$cm = round(
				abs(
					$cm +
					$matriks_perbandingan[$i]["nilai"] *
					$array_BobotPrioritas[$indexbobot]["bobot"]
				),
				5
			);
			if ($indexbobot === count($subkriteria) - 1) {
				$array_CM[$j] = [
					"cm" => round(abs($cm / $array_BobotPrioritas[$j]["bobot"]), 5),
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
		$average_cm = round(abs($total_cm / count($array_CM)), 5);
		$total_ci = round(
			abs(($average_cm - count($subkriteria)) / (count($subkriteria) - 1)),
			5
		);
		$ratio = Kriteria::$ratio_index[count($subkriteria)];
		$result = $ratio === 0 ? '-' : round(abs($total_ci / $ratio), 5);
		try {
			if ($result <= 0.1 || !is_numeric($result)) {
				for ($i = 0; $i < count($subkriteria); $i++) {
					SubKriteria::where("id", $subkriteria[$i]->idsubkriteria)
						->where('kriteria_id', $id)
						->update(["bobot" => $array_BobotPrioritas[$i]["bobot"]]);
				}
				$subbobotkosong = SubKriteria::where('bobot', 0.00000)->count();
			} else {
				SubKriteria::where('kriteria_id', $id)->update(['bobot' => 0.00000]);
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
			return view('main.subkriteria.hasil', compact('data'))
				->with(['kriteria_id' => $id]);
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withError('Gagal memuat hasil perbandingan sub kriteria ' .
				$this->nama_kriteria($id) . ':')
				->withErrors("Kesalahan SQLState #" . $e->errorInfo[0])->withInput()
				->with(['kriteria_id' => $id]);
		}
	}
	public function destroy($id)
	{
		try {
			$kr = Kriteria::firstWhere('id', $id);
			SubKriteriaComp::where('idkriteria', $id)->delete();
			SubKriteria::where('kriteria_id', $id)->update(['bobot' => 0.00000]);
			return redirect('/bobot/sub')
				->withSuccess("Perbandingan Sub kriteria $kr->name sudah direset")
				->with(['kriteria_id' => $id]);
		} catch (QueryException $e) {
			return back()
				->withError("Perbandingan Sub kriteria $kr->name gagal direset")
				->withErrors("Kesalahan SQLState #" . $e->errorInfo[0])
				->with(['kriteria_id' => $id]);
		}
	}
}