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
    public static $rules = [
        'kriteria_id'=>'required|numeric',
        'baris' => 'required',
        'kolom' => 'required',
        'baris.*' => 'numeric|between:1,9',
        'kolom.*' => 'numeric|between:1,9'
    ];
    public static $message = [
        'kriteria_id.required'=>'Kriteria tidak ditemukan',
        'kriteria_id.numeric'=>'Kriteria tidak valid',
        'baris.numeric' => 'Nilai baris harus berupa angka',
        'kolom.numeric' => 'Nilai kolom harus berupa angka',
        'baris.between' => 'Nilai baris harus diantara :min sampai :max sesuai teori AHP',
        'kolom.between' => 'Nilai kolom harus diantara :min sampai :max sesuai teori AHP'
    ];
}
