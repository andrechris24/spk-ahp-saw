<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Nilai;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class AlternatifController extends Controller
{
	public function index(): Factory|View|Application
	{
		$alt = Alternatif::get();
		$ceknilai = Nilai::count();
		return view('main.alternatif.index', compact('alt', 'ceknilai'));
	}
	public function tambah(Request $altrequest)
	{
		$altrequest->validate(Alternatif::$rules, Alternatif::$message);
		$alts = $altrequest->all();
		try {
			$alternatif = Alternatif::create($alts);
			if ($alternatif) return back()->withSuccess('Alternatif sudah ditambahkan');
		} catch (QueryException $e) {
			return back()->withError('Gagal menambah alternatif')
				->withErrors($e->getMessage());
		}
		return back()->withError('Gagal menambah alternatif');
	}
	public function update(Request $updaltrequest, $id)
	{
		try {
			$cek = Alternatif::find($id);
			if (!$cek) return back()->withError('Data Alternatif tidak ditemukan');
			$req = $updaltrequest->all();
			$upd = $cek->update($req);
			if ($upd) return back()->withSuccess('Data Alternatif sudah diupdate');
		} catch (QueryException $sql) {
			return back()->withError('Gagal update data Alternatif')
				->withErrors($sql->getMessage());
		}
		return back()->withError('Gagal update data Alternatif');
	}
	public function hapus($id)
	{
		try {
			$cek = Alternatif::find($id);
			if (!$cek) return back()->withError('Data Alternatif tidak ditemukan');
			$del = $cek->delete();
			if ($del) return back()->withSuccess('Data Alternatif sudah dihapus');
		} catch (QueryException $sql) {
			return back()->withError('Data Alternatif gagal dihapus')
				->withErrors($sql->getMessage());
		}
		return back()->withError('Data Alternatif gagal dihapus');
	}
}
