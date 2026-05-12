<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TransaksiSatuan extends Model
{
    use Notifiable;

    protected $fillable = [
        'invoice',
        'karyawan_id',
        'customer_id',
        'customer',
        'tgl_transaksi',
        'email_customer',
        'status_order',
        'status_payment',
        'disc',
        'harga_akhir',
        'jenis_pembayaran',
        'tgl',
        'bulan',
        'tahun',
        'catatan_admin',
        'jenis_pewangi',
        'ket_delivery',
        'tgl_ambil',
        'info_pembayaran',
    ];

    // Relasi ke model Harga
    public function price()
    {
        return $this->belongsTo(Harga::class, 'harga_id', 'id');
    }

    // Relasi ke model User
    public function customers()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    // Relasi ke model Karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    // Relasi ke model Transaksi Satuan Detail
    public function details()
    {
        return $this->hasMany(TransaksiSatuanDetail::class, 'transaksi_satuan_id');
    }
}
