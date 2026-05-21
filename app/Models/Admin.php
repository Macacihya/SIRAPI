<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $primaryKey = 'user_id';
    public $incrementing  = false;
    protected $keyType    = 'int';

    protected $fillable = [
        'user_id',
        'jabatan_admin',
    ];

    // ─── ISA Relations ───────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
