<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatTarifSpp extends Model
{
    protected $table = 'riwayat_tarif_spp';

    protected $fillable = [
        'kategori_id',
        'nominal',
        'berlaku_mulai',
        'berlaku_sampai',
        'keterangan'
    ];

    protected $casts = [
        'berlaku_mulai' => 'datetime',
        'berlaku_sampai' => 'datetime',
        'nominal' => 'decimal:2'
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriSantri::class, 'kategori_id');
    }

    public function scopeAktif($query)
    {
        return $query->whereNull('berlaku_sampai');
    }
}
