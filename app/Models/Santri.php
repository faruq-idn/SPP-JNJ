<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

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
        'kategori_id',
        'status_spp',
        'tahun_tamat'
    ];

    protected $casts = [
        'tanggal_lahir' => 'datetime',
        'tanggal_masuk' => 'datetime',
        'tahun_tamat' => 'integer'
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriSantri::class, 'kategori_id');
    }

    public function wali()
    {
        return $this->belongsTo(User::class, 'wali_id')->where('role', 'wali');
    }

    public function pembayaran()
    {
        return $this->hasMany(PembayaranSpp::class);
    }

    public function riwayatKenaikanKelas()
    {
        return $this->hasMany(KenaikanKelasHistory::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeLulus($query)
    {
        return $query->where('status', 'lulus');
    }

    public function scopeKeluar($query)
    {
        return $query->where('status', 'keluar');
    }

    public function scopeJenjang($query, $jenjang)
    {
        return $query->where('jenjang', $jenjang);
    }

    public function scopeKelas($query, $kelas)
    {
        return $query->where('kelas', $kelas);
    }

    public function scopeBelumLunas($query)
    {
        return $query->where('status_spp', 'Belum Lunas');
    }

    public function getStatusColorAttribute()
    {
        return [
            'aktif' => 'success',
            'lulus' => 'info',
            'keluar' => 'danger'
        ][$this->status] ?? 'secondary';
    }

    public function getStatusSppColorAttribute()
    {
        return $this->status_spp === 'Lunas' ? 'success' : 'warning';
    }

    public function getNamaWaliAttribute()
    {
        return $this->wali ? $this->wali->name : null;
    }
}
