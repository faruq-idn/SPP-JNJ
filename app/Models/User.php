<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke santri (untuk wali)
    public function santri()
    {
        return $this->hasMany(Santri::class, 'wali_id');
    }

    // Relasi ke pembayaran (untuk petugas)
    public function pembayaran()
    {
        return $this->hasMany(PembayaranSpp::class, 'petugas_id');
    }

    // Assign role saat create/update
    protected static function booted()
    {
        static::created(function ($user) {
            $user->assignRole($user->role);
        });

        static::updated(function ($user) {
            if ($user->isDirty('role')) {
                $user->syncRoles([$user->role]);
            }
        });
    }
}
