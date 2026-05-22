<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sikap extends Model
{
    protected $fillable = [
        'nama_sikap',
    ];

    public function nilaiSikaps()
    {
        return $this->hasMany(NilaiSikap::class);
    }

    public function raports()
    {
        return $this->belongsToMany(Raport::class, 'raport_sikaps')
            ->withPivot(['id', 'predikat', 'deskripsi'])
            ->withTimestamps();
    }
}
