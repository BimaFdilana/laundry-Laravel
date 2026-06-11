<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardKaryawan extends Model
{
    use HasFactory;

    protected $table = 'reward_karyawans';

    protected $fillable = [
        'karyawan_id',
        'jenis_reward',
        'nominal',
        'keterangan',
        'tanggal',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}
