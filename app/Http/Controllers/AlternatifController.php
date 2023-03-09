<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
class AlternatifController extends Controller
{
	public function index()
	{
		$alt = Alternatif::get();
		return view('main.alternatif.index', compact('alt'));
	}
	public function tambah(Request $altrequest)
	{
		$altrequest->validate(Alternatif::$rules, Alternatif::$message);
		$alts = $altrequest->all();
		$alternatif = Alternatif::create($alts);
		if ($alternatif) return back()->with('success', 'Alternatif sudah ditambahkan');
		return back()->with('error', 'Gagal menambah alternatif');
	}
	public function update(Request $updaltrequest, $id)
	{
		$cek = Alternatif::find($id);
		if (!$cek) return back()->with('error', 'Data Alternatif tidak ditemukan');
		$req = $updaltrequest->all();
		$upd = $cek->update($req);
		if ($upd) return back()->with('success', 'Data Alternatif sudah diupdate');
		return back()->with('error', 'Gagal mengupdate data Alternatif');
	}
	public function hapus($id)
	{
		$cek = Alternatif::find($id);
		if (!$cek) return back()->with('error', 'Data Alternatif tidak ditemukan');
		$del = $cek->delete();
		if ($del) return back()->with('success', 'Data Alternatif sudah dihapus');
		return back()->with('error', 'Data Alternatif gagal dihapus');
	}
}
