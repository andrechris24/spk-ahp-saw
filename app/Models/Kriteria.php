<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;
    protected $table = 'kriteria';
    protected $fillable = ['name', 'type', 'desc'];
    public static $rules = [
        'name' => 'required',
        'type' => 'bail|required|in:cost,benefit',
        'desc' => 'required'
    ];
    public static $message = [
        'name.required' => 'Nama kriteria harus diisi',
        'type.required' => 'Tipe Kriteria harus dipilih',
        'desc.required' => 'Keterangan kriteria harus diisi',
    ];
    public function subkriteria()
    {
        return $this->hasMany(SubKriteria::class, 'kriteria_id');
    }
}
