<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\Alternatif;

class HasilController extends Controller
{
    public function index()
    {
        $alt = Alternatif::get();
        $result = Hasil::get();
        if(count($result)==0)
            return redirect('alternatif/nilai')->withWarning('Nilai Alternatif kosong');
        $highest = Hasil::orderBy('skor', 'desc')->first();
        return view('main.rank', compact('alt', 'result', 'highest'));
    }
}
