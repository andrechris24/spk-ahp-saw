<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaComp extends Model
{
	use HasFactory;
	protected $table = "kriteria_banding";
	protected $fillable = ["kriteria1", "kriteria2", "nilai"];
	public static array $rules = [
		'kriteria' => 'required',
		'kriteria.*' => 'required',
		'skala' => 'required',
		'skala.*' => 'numeric|between:1,9'
	];
	public static array $message = [
		'kriteria.required' => 'Perbandingan tidak lengkap',
		'skala.required' => 'Semua nilai perbandingan harus diisi',
		'skala.numeric' => 'Nilai perbandingan harus berupa angka',
		'skala.between' => 'Nilai perbandingan harus diantara :min sampai :max sesuai teori AHP'
	];
	public static array $ratio_index = [
		1 => 0,
		2 => 0,
		3 => 0.58,
		4 => 0.9,
		5 => 1.12,
		6 => 1.24,
		7 => 1.32,
		8 => 1.41,
		9 => 1.45,
		10 => 1.49,
		11 => 1.51,
		12 => 1.48,
		13 => 1.56,
		14 => 1.57,
		15 => 1.59,
		16 => 1.605,
		17 => 1.61,
		18 => 1.615,
		19 => 1.62,
		20 => 1.625
	];
}