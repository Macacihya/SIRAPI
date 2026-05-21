<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $primaryKey = 'user_id';
    public $incrementing  = false;
    protected $keyType    = 'int';

    protected $fillable = [
        'user_id',
        'nip',
        'jabatan',
        'sekolah_id',
    ];

    protected $appends = [
        'name',
        'email',
        'roles',
        'mapel',
    ];

    // ─── Accessors untuk Frontend Alpine.js ────────

    public function getNameAttribute()
    {
        return $this->user ? $this->user->nama : '';
    }

    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : '';
    }

    public function getRolesAttribute()
    {
        if (!$this->user) return [];
        
        $roles = [];
        if ($this->user->role === 'guru') {
            $roles[] = 'GURU MAPEL';
        } elseif ($this->user->role === 'walikelas') {
            $roles[] = 'WALI KELAS';
        } elseif ($this->user->role === 'admin') {
            $roles[] = 'ADMIN';
        }
        return $roles;
    }

    public function getMapelAttribute()
    {
        if ($this->guruPengampus->isEmpty()) {
            return '-';
        }
        return $this->guruPengampus->map(function ($gp) {
            return $gp->mataPelajaran ? $gp->mataPelajaran->nama_mapel : null;
        })->filter()->implode(', ') ?: '-';
    }

    // ─── ISA Relations ───────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ─── Relasi ke Sekolah ───────────────────────

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    // ─── Relasi ke Pengajaran ────────────────────

    public function guruPengampus()
    {
        return $this->hasMany(GuruPengampu::class, 'guru_id', 'user_id');
    }

    public function kelasWali()
    {
        return $this->hasMany(Kelas::class, 'wali_guru_id', 'user_id');
    }

    // ─── Relasi ke Riwayat Status ────────────────

    public function riwayatStatus()
    {
        return $this->hasMany(RiwayatStatusGuru::class, 'guru_id', 'user_id');
    }
}
