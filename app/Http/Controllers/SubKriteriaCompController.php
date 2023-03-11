<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SubKriteriaComp;
use App\Models\SubKriteria;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SubKriteriaCompController extends Controller
{
	private function getSubKriteriaPerbandingan($id)
	{
		$subkriteria_comp = DB::table('subkriteria_banding')
			->join(
				"subkriteria",
				"subkriteria_banding.subkriteria1",
				"subkriteria.id"
			)
			->select(
				"subkriteria_banding.subkriteria1 as idsubkriteria",
				"subkriteria.name"
			)
			->groupBy("subkriteria1", 'name')
			->where('kriteria_id','=',$id)
			->get();
		return $subkriteria_comp;
	}
	private function getPerbandinganBySubKriteria1($subkriteria1,$id)
	{
		$subkriteria2 = DB::table("subkriteria_banding")
			->select('nilai', 'subkriteria2', 'subkriteria1')
			->where("subkriteria2", "=", $subkriteria1)
			->where('idkriteria','=',$id)
			->get();
		return $subkriteria2;
	}
	private function getNilaiPerbandingan($kode_kriteria,$id)
	{
		$nilai_perbandingan = DB::table("subkriteria_banding")
			->select("nilai", "subkriteria1")
			->where("subkriteria1", "=", $kode_kriteria)
			->where('idkriteria','=',$id)
			->get();
		return $nilai_perbandingan;
	}
	public function nama_kriteria($id)
	{
		$kriteria = Kriteria::where('id', '=', $id)->first();
		return $kriteria['name'];
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$allkrit = Kriteria::get();
		if (count($allkrit) == 0){
			return redirect('/kriteria')
			->with(
				'warning', 
				'Masukkan kriteria dulu untuk melakukan perbandingan sub kriteria'
			);
		}
		$crit = SubKriteria::get();
		if (count($crit) == 0){
			return redirect('/kriteria')
			->with(
				'warning', 
				'Masukkan data sub kriteria dulu untuk melakukan perbandingan sub kriteria'
			);
		}
		return view('main.subkriteria.select', compact('allkrit'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		$validate = $request->validate([
			'kriteria_id' => 'required|numeric'
		]);
		$idkriteria = $request->kriteria_id;
		if(isset($request->lompat)) {
			$cek=SubKriteriaComp::where('idkriteria','=',$idkriteria)->count();
			if($cek>0) return redirect('/bobot/sub/hasil/'.$idkriteria);
			else 
				$message="Hasil sub Kriteria belum tersedia, mohon untuk diisi dulu";
		}
		$subkriteria = SubKriteria::where('kriteria_id', '=', $idkriteria)->get();
		$counter = 0;
		for ($a = 0; $a < count($subkriteria); $a++) {
			for ($b = 0; $b < count($subkriteria); $b++) {
				$array[$counter]["baris"] = $subkriteria[$a]->name;
				$array[$counter]["kolom"] = $subkriteria[$b]->name;
				$counter++;
			}
		}
		if(isset($message)){
			return view('main.subkriteria.comp', compact('array'),['warning'=>$message])->with([
				'kriteria_id' => $idkriteria
			]);
		}
		return view('main.subkriteria.comp', compact('array'))->with([
				'kriteria_id' => $idkriteria
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$request->validate(SubKriteriaComp::$rules, SubKriteriaComp::$message);
		$subkriteria = DB::table("subkriteria")
			->where('kriteria_id','=',$request->kriteria_id)->get();
		DB::table("subkriteria_banding")->
		where('idkriteria','=',$request->kriteria_id);
		$a = 0;
		for ($i = 0; $i < count($subkriteria); $i++) {
			for ($j = $i; $j < count($subkriteria); $j++) {
				$perbandingan = new SubKriteriaComp();
				$perbandingan->subkriteria1 = $subkriteria[$i]->id;
				$perbandingan->subkriteria2 = $subkriteria[$j]->id;
				$perbandingan->idkriteria=$request->kriteria_id;
				if ($request->kolom[$a] > $request->baris[$a]) {
					$nilai = 0 - $request->kolom[$a];
				} else $nilai = $request->baris[$a];
				$perbandingan->nilai = $nilai;
				$perbandingan->save();
				$a++;
			}
		}
		return redirect('/bobot/sub/hasil/'.$request->kriteria_id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\SubKriteriaComp  $subKriteriaComp
	 * @return \Illuminate\Http\Response
	 */
	public function show(SubKriteriaComp $subKriteriaComp,$id)
	{
		$subkriteria = $this->getSubKriteriaPerbandingan($id);
		$a = 0;
		foreach ($subkriteria as $k) {
			$kode_kriteria = $k->idsubkriteria;
			$perbandingan = $this->getPerbandinganBySubKriteria1($kode_kriteria,$id);
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
				$nilaiPerbandingan = $this->getNilaiPerbandingan($kode_kriteria,$id);
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
			for (
				$i = $j;
				$i < count($matriks_perbandingan);
				$i = $i + count($subkriteria)
			) {
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
					$subkriteria[$i]->idsubkriteria ==
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
			if ($index_kriteria == count($subkriteria) - 1) {
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
			} else {
				$index_kriteria++;
			}
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
			if ($indexbobot == count($subkriteria) - 1) {
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
			abs(($average_cm - count($subkriteria)) / (count($subkriteria) - 1)),
			4
		);
		$ratio = [null, 0, 0, 0.58, 0.9, 1.12, 1.24, 1.32, 1.41, 1.45];
		$result = number_format(abs($total_ci / $ratio[count($subkriteria) - 1]), 4);
		for ($i = 0; $i < count($kriteria); $i++) {
			SubKriteria::where(
				"id",
				$kriteria[$i]->idsubkriteria
			)->where('kriteria_id','=',$id)
			->update([
				"bobot" => $array_BobotPrioritas[$i]["bobot"],
			]);
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
		];
		return view('main.subkriteria.hasil', compact('data'))
		->with(['kriteria_id' => $id]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\SubKriteriaComp  $subKriteriaComp
	 * @return \Illuminate\Http\Response
	 */
	public function edit(SubKriteriaComp $subKriteriaComp)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\SubKriteriaComp  $subKriteriaComp
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, SubKriteriaComp $subKriteriaComp)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\SubKriteriaComp  $subKriteriaComp
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		DB::table('subkriteria_banding')->where('idkriteria','=',$id)->delete();
		return redirect()->action("SubKriteriaCompController@create",[$id]);
	}
}
