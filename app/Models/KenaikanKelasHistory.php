<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KenaikanKelasHistory extends Model
{
    protected $table = 'kenaikan_kelas_history';
    
    protected $fillable = [
        'santri_id',
        'kelas_sebelum',
        'kelas_sesudah',
        'status',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
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
