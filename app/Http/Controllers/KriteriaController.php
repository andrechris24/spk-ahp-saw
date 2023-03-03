<?php

namespace App\Http\Controllers;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class KriteriaController extends Controller
{
	public function index()
	{
		$krit=Kriteria::get();
		return view('main.kriteria',compact('krit'));
	}
	public function bobot(){
		$total=DB::table('kriteria')->count();
		return view('main.kriteria-comp',compact('total'));
	}
	public function tambah(Request $kritrequest){
		$kritrequest->validate(Kriteria::$rules);
		$krits=$kritrequest->all();
		$kriteria=Kriteria::create($krits);
		if($kriteria) return back()->with('success','Kriteria sudah ditambahkan');
		return back()->with('error','Gagal menambah kriteria');
	}
	public function update(Request $updkritrequest,$id){
		$cek=Kriteria::find($id);
		if(!$cek) return back()->with('error','Data Kriteria tidak ditemukan');
		$req=$updkritrequest->all();
		$upd=$cek->update($req);
		if($upd) return back()->with('success','Data Kriteria sudah diupdate');
		return back()->with('error','Gagal mengupdate data kriteria');
	}
	public function hapus($id){
		$cek=Kriteria::find($id);
		if(!$cek) return back()->with('error','Data Kriteria tidak ditemukan');
		$del=$cek->delete();
		if($del) return back()->with('success','Data Kriteria sudah dihapus');
		return back()->with('error','Data kriteria gagal dihapus');
	}
}
