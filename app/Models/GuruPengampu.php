<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuruPengampu extends Model
{
    protected $fillable = [
        'guru_id',
        'kelas_id',
        'mapel_id',
    ];

    /**
     * Relasi ke guru (tabel gurus, FK = user_id)
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'user_id');
    }

    /**
     * Relasi ke kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Relasi ke mata pelajaran
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id', 'kode_mapel');
    }
}
