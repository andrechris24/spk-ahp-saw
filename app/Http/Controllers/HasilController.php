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
        $highest = Hasil::orderBy('hasil', 'desc')->first();
        return view('main.rank', compact('alt', 'result', 'highest'));
    }
}
