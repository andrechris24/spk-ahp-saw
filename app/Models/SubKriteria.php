<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubKriteria extends Model
{
	use HasFactory;
	protected $table = 'subkriteria';
	protected $fillable = ['name', 'kriteria_id'];
	public static array $rules = [
		'name' => 'required',
		'kriteria_id' => 'bail|required|integer|exists:kriteria,id'
	], $message = [
		'name.required' => 'Nama sub kriteria harus diisi',
		'kriteria_id.required' => 'Kriteria harus dipilih',
		'kriteria_id.integer' => 'Kriteria tidak valid',
		'kriteria_id.exists' => 'Kriteria tidak ditemukan'
	];
	public function kriteria(): BelongsTo
	{
		return $this->belongsTo(Kriteria::class, 'kriteria_id');
	}
	public function nilai(): HasMany
	{
		return $this->hasMany(Nilai::class, 'subkriteria_id');
	}
}