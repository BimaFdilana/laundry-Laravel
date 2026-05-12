<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TransaksiSatuanDetail extends Model
{
    use Notifiable;

    protected $fillable = [
        'transaksi_satuan_id',
        'satuan_id',
        'pcs',
        'hari',
        'harga',
        'subtotal',
    ];

    // Relasi ke TransaksiSatuan
    public function transaksiSatuan()
    {
        return $this->belongsTo(TransaksiSatuan::class, 'transaksi_satuan_id');
    }

    // Relasi ke Satuan
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
