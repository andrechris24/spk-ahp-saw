<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\KriteriaComp;
use App\Models\Nilai;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class KriteriaController extends Controller
{
	public function index(): Factory|View|Application
	{
		$krit = Kriteria::get();
		$ceknilai = Nilai::count();
		$compkr = KriteriaComp::count();
		return view('main.kriteria.index', compact('krit', 'compkr', 'ceknilai'));
	}
	public function tambah(Request $kritrequest)
	{
		$kritrequest->validate(Kriteria::$rules, Kriteria::$message);
		$krits = $kritrequest->all();
		try {
			$kriteria = Kriteria::create($krits);
			if ($kriteria) {
				$cekhasil = KriteriaComp::count();
				if ($cekhasil > 0) {
					DB::table('kriteria_banding')->delete();
					DB::table('kriteria')->update(['bobot' => 0.0000]);
					return back()
						->withSuccess(
							'Kriteria sudah ditambahkan. Silahkan input ulang perbandingan kriteria.'
						);
				}
				return back()->withSuccess('Kriteria sudah ditambahkan.');
			}
		} catch (QueryException $db) {
			return back()->withError('Gagal menambah kriteria:')
				->withErrors($db->getMessage());
		}
		return back()->withError('Gagal menambah kriteria');
	}
	public function update(Request $updkritrequest, $id)
	{
		try {
			$cek = Kriteria::find($id);
			if (!$cek) return back()->withError('Data Kriteria tidak ditemukan');
			$req = $updkritrequest->all();
			$cek->update($req);
			return back()->withSuccess('Data Kriteria sudah diupdate');
		} catch (QueryException $db) {
			return back()->withError('Gagal mengupdate data kriteria')
				->withErrors($db->getMessage());
		}
	}
	public function hapus($id)
	{
		try {
			$cek = Kriteria::find($id);
			if (!$cek) return back()->withError('Data Kriteria tidak ditemukan');
			$del = $cek->delete();
			if ($del) {
				$cekhasil = KriteriaComp::count();
				if ($cekhasil > 0) {
					DB::table('kriteria_banding')->delete();
					DB::table('kriteria')->update(['bobot' => 0.0000]);
					return back()
						->withSuccess(
							'Data Kriteria sudah dihapus. Silahkan input ulang perbandingan.'
						);
				}
				return back()->withSuccess('Data Kriteria sudah dihapus');
			}
		} catch (QueryException $e) {
			return back()->withError('Data kriteria gagal dihapus:')
				->withErrors($e->getMessage());
		}
		return back()->withError('Data kriteria gagal dihapus');
	}
}
