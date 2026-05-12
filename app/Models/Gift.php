<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'user_id',
        'gift',
        'keterangan',
        'expired_at',
        'is_read',
    ];

    // Tipe data untuk casting otomatis
    protected $casts = [
        'expired_at' => 'datetime',
    ];

    // Relasi: Gift dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
