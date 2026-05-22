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
        'nis',
        'nama_siswa',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'alamat',
        'status_aktif',
        'jabatan_kelas',
        'sekolah_id',
        'kelas_id',
    ];

    protected $casts = [
        'tgl_lahir'    => 'date',
        'status_aktif' => 'boolean',
    ];

    /**
     * Relasi ke kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Relasi ke sekolah
     */
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    /**
     * Relasi ke riwayat status siswa
     */
    public function riwayatStatus()
    {
        return $this->hasMany(RiwayatStatusSiswa::class);
    }
}
