<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'auth',
        'alamat',
        'no_telp',
        'kelamin',
        'theme'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function kuotaLaundry()
    {
        return $this->hasMany(KuotaLaundry::class, 'user_id');
    }

    public function gifts()
    {
        return $this->hasMany(Gift::class);
    }

    public function routeNotificationForWablas()
    {
        return $this->no_telp; // atau sesuaikan dengan atribut nomor WA yang kamu simpan
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
}
