<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiSikap extends Model
{
    protected $fillable = [
        'raport_id',
        'predikat',
        'deskripsi',
    ];

    public function raport()
    {
        return $this->belongsTo(Raport::class);
    }
}
