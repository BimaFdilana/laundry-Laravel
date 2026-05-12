<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    use HasFactory;
    protected $table = 'pemasukans';
    protected $fillable = [
        'pemasukan',
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

        static::creating(function ($model) {
            if (is_null($model->total)) {
                $model->total = $model->harga * $model->jumlah;
            }
        });

        static::updating(function ($model) {
            if (is_null($model->total)) {
                $model->total = $model->harga * $model->jumlah;
            }
        });
    }
}
