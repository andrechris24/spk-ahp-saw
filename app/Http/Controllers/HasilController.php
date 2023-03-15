<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\Alternatif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class HasilController extends Controller
{
    public function index()
    {
        $alt=Alternatif::get();
        $result=Hasil::get();
        return view('main.rank',compact('alt','result'));
    }
}
