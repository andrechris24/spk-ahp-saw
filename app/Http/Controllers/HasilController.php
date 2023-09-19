<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class HasilController extends Controller
{
	public function index()
	{
		try {
			$result = Hasil::get();
			if (count($result) === 0) {
				return redirect('alternatif/nilai')->withWarning(
					'Hasil penilaian kosong, pastikan nilai alternatif sudah diisi, ' .
					'lalu klik "Lihat Hasil"'
				);
			}
			$highest = Hasil::orderBy('skor', 'desc')->first();
			return view('main.rank', compact('result', 'highest'));
		} catch (QueryException $e) {
			Log::error($e);
			return back()->withError('Gagal memuat grafik hasil penilaian')
			->withErrors($e->getMessage());
		}
	}
}