<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $primaryKey = 'kode_mapel';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'kkm',
    ];

    /**
     * Relasi ke aturan penilaian
     */
    public function aturanPenilaians()
    {
        return $this->hasMany(AturanPenilaian::class, 'mapel_id', 'kode_mapel');
    }

    /**
     * Relasi ke guru pengampu
     */
    public function guruPengampus()
    {
        return $this->hasMany(GuruPengampu::class, 'mapel_id', 'kode_mapel');
    }
}
