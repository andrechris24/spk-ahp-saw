<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Hasil;
use App\Models\Alternatif;

class HasilController extends Controller
{
	public function index()
	{
		$result = Hasil::get();
		if (count($result) == 0) {
			return redirect('alternatif/nilai')
				->withWarning(
					'Nilai Alternatif kosong, mohon untuk diisi dulu lalu klik \'Lihat Hasil\''
				);
		}
		$alt = Alternatif::get();
		$highest = Hasil::orderBy('skor', 'desc')->first();
		return view('main.rank', compact('alt', 'result', 'highest'));
	}
	public function test()
	{
		dd(csrf_token());
		return view('welcome');
	}
}
