<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'tahun_ajaran_id',
        'wali_guru_id',
    ];

    /**
     * Relasi ke tahun ajaran
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    /**
     * Relasi ke siswa-siswa di kelas ini
     */
    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    /**
     * Relasi ke guru pengampu yang mengajar di kelas ini
     */
    public function guruPengampus()
    {
        return $this->hasMany(GuruPengampu::class);
    }

    public function waliGuru()
    {
        return $this->belongsTo(Guru::class, 'wali_guru_id', 'user_id');
    }
}
