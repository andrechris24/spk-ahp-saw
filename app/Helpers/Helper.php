<?php
use Illuminate\Support\Facades\DB;
use App\Models\Kriteria;
function getCriteriaComp($sel1,$sel2){
	$kriteria1=getCriteriaID($sel1);
	$kriteria2=getCriteriaID($sel2);
	$list=DB::table('kriteria_banding')->select('nilai')
	->where('kriteria1','=',$kriteria1)
	->where('kriteria2','=',$kriteria2)->get();
	$total=DB::table('kriteria_banding')->where('kriteria1','=',$kriteria1)
	->where('kriteria2','=',$kriteria2)->count();
	if($total==0) $nilai=1;
	else{
		foreach($list as $baris) $nilai=$baris->nilai;
	}
	return $nilai;
}
function getCriteriaID($urut){
	$daftar=DB::table('kriteria')->select('id')->get();
	if($daftar){
		foreach($daftar as $lists) $iddaftar[]=$lists->id;
		return $iddaftar[($urut)];
	}
	return null;
}
function inputCriteriaComp($sel1,$sel2){
	$kriteria1=getCriteriaID($sel1);
	$kriteria2=getCriteriaID($sel2);
	$total=DB::table('kriteria_banding')->where('kriteria1','=',$kriteria1)
	->where('kriteria2','=',$kriteria2)->count();
	if($total==0){
		
	}
}
