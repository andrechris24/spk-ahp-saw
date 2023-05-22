<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Nilai;
use App\Models\SubKriteria;
use App\Models\SubKriteriaComp;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SubKriteriaController extends Controller
{
	public function index()
	{
		$kriteria = Kriteria::get();
		$subkriteria = SubKriteria::get();
		$compskr = SubKriteriaComp::count();
		$ceknilai = Nilai::count();
		if (count($kriteria) == 0) {
			return redirect('kriteria')
				->withWarning('Tambahkan kriteria dulu sebelum menambah sub kriteria');
		}
		return view(
			'main.subkriteria.index',
			compact('kriteria', 'subkriteria', 'compskr', 'ceknilai')
		);
	}

	public function store(Request $request)
	{
		$request->validate(SubKriteria::$rules, SubKriteria::$message);
		$subs = $request->all();
		try {
			$subkriteria = SubKriteria::create($subs);
			$namakriteria = $subkriteria->kriteria->name;
			if ($subkriteria) {
				$cek = SubKriteriaComp::where('idkriteria', '=', $request->kriteria_id)
					->count();
				if ($cek > 0) {
					SubKriteriaComp::where('idkriteria', '=', $request->kriteria_id)
						->delete();
					SubKriteria::where('kriteria_id', $request->kriteria_id)
						->update(['bobot', 0.0000]);
					return back()
						->withSuccess(
							'Sub kriteria sudah ditambahkan. ' .
							'Silahkan input ulang perbandingan sub kriteria ' . $namakriteria . '.'
						);
				}
				return back()->withSuccess('Sub kriteria sudah ditambahkan');
			}
		} catch (QueryException $sql) {
			return back()->withError('Gagal menambah sub kriteria')
				->withErrors($sql->getMessage());
		}
		return back()->withError('Gagal menambah sub kriteria');
	}

	public function update(Request $request, $id)
	{
		try {
			$cek = SubKriteria::find($id);
			if (!$cek) {
				return back()->withError('Data Sub kriteria tidak ditemukan');
			}

			$req = $request->all();
			$cek->update($req);
			return back()->withSuccess('Data Sub kriteria sudah diupdate');
		} catch (QueryException $sql) {
			return back()->withError('Gagal mengupdate data sub kriteria')
				->withErrors($sql->getMessage());
		}
	}

	public function destroy($id)
	{
		try {
			$cek = SubKriteria::find($id);
			$idkriteria = $cek->kriteria_id;
			$namakriteria = $cek->kriteria->name;
			$getalt = Nilai::where('kriteria_id', $id)->first();
			if (!$cek) {
				return back()->withError('Data Sub kriteria tidak ditemukan');
			}

			$cek->delete();
			Nilai::where('alternatif_id', $getalt->alternatif_id)->delete();
			$subkrcomp = SubKriteriaComp::where('idkriteria', $cek->kriteria_id);
			if ($subkrcomp->count() > 0) {
				$subkrcomp->delete();
				SubKriteria::where('kriteria_id', $idkriteria)->update(['bobot' => 0.0000]);
				return back()->withSuccess(
					'Data Sub kriteria sudah dihapus. ' .
					'Silahkan input ulang perbandingan sub kriteria ' . $namakriteria . '.'
				);
			}
			return back()->withSuccess('Data Sub kriteria sudah dihapus');
		} catch (QueryException $sql) {
			return back()->withError('Gagal hapus data sub kriteria')
				->withErrors($sql->getMessage());
		}
	}
}