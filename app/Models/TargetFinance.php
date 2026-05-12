<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetFinance extends Model
{
    use HasFactory;

    protected $table = 'finance_targets';

    protected $fillable = [
        'tahun',
        'target_tahun',
        'target_bulan',
        'target_hari',
    ];
}
