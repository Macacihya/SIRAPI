<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $fillable = [
        'tahun_mulai',
        'tahun_selesai',
        'semester',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getLabelAttribute(): string
    {
        return "{$this->tahun_mulai}/{$this->tahun_selesai} - {$this->semester}";
    }
}
