<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'waktu',
        'tipe_icon',
    ];

    protected $casts = [
        'waktu' => 'datetime',
    ];

    // ─── Relasi ──────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
