<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKriteriaComp extends Model
{
	use HasFactory;
	protected $table = "subkriteria_banding";
	protected $fillable = ["idkriteria", "subkriteria1", "subkriteria2", "nilai"];
	public static array $selectrules = ['kriteria_id' => 'bail|required|integer'],
	$selectmessage = [
		'kriteria_id.required' => 'Kriteria harus dipilih',
		'kriteria_id.integer' => 'Kriteria tidak valid'
	], $rules = [
		'skala' => 'bail|required|array',
		'skala.*' => 'bail|required|numeric|between:1,9'
	], $message = [
		'skala.*.required' => 'Nilai perbandingan :attr harus diisi',
		'skala.*.integer' => 'Nilai perbandingan harus berupa angka',
		'skala.*.between' => 'Nilai perbandingan harus diantara :min sampai :max sesuai teori AHP'
	];
}