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

    protected $casts = [
        'sakit' => 'integer',
        'izin' => 'integer',
        'alpha' => 'integer',
    ];

    public function raport()
    {
        return $this->belongsTo(Raport::class);
    }
}
