<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Siswa extends Model
{
    use HasFactory, LogsActivity;

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
