<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranSpp extends Model
{
    protected $table = 'pembayaran_spp';

    protected $fillable = [
        'santri_id',
        'tanggal_bayar',
        'bulan',
        'tahun',
        'nominal',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status',
        'keterangan',
        'petugas_id'
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
