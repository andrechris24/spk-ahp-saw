<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class HasilController extends Controller
{
    public function normalisasi()
    {
        try {
            DB::table('unknown')->truncate();
        } catch (QueryException $e) {
            return back()->withError($e->getMessage());
            // dd($e->getMessage());
        }
    }
}
