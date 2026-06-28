<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaportEkskul extends Model
{
    protected $fillable = [
        'raport_id',
        'ekstrakurikuler_id',
        'predikat',
        'deskripsi',
    ];

    public function raport()
    {
        return $this->belongsTo(Raport::class);
    }

    public function ekstrakurikuler()
    {
        return $this->belongsTo(Ekstrakurikuler::class);
    }
}
