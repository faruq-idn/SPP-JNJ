<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'status',
        'kategori_id'
    ];

    public function wali(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wali_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriSantri::class, 'kategori_id');
    }
}
