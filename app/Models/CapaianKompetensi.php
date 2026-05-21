<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapaianKompetensi extends Model
{
    protected $fillable = [
        'nilai_id',
    ];

    public function nilai()
    {
        return $this->belongsTo(Nilai::class);
    }
}
