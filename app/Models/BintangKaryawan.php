<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BintangKaryawan extends Model
{
    use HasFactory;

    protected $fillable = ['karyawan_id', 'bintang', 'tanggal'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
