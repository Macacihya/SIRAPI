<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $table = 'sekolahs';

    protected $fillable = [
        'npsn',
        'nama_sekolah',
        'alamat',
        'kode_pos',
        'telepon',
        'email',
        'logo',
        'nip_kepsek',
        'status_sekolah',
        'nama_kepala_sekolah',
        'bentuk_pendidikan',
    ];

    // ─── Relasi ──────────────────────────────────

    public function gurus()
    {
        return $this->hasMany(Guru::class, 'sekolah_id');
    }
}