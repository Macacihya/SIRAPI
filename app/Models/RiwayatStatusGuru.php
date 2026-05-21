<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatStatusGuru extends Model
{
    protected $table = 'riwayat_status_gurus';

    protected $fillable = [
        'guru_id',
        'status',
        'keterangan',
        'tanggal_perubahan',
    ];

    protected $casts = [
        'tanggal_perubahan' => 'date',
    ];

    // ─── Relasi ──────────────────────────────────

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'user_id');
    }
}
