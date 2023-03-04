<?php

namespace App\Http\Controllers;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class KriteriaCompController extends Controller
{
	public function bobot(){
		$crit=Kriteria::get();
		$total=DB::table('kriteria')->count();
		return view('main.kriteria-comp',compact('total','crit'));
	}
	public function hitung(Request $request){
		$n=DB::table('kriteria')->count();
		$matriks=array();
		$urutan=0;
		for($a=0;$a<($n-1);$a++){
			for($b=($a+1);$b<$n;$b++){
				//
			}
		}
	}
}
