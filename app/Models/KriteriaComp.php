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
		'banding' => 'required',
		'banding.*' => 'numeric|between:-9,9',
	];
	public static array $message = [
		'banding.numeric' => 'Nilai perbandingan harus berupa angka',
		'banding.between' => 'Nilai perbandingan harus diantara :min sampai :max sesuai teori AHP',
	];
}
