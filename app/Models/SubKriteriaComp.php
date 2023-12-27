<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKriteriaComp extends Model
{
	use HasFactory;
	protected $table = "subkriteria_banding",
	$fillable = ["idkriteria", "subkriteria1", "subkriteria2", "nilai"];
	public static array $rules = [
		'skala' => ['bail', 'required', 'array'],
		'skala.*' => ['bail', 'required', 'numeric', 'between:1,9']
	], $message = [
		'skala.*.required' => 'Nilai perbandingan :attr harus diisi',
		'skala.*.integer' => 'Nilai perbandingan harus berupa angka',
		'skala.*.between' => 'Nilai perbandingan harus diantara :min sampai :max sesuai teori AHP'
	];
	public function kriteria()
	{
		return $this->belongsTo(Kriteria::class, 'idkriteria');
	}
}