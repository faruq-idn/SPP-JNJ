<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatTarifSpp extends Model
{
    protected $table = 'riwayat_tarif_spp';

    protected $fillable = [
        'kategori_id',
        'biaya_makan',
        'biaya_asrama',
        'biaya_listrik',
        'biaya_kesehatan',
        'nominal',
        'berlaku_mulai',
        'berlaku_sampai',
        'keterangan'
    ];

    protected $casts = [
        'berlaku_mulai' => 'datetime',
        'berlaku_sampai' => 'datetime',
        'biaya_makan' => 'decimal:2',
        'biaya_asrama' => 'decimal:2',
        'biaya_listrik' => 'decimal:2',
        'biaya_kesehatan' => 'decimal:2',
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
