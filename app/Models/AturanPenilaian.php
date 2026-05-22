<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class AturanPenilaian extends Model
{
    use LogsActivity;
    protected $fillable = [
        'nama_komponen',
        'bobot',
        'mapel_id',
    ];

    /**
     * Relasi ke mata pelajaran
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id', 'kode_mapel');
    }
}
