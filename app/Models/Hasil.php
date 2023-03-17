<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
    use HasFactory;
    protected $table = 'hasil';
    protected $fillable = ['alternatif_id', 'skor'];
    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'alternatif_id');
    }
}
