<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatStatusSiswa extends Model
{
    protected $fillable = [
        'siswa_id',
        'status',
    ];

    /**
     * Relasi ke siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
