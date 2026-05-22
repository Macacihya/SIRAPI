<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapKehadiran extends Model
{
    protected $fillable = [
        'raport_id',
        'sakit',
        'izin',
        'alpha',
    ];

    public function raport()
    {
        return $this->belongsTo(Raport::class);
    }
}
