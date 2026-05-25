<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $fillable = [
        'nilai_akhir',
        'siswa_id',
        'raport_id',
        'mapel_id',
    ];

    protected $casts = [
        'nilai_akhir' => 'decimal:2',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function raport()
    {
        return $this->belongsTo(Raport::class);
    }

    public function mataPelajaran()
    {
        // mapel_id mengarah ke kode_mapel karena primary key mapel bukan id angka.
        return $this->belongsTo(MataPelajaran::class, 'mapel_id', 'kode_mapel');
    }

    public function capaianKompetensis()
    {
        return $this->hasMany(CapaianKompetensi::class);
    }
}
