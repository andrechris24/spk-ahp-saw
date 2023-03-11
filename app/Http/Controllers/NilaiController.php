<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\Alternatif;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
	private function getAlternatif()
	{
		$alternatif = DB::table("hasil")
			->join("nilai", "hasil.id_nilai", "nilai.id")
			->join("alternatif", "nilai.alternatif_id", "alternatif.id")
			->select("alternatif.name", "alternatif.id")
			->groupBy("nilai.alternatif_id")
			->orderBy("alternatif.id")
			->get();
		return $alternatif;
	}

	private function getKriteria()
	{
		$kriteria = DB::table("hasil")
			->join("nilai", "hasil.id_nilai", "penilaian.kode_hasil")
			->join(
				"kriteria",
				"nilai.kriteria_id",
				"kriteria.id"
			)
			->select(
				"kriteria.name",
				"kriteria.bobot",
				"kriteria.id"
			)
			->groupBy("nilai.kriteria_id")
			->get();
		return $kriteria;
	}
	public function getNilaiAwal()
		{
				$nilai_awal = Hasil::join(
						"nilai",
						"hasil.id_nilai",
						"=",
						"nilai.id"
				)
						->groupBy("penilaian.kode_penilaian")
						->get(["nilai.*"]);

				return $nilai_awal;
		}

		public function getNilaiAkhir()
		{
				$hasil_akhir = Hasil::join(
						"penilaian",
						"hasil.kode_hasil",
						"penilaian.kode_hasil"
				)
						->join("guru", "penilaian.kode_guru", "guru.kode_guru")
						->groupBy("penilaian.kode_hasil")
						->get([
								"guru.nama",
								"guru.kode_guru",
								"hasil.nilai_saw",
								"hasil.keterangan",
								"hasil.kode_hasil",
						])
						->toArray();

				foreach ($hasil_akhir as $key => $isi) {
						$nama[$key] = $isi["nama"];
						$keterangan[$key] = $isi["keterangan"];
						$kode_hasil[$key] = $isi["kode_hasil"];
						$nilai_saw[$key] = $isi["nilai_saw"];
				}

				$nama = array_column($hasil_akhir, "nama");
				$keterangan = array_column($hasil_akhir, "keterangan");
				$kode_hasil = array_column($hasil_akhir, "kode_hasil");
				$nilai_saw = array_column($hasil_akhir, "nilai_saw");

				array_multisort($nilai_saw, SORT_DESC, $hasil_akhir);

				// $hasil_akhir = DB::table("hasil")
				//     ->join("penilaian", "hasil.kode_hasil", "penilaian.kode_hasil")
				//     ->join("guru", "penilaian.kode_guru", "guru.kode_guru")
				//     ->select(
				//         "guru.nama",
				//         "hasil.nilai_saw",
				//         "hasil.keterangan",
				//         "hasil.kode_hasil"
				//     )
				//     ->where("periode", $periode)
				//     ->groupBy("penilaian.kode_hasil")
				//     ->get();

				return $hasil_akhir;
		}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$kriteria=Kriteria::get();
		if(count($kriteria)==0){
			return redirect('kriteria')
			->with(
				'warning',
				'Tambahkan kriteria dan sub kriteria dulu sebelum melakukan penilaian alternatif'
			);
		}
		$subkriteria=SubKriteria::get();
		if(count($subkriteria)==0){
			return redirect('kriteria/sub')
			->with(
				'warning',
				'Tambahkan sub kriteria dulu sebelum melakukan penilaian alternatif'
			);
		}
		$alternatif=Alternatif::get();
		if(count($alternatif)==0){
			return redirect('alternatif')
			->with('warning','Tambahkan alternatif dulu sebelum melakukan penilaian');
		}
		$nilaialt=Nilai::get();
		return view(
			'main.alternatif.nilai',
			compact('kriteria','subkriteria','alternatif','nilaialt')
		);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$request->validate(Nilai::$rules,Nilai::$message);
		$scores=$request->all();
		for($a=0;$a<count($scores['kriteria_id']);$a++){
			$nilai=new Nilai();
			$nilai->alternatif_id=$scores['alternatif_id'];
			$nilai->kriteria_id=$scores['kriteria_id'][$a];
			$nilai->subkriteria_id=$scores['subkriteria_id'][$a];
			$nilai->save();
		}
		return back()->with('success','Penilaian alternatif sudah ditambahkan');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Nilai  $nilai
	 * @return \Illuminate\Http\Response
	 */
	public function show(Nilai $nilai)
	{
		$alternatif = $this->getAlternatif();
		$kriteria = $this->getKriteria();
		$nilai_awal = $this->getNilaiAwal();
		$hasil_akhir = $this->getNilaiAkhir();
		$Maxmin = $this->getMaxMin($nilai_awal);
		$hasilnormalisasi = $this->matriks_normalisasi($nilai_awal, $Maxmin);

		$data = [
			"kriteria" => $kriteria,
			"guru" => $alternatif,
			"nilai_awal" => $nilai_awal,
			"nilai_matriks" => $hasilnormalisasi,
			"hasil_akhir" => $hasil_akhir,
		];
		return view('main.alternatif.hasil');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Nilai  $nilai
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Nilai $nilai)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Nilai  $nilai
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Nilai $nilai)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Nilai  $nilai
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Nilai $nilai)
	{
		//
	}
}
