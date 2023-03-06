<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\KriteriaComp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KriteriaCompController extends Controller
{
	private function getKriteriaComp(){
		$kriteria=DB::table('kriteria_banding')->get();
		return $kriteria;
	}
	private function getPerbandinganByKriteria1($kriteria1)
	{
		$kriteria2 = DB::table("kriteria_banding")
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
		$total = DB::table('kriteria')->count();
		return view('main.kriteria-comp', compact('total', 'crit'));
	}
	public function simpan(Request $request)
	{
		$request->validate(KriteriaComp::$rules,KriteriaComp::$message);
		$kriteria=DB::table('kriteria')->get();
		DB::table('kriteria_banding')->truncate();
		$counter=0;
		for($a=0;$a<count($kriteria)-1;$a++){
			for($b=0;$b<count($kriteria);$b++){
				$perbandingan=new KriteriaComp();
				$perbandingan->kriteria1=$kriteria[$a]->id;
				$perbandingan->kriteria2=$kriteria[$b]->id;
				if($request->pilihan[$counter]==2){
					$nilai=0-$request->bobot[$counter];
				}else $nilai=$request->bobot[$counter];
				$perbandingan->nilai=$nilai;
				$perbandingan->save();
				$counter++;
			}
		}
		return redirect('/bobot/hasil');
	}
	public function hasil()
	{
		$kriteria = $this->getKriteriaComp();
		$counter = 0;
		foreach ($kriteria as $k) {
			$kode_kriteria = $k->kriteria1;
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
						$matriks_perbandingan[$counter] = [
							"nilai" => $nilai,
							"kode_kriteria" => $kode_kriteria,
						];
						$matriks_awal[$counter] = [
							"nilai" => $nilai2,
							"kode_kriteria" => $kode_kriteria,
						];
						$counter++;
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

					$matriks_perbandingan[$counter] = [
						"nilai" => $nilai,
						"kode_kriteria" => $kode_kriteria,
					];
					$matriks_awal[$counter] = [
						"nilai" => $nilai2,
						"kode_kriteria" => $kode_kriteria,
					];
					$counter++;
				}
			}
			// unset($matriks_perbandingan[$counter][$counter]);
			// $matriks_perbandingan[$a] =  array_values($matriks_perbandingan[$counter]);
		}
		$array_jumlah = null;
		for ($b = 0; $b < count($kriteria); $b++) {
			$jumlah = 0;
			for ($a = $b;$a < count($matriks_perbandingan);$a+= count($kriteria)) {
				$jumlah += $matriks_perbandingan[$a]["nilai"];
			}
			$array_jumlah[$b] = ["jumlah" => number_format(abs($jumlah), 4)];
		}
		$array_normalisasi = null;
		$counter = 0;
		$array_filter = [];
		for ($a = 0; $a < count($kriteria); $a++) {
			for ($b = 0; $b < count($matriks_perbandingan); $b++) {
				if ($kriteria[$a]->kriteria1 ==$matriks_perbandingan[$b]["kode_kriteria"]) {
					array_push(
						$array_filter,
						$matriks_perbandingan[$b]["nilai"]
					);
				}
			}
			for ($c = 0; $c < count($matriks_perbandingan); $c++) {
				for ($e = 0; $e < count($array_filter); $e++) {
					$kolom = $e-1;
					$hasil = 0;
					for ($d = 0; $d < count($array_filter); $d++) {
						$hasil +=($array_filter[$d] *$matriks_perbandingan[$kolom]["nilai"]);
						$kolom+= count($array_filter);
					}
					$array_normalisasi[$counter] = [
						"nilai" => number_format(abs($hasil), 4),
						"kode_kriteria" => $kriteria[$a]->kriteria1,
					];
					$counter++;
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
		$urutan = 0;
		for ($a = 0; $a < count($array_normalisasi); $a++) {
			$jumlah_baris = $jumlah_baris + $array_normalisasi[$a]["nilai"];
			if ($index_kriteria == count($kriteria) - 1) {
				$array_BobotPrioritas[$urutan] = [
					"jumlah_baris" => number_format(abs($jumlah_baris), 4),
					"bobot" => number_format(
						abs($jumlah_baris / $total_jumlah_baris),
						4
					),
					"kode_kriteria" => $kriteria[$urutan]->kode_kriteria,
				];
				$urutan++;
				$jumlah_baris = 0;
				$index_kriteria = 0;
			} else {
				$index_kriteria++;
			}
		}
		$array_CM = null;
		$cm = 0;
		$indexbobot = 0;
		$count = 0;
		for ($a = 0; $a < count($matriks_perbandingan); $a++) {
			$cm = number_format(
				abs(
					$cm +
						$matriks_perbandingan[$a]["nilai"] *
							$array_BobotPrioritas[$indexbobot]["bobot"]
				),
				4
			);
			if ($indexbobot == count($kriteria) - 1) {
				$array_CM[$count] = [
					"cm" => number_format(
						abs($cm / $array_BobotPrioritas[$count]["bobot"]),
						4
					),
					"kode_kriteria" => $kriteria[$count]->kriteria1,
					"kali_matriks" => $cm,
				];
				$count++;
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
		$result = number_format(abs($total_ci / $ratio[count($kriteria)]), 4);
		for ($a = 0; $a < count($kriteria); $a++) {
			Kriteria::where(
				"id",
				$kriteria[$a]->kriteria1
			)->update([
				"bobot" => $array_BobotPrioritas[$a]["bobot"],
			]);
		}
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
		return view('main.kriteria-hasil',compact('data'));
	}
}
