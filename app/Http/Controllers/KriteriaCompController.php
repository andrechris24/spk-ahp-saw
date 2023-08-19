<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\KriteriaComp;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;

class KriteriaCompController extends Controller
{
	private function getKriteriaPerbandingan()
	{
		return KriteriaComp::join(
			"kriteria",
			"kriteria_banding.kriteria1",
			"kriteria.id"
		)->select("kriteria_banding.kriteria1 as idkriteria","kriteria.name")
		->groupBy("kriteria1", 'name')->get();
	}
	private function getPerbandinganByKriteria1($kriteria1)
	{
		return KriteriaComp::select('nilai', 'kriteria2', 'kriteria1')
			->where("kriteria2", "=", $kriteria1)->get();
	}
	private function getNilaiPerbandingan($kode_kriteria)
	{
		return KriteriaComp::select("nilai", "kriteria1")
			->where("kriteria1", "=", $kode_kriteria)->get();
	}
	public function index(): Factory|View|Application
	{
		$crit = Kriteria::get();
		$jmlcrit = count($crit);
		$array = [];
		$counter = 0;
		for ($a = 0; $a < $jmlcrit; $a++) {
			for ($b = $a; $b < $jmlcrit; $b++) {
				$array[$counter]["baris"] = $crit[$a]->name;
				$array[$counter]["kolom"] = $crit[$b]->name;
				$counter++;
			}
		}
		$cek = KriteriaComp::count();
		return view('main.kriteria.comp', compact('array', 'cek', 'jmlcrit'));
	}
	public function simpan(Request $request): Redirector|RedirectResponse|Application
	{
		$request->validate(KriteriaComp::$rules, KriteriaComp::$message);
		try {
			$kriteria = Kriteria::get();
			KriteriaComp::truncate();
			$a = 0;
			for ($i = 0; $i < count($kriteria); $i++) {
				for ($j = $i; $j < count($kriteria); $j++) {
					$perbandingan = new KriteriaComp();
					$perbandingan->kriteria1 = $kriteria[$i]->id;
					$perbandingan->kriteria2 = $kriteria[$j]->id;
					if ($request->kriteria[$a] === 'right') 
						$nilai = 0 - $request->skala[$a];
					else if ($request->kriteria[$a] === 'left') 
						$nilai = $request->skala[$a];
					else $nilai = 1;
					$perbandingan->nilai = $nilai;
					$perbandingan->save();
					$a++;
				}
			}
		} catch (QueryException $sql) {
			return back()->withInput()->withErrors($sql->getMessage());
		}
		return redirect('/bobot/hasil');
	}
	public function hasil(): Factory|View|Application
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
			for ($i = $j;$i < count($matriks_perbandingan);$i+= count($kriteria)) {
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
					$kriteria[$i]->idkriteria ===
					$matriks_perbandingan[$j]["kode_kriteria"]
				) $array_filter[] = $matriks_perbandingan[$j]["nilai"];
			}
			for ($k = 0; $k < count($matriks_perbandingan); $k++) {
				for ($m = 0; $m < count($array_filter); $m++) {
					$kolom = $m;
					$hasil = 0;
					for ($l = 0; $l < count($array_filter); $l++) {
						$hasil += $array_filter[$l] * $matriks_perbandingan[$kolom]["nilai"];
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
			} else $index_kriteria++;
		}
		$array_CM = null;
		$cm = 0;
		$indexbobot = 0;
		$j = 0;
		for ($i = 0; $i < count($matriks_perbandingan); $i++) {
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
			} else $indexbobot++;
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
		$ratio = KriteriaComp::$ratio_index[count($kriteria)];
		if ($ratio == 0) $result = '-';
		else $result = number_format(abs($total_ci / $ratio), 4);
		if ($result <= 0.1 || !is_numeric($result)) {
			for ($i = 0; $i < count($kriteria); $i++) {
				Kriteria::where("id",$kriteria[$i]->idkriteria)
				->update(["bobot" => $array_BobotPrioritas[$i]["bobot"]]);
			}
		} else {
			DB::table('kriteria')->where('bobot', '<>', 0.0000)
			->update(['bobot' => 0.0000]);
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
		return view('main.kriteria.hasil', compact('data'));
	}
	public function destroy()
	{
		try {
			$del = DB::table('kriteria_banding')->delete();
			if (!$del) {
				return redirect('/bobot')
					->withWarning('Perbandingan Kriteria tidak ditemukan');
			}
			DB::table('kriteria')->where('bobot', '<>', 0.0000)
			->update(['bobot' => 0.0000]);
			return redirect('/bobot')
			->withSuccess('Perbandingan Kriteria sudah direset');
		} catch (QueryException $sql) {
			return redirect('/bobot')->withError('Perbandingan Kriteria gagal direset:')
				->withErrors($sql->getMessage());
		}
	}
}