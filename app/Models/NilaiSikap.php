<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiSikap extends Model
{
    protected $table = 'raport_sikaps';

    protected $fillable = [
        'raport_id',
        'sikap_id',
        'predikat',
        'deskripsi',
    ];

    public function raport()
    {
        return $this->belongsTo(Raport::class);
    }

    public function sikap()
    {
        return $this->belongsTo(Sikap::class);
    }
}
