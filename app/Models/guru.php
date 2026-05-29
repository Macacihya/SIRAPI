<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Guru extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'user_id';
    public $incrementing  = false;
    protected $keyType    = 'int';

    protected $fillable = [
        'user_id',
        'nip',
        'sekolah_id',
        'jabatan',
        'mata_pelajaran',
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
        
        return $this->user->roles->map(function ($role) {
            if ($role->nama_role === 'guru') {
                return 'GURU MAPEL';
            } elseif ($role->nama_role === 'walikelas') {
                return 'WALI KELAS';
            } elseif ($role->nama_role === 'admin') {
                return 'ADMIN';
            }
            return strtoupper($role->nama_role);
        })->toArray();
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
