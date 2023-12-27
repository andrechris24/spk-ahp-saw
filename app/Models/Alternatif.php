<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
	use HasFactory;
	protected $table = 'alternatif', $fillable = ['name'];
	public static array $rules = [
		'name' => ['bail', 'required', 'regex:/^[\s\w-]*$/']
	], $message = [
		'name.required' => 'Nama alternatif diperlukan',
		'name.regex' => 'Nama alternatif tidak boleh mengandung simbol'];
	public function nilai()
	{
		return $this->hasOne(Nilai::class, 'alternatif_id');
	}
	public function hasil()
	{
		return $this->hasOne(Hasil::class, 'alternatif_id');
	}
}