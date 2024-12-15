<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriSantri extends Model
{
    protected $table = 'kategori_santri';

    protected $fillable = [
        'nama',
        'keterangan'
    ];

    public function riwayatTarif(): HasMany
    {
        return $this->hasMany(RiwayatTarifSpp::class, 'kategori_id');
    }

    public function tarifTerbaru()
    {
        return $this->hasOne(RiwayatTarifSpp::class, 'kategori_id')
            ->whereNull('berlaku_sampai')
            ->latest('berlaku_mulai');
    }

    public function santri(): HasMany
    {
        return $this->hasMany(Santri::class, 'kategori_id');
    }
}
