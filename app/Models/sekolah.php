<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $fillable = ['nama_sekolah', 'alamat', 'telepon'];

    public function gurus()
    {
        return $this->hasMany(Guru::class);
    }
}