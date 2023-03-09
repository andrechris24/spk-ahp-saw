<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SubKriteriaComp;
use App\Models\SubKriteria;
use Illuminate\Http\Request;

class SubKriteriaCompController extends Controller
{
	public function nama_kriteria($id){
		$kriteria=Kriteria::where('id','=',$id)->first();
		return $kriteria['name'];
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$allkrit=Kriteria::get();
		if(count($allkrit)==0)
			return redirect('/kriteria')->with('warning','Masukkan kriteria dulu untuk melakukan perbandingan sub kriteria');
		$crit = SubKriteria::get();
		if(count($crit)==0)
			return redirect('/kriteria')->with('warning','Masukkan data sub kriteria dulu untuk melakukan perbandingan sub kriteria');
		return view('main.subkriteria.select',compact('allkrit'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		$validate=$request->validate([
			'kriteria_id'=>'required|numeric'
		]);
		$idkriteria=$request->kriteria_id;
		$subkriteria=SubKriteria::where('kriteria_id','=',$idkriteria)->get();
		$counter = 0;
		for ($a = 0; $a < count($subkriteria); $a++) {
			for ($b = 0; $b < count($subkriteria); $b++) {
				$array[$counter]["baris"] = $subkriteria[$a]->name;
				$array[$counter]["kolom"] = $subkriteria[$b]->name;
				$counter++;
			}
		}
		return view('main.subkriteria.comp',compact('array'))->with([
			'kriteria_id'=>$idkriteria
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\SubKriteriaComp  $subKriteriaComp
	 * @return \Illuminate\Http\Response
	 */
	public function show(SubKriteriaComp $subKriteriaComp)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\SubKriteriaComp  $subKriteriaComp
	 * @return \Illuminate\Http\Response
	 */
	public function edit(SubKriteriaComp $subKriteriaComp)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\SubKriteriaComp  $subKriteriaComp
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, SubKriteriaComp $subKriteriaComp)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\SubKriteriaComp  $subKriteriaComp
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(SubKriteriaComp $subKriteriaComp)
	{
		//
	}
}
