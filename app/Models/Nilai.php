<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;
    protected $table="nilai";
    protected $fillable=['alternatif_id','kriteria_id','subkriteria_id'];
    public static $rules = [
        'alternatif_id' => 'bail|required|unique:nilai,alternatif_id',
        'kriteria_id'=>'required',
        'kriteria_id.*'=>'numeric',
        'subkriteria_id'=>'required',
        'subkriteria_id.*'=>'numeric'
    ];
    public static $updrules = [
        'alternatif_id' => 'bail|required',
        'kriteria_id'=>'required',
        'kriteria_id.*'=>'numeric',
        'subkriteria_id'=>'required',
        'subkriteria_id.*'=>'numeric'
    ];
    public static $message=[
        'alternatif_id.required'=>'Nama alternatif harus dipilih',
        'alternatif_id.unique'=>'Nama alternatif sudah digunakan',
        'kriteria_id.required'=>'Kriteria tidak ditemukan',
        'subkriteria_id.required'=>'Semua sub kriteria harus dipilih',
        'kriteria_id.numeric'=>'Kriteria tidak valid',
        'subkriteria_id.numeric'=>'Sub Kriteria tidak valid'
    ];
    public function alternatif(){
        return $this->belongsTo(Alternatif::class,'alternatif_id');
    }
    public function kriteria(){
        return $this->belongsTo(Kriteria::class,'kriteria_id');
    }
    public function subkriteria(){
        return $this->belongsTo(SubKriteria::class,'subkriteria_id');
    }
}
