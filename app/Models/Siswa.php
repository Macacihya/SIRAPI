<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        'nisn',
        'nama_siswa',
        'kelas_id',
    ];

    /**
     * Relasi ke kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Relasi ke riwayat status siswa
     */
    public function riwayatStatus()
    {
        return $this->hasMany(RiwayatStatusSiswa::class);
    }
}
