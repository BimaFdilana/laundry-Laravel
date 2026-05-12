<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;
    protected $table = 'pengeluarans';
    protected $fillable = [
        'pengeluaran',
        'kategori',
        'harga',
        'jumlah',
        'total',
        'keterangan',
        'tanggal',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->isDirty('total')) {
                $model->total = $model->harga * $model->jumlah;
            }
        });
    }
}
