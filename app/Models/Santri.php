<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\KategoriSantri;
use App\Models\PembayaranSpp;

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
        'nama_wali',
        'tanggal_masuk',
        'jenjang',
        'kelas',
        'kategori_id',
        'status',
        'status_spp'
    ];

    protected $nullable = [
        'wali_id',
        'nama_wali'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'nisn' => 'string',
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
        return $this->hasMany(PembayaranSpp::class);
    }

}
