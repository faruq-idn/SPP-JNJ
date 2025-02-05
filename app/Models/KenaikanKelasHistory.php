<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KenaikanKelasHistory extends Model
{
    protected $table = 'kenaikan_kelas_history';

    protected $fillable = [
        'santri_id',
        'jenjang_awal',
        'kelas_awal',
        'status_awal',
        'jenjang_akhir',
        'kelas_akhir',
        'status_akhir',
        'created_by'
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
