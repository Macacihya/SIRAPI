<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapKehadiran extends Model
{
    protected $fillable = [
        'raport_id',
        'status',       // sakit | izin | alpha
        'keterangan',   // opsional
    ];

    public function raport()
    {
        return $this->belongsTo(Raport::class);
    }
}

