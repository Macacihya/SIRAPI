<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

// Model Guru - Representasi data Guru (Relasi ISA ke model User)
class Guru extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'user_id';

    // Menonaktifkan auto-increment karena sinkron dengan id users
    public $incrementing = false;

    protected $keyType = 'int';

    // Atribut yang dapat diisi secara massal
    protected $fillable = [
        'user_id',
        'nip',
        'sekolah_id',
        'jabatan',
        'mata_pelajaran',
    ];

    // Atribut virtual tambahan untuk response JSON/Array
    protected $appends = [
        'name',
        'email',
        'roles',
        'mapel',
    ];

    // Mengambil nama dari model User
    public function getNameAttribute()
    {
        return $this->user ? $this->user->nama : '';
    }

    // Mengambil email dari model User
    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : '';
    }

    // Mengubah format nama role agar lebih rapi untuk frontend
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

    // Menggabungkan semua nama mapel yang diampu oleh guru
    public function getMapelAttribute()
    {
        if ($this->guruPengampus->isEmpty()) {
            return '-';
        }
        return $this->guruPengampus->map(function ($gp) {
            return $gp->mataPelajaran ? $gp->mataPelajaran->nama_mapel : null;
        })->filter()->implode(', ') ?: '-';
    }

    // Relasi ke data User dasar
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke data Sekolah
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    // Relasi ke tabel GuruPengampu (mata pelajaran yang diampu)
    public function guruPengampus()
    {
        return $this->hasMany(GuruPengampu::class, 'guru_id', 'user_id');
    }

    // Relasi ke tabel Kelas (jika sebagai Wali Kelas)
    public function kelasWali()
    {
        return $this->hasMany(Kelas::class, 'wali_guru_id', 'user_id');
    }

    // Relasi ke Riwayat Status Guru
    public function riwayatStatus()
    {
        return $this->hasMany(RiwayatStatusGuru::class, 'guru_id', 'user_id');
    }
}
