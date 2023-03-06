<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;
    protected $table='kriteria';
    protected $fillable=['name','type'];
    public static $rules=[
        'name'=>'required',
        'type'=>'bail|required|in:cost,benefit'
    ];
    public static $message=[
        'name.required'=>'Nama kriteria diperlukan',
        'type.required'=>'Tipe Kriteria harus dipilih'
    ];
}
