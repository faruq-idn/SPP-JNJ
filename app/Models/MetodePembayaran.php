<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetodePembayaran extends Model
{
    protected $table = 'metode_pembayaran';

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'status'
    ];

    public function pembayaran(): HasMany
    {
        return $this->hasMany(PembayaranSpp::class);
    }
}
