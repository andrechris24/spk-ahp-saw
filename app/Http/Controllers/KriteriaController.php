<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\KriteriaComp;
use App\Models\Nilai;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
				if (KriteriaComp::exists()) {
					DB::table('kriteria_banding')->delete();
					DB::table('kriteria')->update(['bobot' => 0.0000]);
					return back()->withSuccess(
						'Kriteria sudah ditambahkan. '.
						'Silahkan input ulang perbandingan kriteria.'
					);
				}
				return back()->withSuccess('Kriteria sudah ditambahkan');
			}
		} catch (QueryException $db) {
			return back()->withError('Gagal menambah kriteria:')
				->withErrors($db->getMessage());
		}
		return back()->withError('Gagal menambah kriteria: Kesalahan tidak diketahui');
	}
	public function update(Request $updkritrequest, $id)
	{
		try {
			$req = $updkritrequest->all();
			$upd=Kriteria::findOrFail($id)->update($req);
			if($upd) return back()->withSuccess('Kriteria sudah diupdate');
		} catch (ModelNotFoundException $e) {
			return back()->withError('Gagal update: Kriteria tidak ditemukan')
				->withErrors($e->getMessage());
		} catch (QueryException $db) {
			return back()->withError('Gagal update kriteria:')
				->withErrors($db->getMessage());
		}
		return back()->withError('Gagal update Kriteria: Kesalahan tidak diketahui');
	}
	public function hapus($id)
	{
		try {
			$del = Kriteria::findOrFail($id)->delete();
			if ($del) {
				if (KriteriaComp::exists()) {
					DB::table('kriteria_banding')->delete();
					DB::table('kriteria')->update(['bobot' => 0.0000]);
					return back()->withSuccess(
						'Kriteria sudah dihapus. Silahkan input ulang perbandingan.'
					);
				}
				return back()->withSuccess('Kriteria sudah dihapus');
			}
		} catch (ModelNotFoundException $e) {
			return back()->withError('Gagal hapus: Kriteria tidak ditemukan')
				->withErrors($e->getMessage());
		} catch (QueryException $e) {
			return back()->withError('Gagal hapus kriteria:')
				->withErrors($e->getMessage());
		}
		return back()->withError('Gagal hapus kriteria: Kesalahan tidak diketahui');
	}
}