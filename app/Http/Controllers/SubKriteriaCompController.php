<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\SubKriteriaComp;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class SubKriteriaCompController extends Controller
{
	private function getSubKriteriaPerbandingan($id)
	{
		return SubKriteriaComp::join(
			"subkriteria",
			"subkriteria_banding.subkriteria1",
			"subkriteria.id"
		)->select(
				"subkriteria_banding.subkriteria1 as idsubkriteria",
				"subkriteria.name"
			)->groupBy("subkriteria1", 'name')
			->where('kriteria_id', '=', $id)
			->get();
	}
	private function getPerbandinganBySubKriteria1($subkriteria1, $id)
	{
		return SubKriteriaComp::select('nilai', 'subkriteria2', 'subkriteria1')
			->where("subkriteria2", "=", $subkriteria1)
			->where('idkriteria', '=', $id)
			->get();
	}
	private function getNilaiPerbandingan($kode_kriteria, $id)
	{
		return SubKriteriaComp::select("nilai", "subkriteria1")
			->where("subkriteria1", "=", $kode_kriteria)
			->where('idkriteria', '=', $id)
			->get();
	}
	public function nama_kriteria($id)
	{
		$kriteria = Kriteria::where('id', '=', $id)->first();
		return $kriteria['name'];
	}
	public function index()
	{
		$allkrit = Kriteria::get();
		if (count($allkrit) === 0) {
			return redirect('/kriteria')->withWarning(
				'Masukkan kriteria dulu untuk melakukan perbandingan sub kriteria'
			);
		}
		if (SubKriteria::count() === 0) {
			return redirect('/kriteria/sub')->withWarning(
				'Masukkan data sub kriteria dulu ' .
				'untuk melakukan perbandingan sub kriteria'
			);
		}
		return view('main.subkriteria.select', compact('allkrit'));
	}
	public function create(Request $request): Factory|View|Application
	{
		$request->validate(['kriteria_id' => 'required|numeric']);
		$idkriteria = $request->kriteria_id;
		$subkriteria = SubKriteria::where('kriteria_id', '=', $idkriteria)->get();
		$jmlsubkriteria = count($subkriteria);
		$array = [];
		$counter = 0;
		for ($a = 0; $a < $jmlsubkriteria; $a++) {
			for ($b = $a; $b < $jmlsubkriteria; $b++) {
				$array[$counter]["baris"] = $subkriteria[$a]->name;
				$array[$counter]["kolom"] = $subkriteria[$b]->name;
				$counter++;
			}
		}
		$cek = SubKriteriaComp::where('idkriteria', '=', $idkriteria)->count();
		return view('main.subkriteria.comp', compact('array', 'cek', 'jmlsubkriteria'))
			->with(['kriteria_id' => $idkriteria]);
	}

	public function store(Request $request, $kriteria_id): Redirector|Application|RedirectResponse
	{
		$request->validate(SubKriteriaComp::$rules, SubKriteriaComp::$message);
		try {
			$subkriteria = SubKriteria::where('kriteria_id', $kriteria_id)->get();
			SubKriteriaComp::where('idkriteria', '=', $kriteria_id)->delete();
			if (SubKriteriaComp::count() === 0)
				SubKriteriaComp::truncate();
			$a = 0;
			for ($i = 0; $i < count($subkriteria); $i++) {
				for ($j = $i; $j < count($subkriteria); $j++) {
					$perbandingan = new SubKriteriaComp();
					$perbandingan->subkriteria1 = $subkriteria[$i]->id;
					$perbandingan->subkriteria2 = $subkriteria[$j]->id;
					$perbandingan->idkriteria = $kriteria_id;
					if ($request->kriteria[$a] === 'right')
						$nilai = 0 - $request->skala[$a];
					else if ($request->kriteria[$a] === 'left')
						$nilai = $request->skala[$a];
					else
						$nilai = 1;
					$perbandingan->nilai = $nilai;
					$perbandingan->save();
					$a++;
				}
			}
			return redirect('/bobot/sub/hasil/' . $kriteria_id);
		} catch (QueryException $e) {
			return back()->withError(
				'Gagal menambah perbandingan sub kriteria ' .
				$this->nama_kriteria($kriteria_id)
			)->withErrors($e->getMessage());
		}
	}

	public function show($id): Factory|View|Application
	{
		$subkriteria = $this->getSubKriteriaPerbandingan($id);
		$a = 0;
		foreach ($subkriteria as $k) {
			$kode_kriteria = $k->idsubkriteria;
			$perbandingan = $this->getPerbandinganBySubKriteria1($kode_kriteria, $id);
			if ($perbandingan) {
				foreach ($perbandingan as $hk) {
					if ($hk->subkriteria2 !== $hk->subkriteria1) {
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
				$nilaiPerbandingan = $this->getNilaiPerbandingan($kode_kriteria, $id);
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
		for ($j = 0; $j < count($subkriteria); $j++) {
			$jumlah = 0;
			for ($i = $j; $i < count($matriks_perbandingan); $i += count($subkriteria)) {
				$jumlah = $jumlah + $matriks_perbandingan[$i]["nilai"];
			}
			$array_jumlah[$j] = ["jumlah" => number_format(abs($jumlah), 4)];
		}
		$array_normalisasi = null;
		$a = 0;
		$array_filter = [];
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
					$kolom = $m;
					$hasil = 0;
					for ($l = 0; $l < count($array_filter); $l++) {
						$hasil += $array_filter[$l] * $matriks_perbandingan[$kolom]["nilai"];
						$kolom += count($array_filter);
					}
					$array_normalisasi[$a] = [
						"nilai" => number_format(abs($hasil), 4),
						"kode_kriteria" => $subkriteria[$i]->idsubkriteria,
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
			if ($index_kriteria === count($subkriteria) - 1) {
				$array_BobotPrioritas[$j] = [
					"jumlah_baris" => number_format(abs($jumlah_baris), 4),
					"bobot" => number_format(
						abs($jumlah_baris / $total_jumlah_baris),
						4
					),
					"kode_kriteria" => $subkriteria[$j]->idsubkriteria,
				];
				$j++;
				$jumlah_baris = 0;
				$index_kriteria = 0;
			} else
				$index_kriteria++;
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
			if ($indexbobot === count($subkriteria) - 1) {
				$array_CM[$j] = [
					"cm" => number_format(
						abs($cm / $array_BobotPrioritas[$j]["bobot"]),
						4
					),
					"kode_kriteria" => $subkriteria[$j]->idsubkriteria,
					"kali_matriks" => $cm,
				];
				$j++;
				$cm = 0;
				$indexbobot = 0;
			} else
				$indexbobot++;
		}
		$total_cm = 0;
		foreach ($array_CM as $cm) {
			$total_cm = $total_cm + $cm["cm"];
		}
		$average_cm = number_format(abs($total_cm / count($array_CM)), 4);
		$total_ci = number_format(
			abs(($average_cm - count($subkriteria)) / (count($subkriteria) - 1)),
			4
		);
		$ratio = SubKriteriaComp::$ratio_index[count($subkriteria)];
		if ($ratio === 0)
			$result = '-';
		else
			$result = number_format(abs($total_ci / $ratio), 4);
		if ($result <= 0.1 || !is_numeric($result)) {
			for ($i = 0; $i < count($subkriteria); $i++) {
				SubKriteria::where("id", $subkriteria[$i]->idsubkriteria)
					->where('kriteria_id', '=', $id)
					->update(["bobot" => $array_BobotPrioritas[$i]["bobot"]]);
			}
			$subbobotkosong = SubKriteria::where('bobot', '=', 0.0000)->count();
		} else {
			SubKriteria::where('kriteria_id', '=', $id)->update(['bobot' => 0.0000]);
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
			"total_jumlah_baris" => $total_jumlah_baris,
			"bobot_sub_kosong" => $subbobotkosong
		];
		return view('main.subkriteria.hasil', compact('data'))
			->with(['kriteria_id' => $id]);
	}

	public function destroy($id)
	{
		try {
			$kr = Kriteria::where('id', '=', $id)->first();
			SubKriteriaComp::where('idkriteria', '=', $id)->delete();
			SubKriteria::where('kriteria_id', '=', $id)->update(['bobot' => 0.0000]);
			return redirect('/bobot/sub')
				->withSuccess('Perbandingan Sub kriteria ' . $kr->name . ' sudah direset');
		} catch (QueryException $e) {
			return redirect('/bobot/sub')->withError(
				'Perbandingan Sub kriteria ' . $kr->name . ' gagal direset'
			)->withErrors($e->getMessage());
		}
	}
}