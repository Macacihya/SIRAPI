<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Raport extends Model
{
    protected $fillable = [
        'siswa_id',
        'tahun_ajaran_id',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    public function rekapKehadiran()
    {
        return $this->hasOne(RekapKehadiran::class);
    }

    // Relasi banyak baris dipakai karena rekap disimpan per hari/status ketidakhadiran.
    public function rekapKehadirans()
    {
        return $this->hasMany(RekapKehadiran::class);
    }

    public function nilaiSikap()
    {
        return $this->hasOne(NilaiSikap::class);
    }

    public function raportEkskuls()
    {
        return $this->hasMany(RaportEkskul::class);
    }
}
