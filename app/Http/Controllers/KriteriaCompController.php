<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\KriteriaComp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KriteriaCompController extends Controller
{
	// private function getAllKriteria()
	// {
	// 	$kriteria = DB::table("kriteria")->select("nama")->get();
	// 	return $kriteria;
	// }
	private function getKriteriaPerbandingan()
	{
		$kriteria_comp = DB::table('kriteria_banding')
			->join('kriteria','kriteria_banding.kriteria1','kriteria.id')
			->select('kriteria_banding.kriteria1 as idkriteria','kriteria.name')
			->groupBy('kriteria1','name')->get();
		return $kriteria_comp;
	}
	private function getPerbandinganByKriteria1($kriteria1)
	{
		$kriteria2 = DB::table("kriteria_banding")
		->select('nilai','kriteria2','kriteria1')
			->where("kriteria2", "=", $kriteria1)
			->get();
		return $kriteria2;
	}
	private function getNilaiPerbandingan($kode_kriteria)
	{
		$nilai_perbandingan = DB::table("kriteria_banding")
			->select("nilai", "kriteria1")
			->where("kriteria1", "=", $kode_kriteria)
			->get();
		return $nilai_perbandingan;
	}
	public function index()
	{
		$crit = Kriteria::get();
		$counter = 0;
		for ($a = 0; $a < count($crit); $a++) {
			for ($b = 0; $b < count($crit); $b++) {
				$array[$counter]["baris"] = $crit[$a]->name;
				$array[$counter]["kolom"] = $crit[$b]->name;
				$counter++;
			}
		}
		return view('main.kriteria-comp', compact('array'));
	}
	public function simpan(Request $request)
	{
		$request->validate(KriteriaComp::$rules, KriteriaComp::$message);
		$kriteria = DB::table("kriteria")->get();
		DB::table("kriteria_banding")->truncate();
		$a = 0;
		for ($i = 0; $i < count($kriteria); $i++) {
			for ($j = $i; $j < count($kriteria); $j++) {
				$perbandingan = new KriteriaComp();
				$perbandingan->kriteria1 = $kriteria[$i]->id;
				$perbandingan->kriteria2 = $kriteria[$j]->id;
				if ($request->kolom[$a] > $request->baris[$a]) {
					$nilai = 0 - $request->kolom[$a];
				} else {
					$nilai = $request->baris[$a];
				}
				$perbandingan->nilai = $nilai;
				$perbandingan->save();
				$a++;
			}
		}
		return redirect('/bobot/hasil');
	}
	public function hasil()
	{
		$kriteria = $this->getKriteriaPerbandingan();
		$a = 0;
		foreach ($kriteria as $k) {
			$kode_kriteria = $k->idkriteria;
			$perbandingan = $this->getPerbandinganByKriteria1($kode_kriteria);
			if ($perbandingan) {
				foreach ($perbandingan as $hk) {
					if ($hk->kriteria2 !== $hk->kriteria1) {
						if ($hk->nilai < 0) {
							$nilai = number_format(abs($hk->nilai / 1), 4);
							$nilai2 = abs($hk->nilai) . "/1";
						} else {
							$nilai = number_format(abs(1 / $hk->nilai), 4);
							$nilai2 = "1/" . abs($hk->nilai);
						}
						$matriks_perbandingan[$a] = [
							"nilai" => $nilai,
							"kode_kriteria" => $kode_kriteria,
						];
						$matriks_awal[$a] = [
							"nilai" => $nilai2,
							"kode_kriteria" => $kode_kriteria,
						];
						$a++;
					}
				}
				$nilaiPerbandingan = $this->getNilaiPerbandingan($kode_kriteria);
				foreach ($nilaiPerbandingan as $hb) {
					if ($hb->nilai < 0) {
						$nilai = number_format(abs(1 / $hb->nilai), 4);
						$nilai2 = "1/" . abs($hb->nilai);
					} elseif ($hb->nilai > 1) {
						$nilai = number_format(abs($hb->nilai / 1), 4);
						$nilai2 = abs($hb->nilai) . "/1";
					} else {
						$nilai = number_format(abs($hb->nilai), 4);
						$nilai2 = abs($hb->nilai) . "/1";
					}
					$matriks_perbandingan[$a] = [
						"nilai" => $nilai,
						"kode_kriteria" => $kode_kriteria,
					];
					$matriks_awal[$a] = [
						"nilai" => $nilai2,
						"kode_kriteria" => $kode_kriteria,
					];
					$a++;
				}
			}
		}
		$array_jumlah = null;
		for ($j = 0; $j < count($kriteria); $j++) {
			$jumlah = 0;
			for (
				$i = $j;
				$i < count($matriks_perbandingan);
				$i = $i + count($kriteria)
			) {
				$jumlah = $jumlah + $matriks_perbandingan[$i]["nilai"];
			}
			$array_jumlah[$j] = ["jumlah" => number_format(abs($jumlah), 4)];
		}
		$array_normalisasi = null;
		$a = 0;
		$array_filter = [];
		for ($i = 0; $i < count($kriteria); $i++) {
			for ($j = 0; $j < count($matriks_perbandingan); $j++) {
				if (
					$kriteria[$i]->idkriteria ==
					$matriks_perbandingan[$j]["kode_kriteria"]
				) {
					array_push(
						$array_filter,
						$matriks_perbandingan[$j]["nilai"]
					);
				}
			}
			for ($k = 0; $k < count($matriks_perbandingan); $k++) {
				for ($m = 0; $m < count($array_filter); $m++) {
					$kolom = $m;
					$hasil = 0;
					for ($l = 0; $l < count($array_filter); $l++) {
						// if (!isset($matriks_perbandingan[$kolom])) continue (1);
						$hasil +=
							$array_filter[$l] *
							$matriks_perbandingan[$kolom]["nilai"];
						$kolom += count($array_filter);
					}
					$array_normalisasi[$a] = [
						"nilai" => number_format(abs($hasil), 4),
						"kode_kriteria" => $kriteria[$i]->idkriteria,
					];
					$a++;
				}
				$array_filter = [];
			}
		}
		$total_jumlah_baris = 0;
		foreach ($array_normalisasi as $an) {
			$total_jumlah_baris = number_format(
				abs($total_jumlah_baris + $an["nilai"]),
				4
			);
		}
		$array_BobotPrioritas = null;
		$jumlah_baris = 0;
		$index_kriteria = 0;
		$j = 0;
		for ($i = 0; $i < count($array_normalisasi); $i++) {
			$jumlah_baris = $jumlah_baris + $array_normalisasi[$i]["nilai"];
			if ($index_kriteria == count($kriteria) - 1) {
				// if (!isset($kriteria[$j])) continue;
				$array_BobotPrioritas[$j] = [
					"jumlah_baris" => number_format(abs($jumlah_baris), 4),
					"bobot" => number_format(
						abs($jumlah_baris / $total_jumlah_baris),
						4
					),
					"kode_kriteria" => $kriteria[$j]->idkriteria,
				];
				$j++;
				$jumlah_baris = 0;
				$index_kriteria = 0;
			} else {
				$index_kriteria++;
			}
		}
		$array_CM = null;
		$cm = 0;
		$indexbobot = 0;
		$j = 0;
		for ($i = 0; $i < count($matriks_perbandingan); $i++) {
			// if (!isset($array_BobotPrioritas[$indexbobot]["bobot"])) continue;
			$cm = number_format(
				abs(
					$cm +
						$matriks_perbandingan[$i]["nilai"] *
						$array_BobotPrioritas[$indexbobot]["bobot"]
				),
				4
			);
			if ($indexbobot == count($kriteria) - 1) {
				$array_CM[$j] = [
					"cm" => number_format(
						abs($cm / $array_BobotPrioritas[$j]["bobot"]),
						4
					),
					"kode_kriteria" => $kriteria[$j]->idkriteria,
					"kali_matriks" => $cm,
				];
				$j++;
				$cm = 0;
				$indexbobot = 0;
			} else {
				$indexbobot++;
			}
		}
		$total_cm = 0;
		foreach ($array_CM as $cm) {
			$total_cm = $total_cm + $cm["cm"];
		}
		$average_cm = number_format(abs($total_cm / count($array_CM)), 4);
		$total_ci = number_format(
			abs(($average_cm - count($kriteria)) / (count($kriteria) - 1)),
			4
		);
		$ratio = [null, 0, 0, 0.58, 0.9, 1.12, 1.24, 1.32, 1.41, 1.45];
		$result = number_format(abs($total_ci / $ratio[count($kriteria) - 1]), 4);

		// for ($i = 0; $i < count($kriteria); $i++) {
		// 	Kriteria::where(
		// 		"kode_kriteria",
		// 		$kriteria[$i]->idkriteria
		// 	)->update([
		// 		"bobot" => $array_BobotPrioritas[$i]["bobot"],
		// 	]);
		// }

		$data = [
			"kriteria" => $kriteria,
			"matriks_perbandingan" => $matriks_perbandingan,
			"matriks_awal" => $matriks_awal,
			"average_cm" => $average_cm,
			"bobot_prioritas" => $array_BobotPrioritas,
			"matriks_normalisasi" => $array_normalisasi,
			"jumlah" => $array_jumlah,
			"cm" => $array_CM,
			"ci" => $total_ci,
			"result" => $result,
			"total_jumlah_baris" => $total_jumlah_baris,
		];
		return view('main.kriteria-hasil', compact('data'));
	}
	public function destroy()
	{
		DB::table('kriteria_banding')->delete();
		return redirect('/bobot');
	}
}
