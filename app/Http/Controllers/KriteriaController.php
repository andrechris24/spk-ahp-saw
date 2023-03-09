<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\KriteriaComp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KriteriaController extends Controller
{
	public function index()
	{
		$krit = Kriteria::get();
		return view('main.kriteria.index', compact('krit'));
	}
	public function tambah(Request $kritrequest)
	{
		$kritrequest->validate(Kriteria::$rules, Kriteria::$message);
		$krits = $kritrequest->all();
		$kriteria = Kriteria::create($krits);
		if ($kriteria){
			$cekhasil=KriteriaComp::count();
			if($cekhasil>0){
				KriteriaComp::delete();
				return back()
				->with('success','Kriteria sudah ditambahkan. Silahkan input ulang perbandingan.');
			}
			return back()->with('success', 'Kriteria sudah ditambahkan.');
		}
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
		if ($del){
			$cekhasil=KriteriaComp::count();
			if($cekhasil>0){
				KriteriaComp::delete();
				return back()
				->with('success', 'Data Kriteria sudah dihapus. Silahkan input ulang perbandingan.');
			}
			return back()->with('success', 'Data Kriteria sudah dihapus');
		}
		return back()->with('error', 'Data kriteria gagal dihapus');
	}
}
