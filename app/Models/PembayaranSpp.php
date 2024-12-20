<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PembayaranSpp extends Model
{
    protected $table = 'pembayaran_spp';

    protected $fillable = [
        'santri_id',
        'nominal',
        'bulan',
        'tahun',
        'tanggal_bayar',
        'metode_pembayaran_id',
        'status',
        'keterangan',
        'snap_token',
        'order_id',
        'payment_type',
        'transaction_id',
        'payment_details'
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'tahun' => 'integer',
        'bulan' => 'string',
        'nominal' => 'decimal:0',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'payment_details' => 'array'
    ];

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function metode_pembayaran(): BelongsTo
    {
        return $this->belongsTo(MetodePembayaran::class);
    }

    public function getNamaBulanAttribute(): string
    {
        return Carbon::createFromDate(null, (int)$this->bulan, 1)->translatedFormat('F');
    }

    public function getPeriodeAttribute(): string
    {
        return Carbon::createFromDate($this->tahun, (int)$this->bulan, 1)->translatedFormat('F Y');
    }

    public function getIsLunasAttribute(): bool
    {
        return $this->status === 'success';
    }

    public function scopeLunas($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeBelumLunas($query)
    {
        return $query->where('status', '!=', 'success');
    }

    protected static function booted()
    {
        static::updating(function ($pembayaran) {
            Log::info('Updating payment', [
                'id' => $pembayaran->id,
                'changes' => $pembayaran->getDirty()
            ]);
        });
    }
}
