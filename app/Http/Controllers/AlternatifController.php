<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Nilai;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
		return back()
		->withError('Gagal menambah alternatif: Kesalahan tidak diketahui');
	}
	public function update(Request $updaltrequest, $id)
	{
		try {
			$req = $updaltrequest->all();
			$upd = Alternatif::findOrFail($id)->update($req);
			if ($upd) return back()->withSuccess('Data Alternatif sudah diupdate');
		} catch (ModelNotFoundException $e) {
			return back()->withError('Gagal update: Alternatif tidak ditemukan')
				->withErrors($e->getMessage());
		} catch (QueryException $sql) {
			return back()->withError('Gagal update alternatif:')
				->withErrors($sql->getMessage());
		}
		return back()->withError('Gagal update alternatif: Kesalahan tidak diketahui');
	}
	public function hapus($id)
	{
		try {
			$del = Alternatif::findOrFail($id)->delete();
			if ($del)
				return back()->withSuccess('Data Alternatif sudah dihapus');
		} catch (ModelNotFoundException $e) {
			return back()->withError('Gagal hapus: Data Alternatif tidak ditemukan')
				->withErrors($e->getMessage());
		} catch (QueryException $sql) {
			return back()->withError('Gagal hapus:')
				->withErrors($sql->getMessage());
		}
		return back()->withError('Gagal hapus alternatif: Kesalahan tidak diketahui');
	}
}