<?php

namespace App\Http\Controllers;

use App\Models\Hasil;

class HasilController extends Controller
{
	public function index()
	{
		// $result = Hasil::get();
		if (Hasil::isEmpty()) {
			return redirect('alternatif/nilai')
				->withWarning(
					'Hasil penilaian kosong, pastikan nilai alternatif sudah diisi, lalu klik "Lihat Hasil"'
				);
		}
		$highest = Hasil::orderBy('skor', 'desc')->first();
		return view('main.rank', compact('result', 'highest'));
	}
}