<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivitasKaryawan extends Model
{
    use HasFactory;

    protected $table = 'aktivitas_karyawans';

    protected $fillable = [
        'transaksi_id',
        'transaksi_satuan_id',
        'karyawan_id',
        'jenis_aktivitas',
        'jumlah_item',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function transaksiSatuan()
    {
        return $this->belongsTo(TransaksiSatuan::class, 'transaksi_satuan_id');
    }
}
