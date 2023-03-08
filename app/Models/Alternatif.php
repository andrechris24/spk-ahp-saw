<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    use HasFactory;
    protected $table = 'alternatif';
    protected $fillable = ['name'];
    public static $rules = ['name' => 'required|regex:/^[\s\w-]*$/'];
    public static $message = [
        'name.required' => 'Nama alternatif diperlukan',
        'name.regex' => 'Nama alternatif tidak boleh mengandung simbol'
    ];
}
