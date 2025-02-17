<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PembayaranSpp extends Model
{
    protected $table = 'pembayaran_spp';

    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_PENDING = 'pending';

    protected $fillable = [
        'santri_id',
        'bulan',
        'tahun',
        'nominal',
        'status',
        'tanggal_bayar',
        'keterangan',
        'snap_token',
        'order_id',
        'payment_type',
        'transaction_id',
        'payment_details',
        'fraud_status',
        'metode_pembayaran_id'
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'payment_details' => 'array'
    ];

    protected $appends = ['nama_bulan', 'periode'];

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
        return Carbon::create()
            ->setMonth((int)$this->bulan)
            ->locale('id')
            ->translatedFormat('F');
    }

    public function getPeriodeAttribute(): string
    {
        return Carbon::create($this->tahun, (int)$this->bulan, 1)
            ->locale('id')
            ->translatedFormat('F Y');
    }

    public function getIsLunasAttribute(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    public function scopeLunas($query)
    {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    public function scopeBelumLunas($query)
    {
        return $query->where('status', '!=', self::STATUS_SUCCESS);
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
