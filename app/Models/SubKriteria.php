<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
	use HasFactory;
	protected $table = 'subkriteria', $fillable = ['name', 'kriteria_id'];
	public static array $rules = [
		'name' => 'required',
		'kriteria_id' => ['bail', 'required', 'integer', 'exists:kriteria,id']
	], $message = [
		'name.required' => 'Nama sub kriteria harus diisi',
		'kriteria_id.required' => 'Kriteria harus dipilih',
		'kriteria_id.integer' => 'Kriteria tidak valid',
		'kriteria_id.exists' => 'Kriteria tidak ditemukan'];
	public function kriteria()
	{
		return $this->belongsTo(Kriteria::class, 'kriteria_id');
	}
	public function nilai()
	{
		return $this->hasOne(Nilai::class, 'subkriteria_id');
	}
	public function subkriteriacomp()
	{
		return $this->hasMany(SubKriteriaComp::class);
	}
}