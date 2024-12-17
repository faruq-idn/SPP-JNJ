<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranSpp extends Model
{
    protected $table = 'pembayaran_spp';

    protected $fillable = [
        'santri_id',
        'bulan',
        'nominal',
        'metode_pembayaran_id',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function metode_pembayaran(): BelongsTo
    {
        return $this->belongsTo(MetodePembayaran::class);
    }
}
