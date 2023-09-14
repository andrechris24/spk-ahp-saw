<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\Kriteria;
use App\Models\Alternatif;
use App\Models\Nilai;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class HasilController extends Controller
{
	public function index()
	{
		$result = Hasil::get();
		if (count($result) === 0) {
			return redirect('alternatif/nilai')->withWarning(
				'Hasil penilaian kosong, pastikan nilai alternatif sudah diisi, ' .
				'lalu klik "Lihat Hasil"'
			);
		}
		$highest = Hasil::orderBy('skor', 'desc')->first();
		return view('main.rank', compact('result', 'highest'));
	}
	public function resultDataTables(Request $request)
	{
		$allcriterias = Kriteria::get();
		$columns = [
			1 => 'alternatif.id',
			2 => 'alternatif.name',
		];
		foreach ($allcriterias as $krit) {
			$columns[$krit->id + 2] = Str::slug($krit->name, '-');
		}
		$columns[] = 'skor';
		// dd(array_key_last($columns));
		$search = [];
		$totalAlternatif = Alternatif::get();
		$totalData = $totalFiltered = 0;
		foreach ($totalAlternatif as $alter) {
			$totalData += Nilai::leftJoin(
				'alternatif',
				'alternatif.id',
				'=',
				'nilai.alternatif_id'
			)->leftJoin('kriteria', 'kriteria.id', '=', 'nilai.kriteria_id')
				->leftJoin('subkriteria', 'subkriteria.id', '=', 'nilai.subkriteria_id')
				->where('alternatif_id', $alter->id)
				->count();
		}

		$totalFiltered = $totalData + Alternatif::count();

		$limit = $request->input('length') ?? 10;
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column') ?? array_key_last($columns)];
		$dir = $request->input('order.0.dir') ?? 'asc';

		if (empty($request->input('search.value'))) {
			$alter = Nilai::leftJoin(
				'alternatif',
				'alternatif.id',
				'=',
				'nilai.alternatif_id'
			)->leftJoin('kriteria', 'kriteria.id', '=', 'nilai.kriteria_id')
				->leftJoin('subkriteria', 'subkriteria.id', '=', 'nilai.subkriteria_id')
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();
		} else {
			$search = $request->input('search.value');

			$alter = Nilai::leftJoin(
				'alternatif',
				'alternatif.id',
				'=',
				'nilai.alternatif_id'
			)->leftJoin('kriteria', 'kriteria.id', '=', 'nilai.kriteria_id')
				->leftJoin('subkriteria', 'subkriteria.id', '=', 'nilai.subkriteria_id')
				->where('alternatif.name', 'LIKE', "%{$search}%")
				->orWhere('kriteria.name', 'LIKE', "%{$search}%")
				->orWhere('subkriteria.name', 'LIKE', "%{$search}%")
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();

			$totalFiltered = Nilai::leftJoin(
				'alternatif',
				'alternatif.id',
				'=',
				'nilai.alternatif_id'
			)->leftJoin('kriteria', 'kriteria.id', '=', 'nilai.kriteria_id')
				->leftJoin('subkriteria', 'subkriteria.id', '=', 'nilai.subkriteria_id')
				->where('id', 'LIKE', "%{$search}%")
				->orWhere('alternatif.name', 'LIKE', "%{$search}%")
				->orWhere('kriteria.name', 'LIKE', "%{$search}%")
				->orWhere('subkriteria.name', 'LIKE', "%{$search}%")
				->count();
		}

		$data = [];

		if (!empty($alter)) {
			// providing a dummy id instead of database ids
			$ids = $start;

			foreach ($alter as $alternative) {
				$nestedData['id'] = $alternative->id;
				$nestedData['fake_id'] = ++$ids;
				$nestedData['name'] = $alternative->name;
				foreach ($allcriterias as $krit) {
					$nestedData[Str::slug($krit->name, '-')];
				}
				$data[] = $nestedData;
			}
		}

		if ($data) {
			return response()->json([
				'draw' => intval($request->input('draw')),
				'recordsTotal' => intval($totalData),
				'recordsFiltered' => intval($totalFiltered),
				'data' => $data,
			], 200);
		} else {
			return response()->json([
				'message' => 'Internal Server Error',
				'data' => [],
			], 500);
		}
	}
}