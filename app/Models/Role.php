<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    /**
     * Tabel roles — daftar peran yang tersedia dalam sistem.
     * Relasi M:M dengan users melalui tabel pivot user_roles.
     */
    public $timestamps = false;

    protected $fillable = ['nama_role'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }
}
