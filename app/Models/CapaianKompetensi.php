<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapaianKompetensi extends Model
{
    protected $fillable = [
        'nilai_id',
        'deskripsi',
        'status',
    ];

    public function nilai()
    {
        return $this->belongsTo(Nilai::class);
    }
}
