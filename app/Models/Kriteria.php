<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;
    protected $table='kriteria';
    protected $fillable=['name','type'];
    protected $rules=[
        'name'=>'required',
        'type'=>'required|in:cost,benefit'
    ];
}
