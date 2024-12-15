<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Santri extends Model
{
    protected $table = 'santri';

    protected $fillable = [
        'nisn',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'wali_id',
        'tanggal_masuk',
        'jenjang',
        'kelas',
        'kategori_id',
        'status'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'status' => 'string'
    ];

    public function wali(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wali_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriSantri::class, 'kategori_id');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(PembayaranSpp::class, 'santri_id');
    }
}
