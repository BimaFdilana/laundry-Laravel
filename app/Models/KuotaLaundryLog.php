<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuotaLaundryLog extends Model
{
    use HasFactory;

    protected $table = 'kuota_laundry_logs';

    protected $fillable = [
        'user_id',
        'tipe',
        'jumlah',
        'kategori',
        'invoice',
        'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
