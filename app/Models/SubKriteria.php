<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    use HasFactory;
    // protected $appends=['kriteria'];
    protected $table = 'subkriteria';
    protected $fillable = ['name', 'kriteria_id'];
    public static $rules = [
        'name' => 'required',
        'kriteria_id' => 'bail|required|integer',
    ];
    public static $message = [
        'name.required' => 'Nama sub kriteria harus diisi',
        'kriteria_id.required' => 'Kriteria harus dipilih',
        'kriteria_id.integer' => 'Kriteria tidak valid',
    ];
    public function kriteria(){
        return $this->belongsTo(Kriteria::class,'kriteria_id');
    }
}
