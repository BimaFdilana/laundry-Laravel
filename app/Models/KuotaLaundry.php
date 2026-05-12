<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuotaLaundry extends Model
{
    use HasFactory;

    protected $table = 'kuota_laundry';
    protected $fillable = ['user_id', 'kategori', 'kuota'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
