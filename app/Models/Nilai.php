<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
	use HasFactory;
	protected $table = "nilai";
	protected $fillable = ['alternatif_id', 'kriteria_id', 'subkriteria_id'];
	public static array $rules = [
		'alternatif_id' => 'bail|required|integer',
		'kriteria_id' => 'required',
		'kriteria_id.*' => 'bail|required|integer',
		'subkriteria_id' => 'required',
		'subkriteria_id.*' => 'bail|required|integer'
	], $message = [
		'alternatif_id.required' => 'Alternatif harus dipilih',
		'alternatif_id.integer' => 'Alternatif tidak valid',
		'kriteria_id.*.required' => 'Kriteria tidak ditemukan',
		'kriteria_id.*.integer' => 'Kriteria tidak valid',
		'subkriteria_id.*.required' => 'Sub kriteria :attr harus dipilih',
		'subkriteria_id.*.integer' => 'Sub kriteria :attr tidak valid'
	];
	public function alternatif()
	{
		return $this->belongsTo(Alternatif::class, 'alternatif_id');
	}
	public function kriteria()
	{
		return $this->belongsTo(Kriteria::class, 'kriteria_id');
	}
	public function subkriteria()
	{
		return $this->belongsTo(SubKriteria::class, 'subkriteria_id');
	}
}