<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = ['user_id', 'nip'];

    /**
     * Relasi ke tabel induk ISA: users
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke guru pengampu (kelas & mapel yang diampu)
     */
    public function guruPengampus()
    {
        return $this->hasMany(GuruPengampu::class, 'guru_id', 'user_id');
    }
}