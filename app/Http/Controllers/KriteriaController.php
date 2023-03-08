<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KriteriaController extends Controller
{
	public function index()
	{
		$krit = Kriteria::get();
		return view('main.kriteria', compact('krit'));
	}
	public function tambah(Request $kritrequest)
	{
		$kritrequest->validate(Kriteria::$rules, Kriteria::$message);
		$krits = $kritrequest->all();
		$kriteria = Kriteria::create($krits);
		if ($kriteria) return back()->with('success', 'Kriteria sudah ditambahkan');
		return back()->with('error', 'Gagal menambah kriteria');
	}
	public function update(Request $updkritrequest, $id)
	{
		$cek = Kriteria::find($id);
		if (!$cek) return back()->with('error', 'Data Kriteria tidak ditemukan');
		$req = $updkritrequest->all();
		$upd = $cek->update($req);
		if ($upd) return back()->with('success', 'Data Kriteria sudah diupdate');
		return back()->with('error', 'Gagal mengupdate data kriteria');
	}
	public function hapus($id)
	{
		$cek = Kriteria::find($id);
		if (!$cek) return back()->with('error', 'Data Kriteria tidak ditemukan');
		$del = $cek->delete();
		if ($del) return back()->with('success', 'Data Kriteria sudah dihapus');
		return back()->with('error', 'Data kriteria gagal dihapus');
	}
	public function bobot()
	{
		$crit = Kriteria::get();
		$total = DB::table('kriteria')->count();
		return view('main.kriteria-comp', compact('total', 'crit'));
	}
	public function hitung(Request $request)
	{
		$allrequest = $request->all();
		$n = DB::table('kriteria')->count();
		$matriks = array();
		$urutan = 0;
		for ($a = 0; $a < ($n - 1); $a++) {
			for ($b = ($a + 1); $b < $n; $b++) {
				$urutan++;
				$pilihan = $request->input('pilihan')[$urutan];
				$bobot = $request->input('bobot')[$urutan];
				if ($pilihan == 1) {
					$matriks[$a][$b] = $bobot;
					$matriks[$b][$a] = 1 / $bobot;
				} else {
					$matriks[$a][$b] = 1 / $bobot;
					$matriks[$b][$a] = $bobot;
				}
				$this->inputCriteriaComp($a, $b, $matriks[$a][$b]);
			}
		}
		for ($f = 0; $f < $n; $f++) {
			$matriks[$f][$f] = 1;
		}
		$jmlmpb = array();
		$jmlmnk = array();
		for ($z = 0; $z < $n; $z++) {
			$jmlmpb[$z] = 0;
			$jmlmnk[$z] = 0;
		}
		for ($x = 0; $x < $n; $x++) {
			for ($y = 0; $y < $n; $y++) {
				$value		= $matriks[$x][$y];
				$jmlmpb[$y] += $value;
			}
		}
		for ($x = 0; $x <= ($n - 1); $x++) {
			for ($y = 0; $y <= ($n - 1); $y++) {
				$matriksb[$x][$y] = $matriks[$x][$y] / $jmlmpb[$y];
				$value	= $matriksb[$x][$y];
				$jmlmnk[$x] += $value;
			}
			$pv[$x]	 = $jmlmnk[$x] / $n;
			$kriteria_id = $this->getCriteriaID($x);
			$this->inputKriteriaPV($kriteria_id, $pv[$x]);
		}
		$eigenvektor = $this->getEigenVector($jmlmpb, $jmlmnk, $n);
		$consIndex   = $this->getConsIndex($jmlmpb, $jmlmnk, $n);
		$consRatio   = $this->getConsRatio($jmlmpb, $jmlmnk, $n);
		return;
	}
}
