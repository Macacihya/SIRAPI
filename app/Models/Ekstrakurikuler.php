<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ekstrakurikuler extends Model
{
    protected $fillable = [
        'nama_eskul',
    ];

    public function raportEkskuls()
    {
        return $this->hasMany(RaportEkskul::class);
    }
}
