<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKriteriaComp extends Model
{
	use HasFactory;
	protected $table = "subkriteria_banding";
	protected $fillable = [
		'idkriteria',
		"subkriteria1",
		"subkriteria2",
		"nilai",
	];
	public static array $rules = [
		'kriteria_id' => 'required|numeric',
		'baris' => 'required',
		'baris.*' => 'numeric|between:1,9',
		'kolom' => 'required',
		'kolom.*' => 'numeric|between:1,9',
	];
	public static array $message = [
		'kriteria_id.required' => 'Kriteria tidak ditemukan',
		'kriteria_id.numeric' => 'Kriteria tidak valid',
		'baris.required'=>'Semua nilai perbandingan harus diisi',
		'baris.numeric' => 'Nilai perbandingan harus berupa angka',
		'baris.between' => 'Nilai perbandingan harus diantara :min sampai :max sesuai teori AHP',
		'kolom.required'=>'Semua nilai perbandingan harus diisi',
		'kolom.numeric' => 'Nilai perbandingan harus berupa angka',
		'kolom.between' => 'Nilai perbandingan harus diantara :min sampai :max sesuai teori AHP',
	];
}
