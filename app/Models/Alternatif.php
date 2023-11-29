<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alternatif extends Model
{
	use HasFactory;
	protected $table = 'alternatif';
	protected $fillable = ['name'];
	public static array $rules = ['name' => 'bail|required|regex:/^[\s\w-]*$/'],
	$message = [
		'name.required' => 'Nama alternatif diperlukan',
		'name.regex' => 'Nama alternatif tidak boleh mengandung simbol'
	];
	public function nilai(): HasMany
	{
		return $this->hasMany(Nilai::class, 'alternatif_id');
	}
	public function hasil(): HasMany
	{
		return $this->hasMany(Hasil::class, 'alternatif_id');
	}
}