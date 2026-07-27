<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuotaLaundryLog extends Model
{
    protected $table = 'kuota_laundry_logs';

    protected $fillable = [
        'user_id',
        'transaksi_id',
        'purchase_request_id',
        'kategori',
        'tipe',
        'kuota_sebelum',
        'perubahan',
        'kuota_sesudah',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'kuota_sebelum' => 'float',
        'perubahan'     => 'float',
        'kuota_sesudah' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}
