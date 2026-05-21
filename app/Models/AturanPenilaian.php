<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AturanPenilaian extends Model
{
    protected $fillable = [
        'nama_komponen',
        'mapel_id',
    ];

    /**
     * Relasi ke mata pelajaran
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id', 'kode_mapel');
    }
}
