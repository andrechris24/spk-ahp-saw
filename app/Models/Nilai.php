<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
	use HasFactory;
	protected $table = "nilai";
	protected $fillable = ['alternatif_id', 'kriteria_id', 'subkriteria_id'];
	public static array $rules = [
		'alternatif_id' => 'required',
		'kriteria_id' => 'required',
		'kriteria_id.*' => 'integer',
		'subkriteria_id' => 'required',
		'subkriteria_id.*' => 'integer'
	];
	public static array $message = [
		'alternatif_id.required' => 'Nama alternatif harus dipilih',
		'kriteria_id.required' => 'Kriteria tidak ditemukan',
		'subkriteria_id.required' => 'Semua sub kriteria harus dipilih',
		'kriteria_id.integer' => 'Kriteria tidak valid',
		'subkriteria_id.integer' => 'Sub kriteria tidak valid'
	];
	public function alternatif(): BelongsTo
	{
		return $this->belongsTo(Alternatif::class, 'alternatif_id');
	}
	public function kriteria(): BelongsTo
	{
		return $this->belongsTo(Kriteria::class, 'kriteria_id');
	}
	public function subkriteria(): BelongsTo
	{
		return $this->belongsTo(SubKriteria::class, 'subkriteria_id');
	}
}