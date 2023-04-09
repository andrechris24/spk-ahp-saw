<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaComp extends Model
{
	use HasFactory;
	protected $table = "kriteria_banding";
	protected $fillable = [
		"kriteria1",
		"kriteria2",
		"nilai",
	];
	public static array $rules = [
		'baris' => 'required',
		'baris.*' => 'numeric|between:1,9',
		'kolom' => 'required',
		'kolom.*' => 'numeric|between:1,9',
	];
	public static array $message = [
		'baris.required'=>'Semua nilai perbandingan harus diisi',
		'baris.numeric' => 'Nilai perbandingan harus berupa angka',
		'baris.between' => 'Nilai perbandingan harus diantara :min sampai :max sesuai teori AHP',
		'kolom.required'=>'Semua nilai perbandingan harus diisi',
		'kolom.numeric' => 'Nilai perbandingan harus berupa angka',
		'kolom.between' => 'Nilai perbandingan harus diantara :min sampai :max sesuai teori AHP',
	];
}
