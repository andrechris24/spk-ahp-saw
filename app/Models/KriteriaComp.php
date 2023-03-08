<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaComp extends Model
{
    use HasFactory;
    protected $table = "kriteria_banding";
    protected $fillable = [
        "kriteria1",
        "kriteria2",
        "nilai",
    ];
    public static $rules = [
        'baris' => 'required',
        'kolom' => 'required',
        'baris.*'=>'numeric|between:1,9',
        'kolom.*' => 'numeric|between:1,9'
    ];
    public static $message = [
        'baris.numeric' => 'Nilai baris harus berupa angka',
        'kolom.numeric' => 'Nilai kolom harus berupa angka',
        'baris.between'=>'Nilai baris harus diantara :min sampai :max sesuai teori AHP',
        'kolom.between'=>'Nilai kolom harus diantara :min sampai :max sesuai teori AHP'
    ];
}
