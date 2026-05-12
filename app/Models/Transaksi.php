<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Transaksi extends Model
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
        'harga_id',
        'kg',
        'jumlah_lembar_baju',
        'hari',
        'harga',
        'disc',
        'harga_akhir',
        'jenis_pembayaran',
        'tgl',
        'bulan',
        'tahun',
        'tgl_ambil',
        'catatan_admin',
        'jenis_pewangi',
        'ket_delivery',
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
}
