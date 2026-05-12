<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jenis',
        'pcs',
        'harga',
        'status',
        'hari'
    ];

    protected $casts = [
        'harga' => 'float',  // atau 'integer' jika harga selalu integer
    ];
}
