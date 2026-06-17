<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model Admin - Representasi data Administrator (Relasi ISA ke model User)
class Admin extends Model
{
    protected $primaryKey = 'user_id';

    // Menonaktifkan auto-increment karena sinkron dengan id users
    public $incrementing = false;

    // Tipe data primary key adalah integer
    protected $keyType = 'int';

    // Atribut yang dapat diisi secara massal
    protected $fillable = [
        'user_id',
    ];

    // Relasi ke data User dasar
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
