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

    // Alias lama untuk kompatibilitas kode yang masih membaca satu nilai sikap.
    public function nilaiSikap()
    {
        return $this->hasOne(NilaiSikap::class);
    }

    public function nilaiSikaps()
    {
        return $this->hasMany(NilaiSikap::class);
    }

    public function sikaps()
    {
        return $this->belongsToMany(Sikap::class, 'raport_sikaps')
            ->withPivot(['id', 'predikat', 'deskripsi'])
            ->withTimestamps();
    }

    public function raportEkskuls()
    {
        return $this->hasMany(RaportEkskul::class);
    }
}
