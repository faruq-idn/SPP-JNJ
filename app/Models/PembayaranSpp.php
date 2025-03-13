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
    const STATUS_UNPAID = 'unpaid';

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

    public function scopeTunggakan($query)
    {
        return $query->where('status', '!=', self::STATUS_SUCCESS);
    }

    public function isTunggakan(): bool
    {
        return $this->status !== self::STATUS_SUCCESS;
    }

    /**
     * Validasi apakah pembayaran untuk bulan ini terlambat
     */
    public function isTerlambat(): bool
    {
        $jatuhTempo = Carbon::create($this->tahun, $this->bulan, 10);
        return $this->isTunggakan() && now()->greaterThan($jatuhTempo);
    }

    /**
     * Cek apakah bulan dan tahun valid untuk pembayaran
     */
    public function isValidPeriod(): bool
    {
        $tanggalMasuk = $this->santri->tanggal_masuk->startOfMonth();
        $periodePembayaran = Carbon::create($this->tahun, $this->bulan, 1)->startOfMonth();
        $bulanSekarang = Carbon::now()->startOfMonth();

        return $periodePembayaran >= $tanggalMasuk && $periodePembayaran <= $bulanSekarang;
    }

    protected static function booted()
    {
        static::creating(function ($pembayaran) {
            Log::info('Creating payment', [
                'santri_id' => $pembayaran->santri_id,
                'bulan' => $pembayaran->bulan,
                'tahun' => $pembayaran->tahun,
                'nominal' => $pembayaran->nominal,
                'status' => $pembayaran->status
            ]);
        });

        static::updating(function ($pembayaran) {
            Log::info('Updating payment', [
                'id' => $pembayaran->id,
                'changes' => $pembayaran->getDirty()
            ]);
        });

        static::created(function ($pembayaran) {
            Log::info('Payment created', [
                'id' => $pembayaran->id,
                'santri_id' => $pembayaran->santri_id,
                'periode' => $pembayaran->periode
            ]);
        });
    }
}
