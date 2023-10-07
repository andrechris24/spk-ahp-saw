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
		'skala' => 'bail|required|array',
		'skala.*' => 'bail|required|integer|between:1,9'
	];
	public static array $message = [
		'skala.required' => 'Semua nilai perbandingan harus diisi',
		'skala.integer' => 'Nilai perbandingan harus berupa angka',
		'skala.between' => 'Nilai perbandingan harus diantara :min sampai :max sesuai teori AHP'
	];
}