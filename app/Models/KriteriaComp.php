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
        'pilihan' => 'required',
        'bobot' => 'required',
        'bobot.*'=>'numeric'
    ];
    public static $message=[
        'bobot.numeric'=>'Nilai :attribute harus berupa angka'
    ];
}
