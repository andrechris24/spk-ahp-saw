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
		'banding' => 'required',
		'banding.*' => 'numeric|between:-9,9',
	];
	public static array $message = [
		'kriteria_id.required' => 'Kriteria tidak ditemukan',
		'kriteria_id.numeric' => 'Kriteria tidak valid',
		'banding.numeric' => 'Nilai perbandingan harus berupa angka',
		'banding.between' => 'Nilai perbandingan harus diantara :min sampai :max sesuai teori AHP',
	];
}
