<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\LogsActivity;

// Model User - Representasi data Pengguna untuk Autentikasi & Otorisasi
class User extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity;

    // Atribut yang dapat diisi secara massal
    protected $fillable = [
        'nama',     
        'username',
        'email',
        'password',
        'role', // Diisi via mutator setRoleAttribute
        'jenis_kelamin',
        'no_hp',
        'alamat',
        'status',
    ];

    // Atribut yang disembunyikan saat serialisasi JSON/Array
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casting tipe data kolom database
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // Variabel penampung sementara role untuk disinkronkan setelah model di-save
    public ?string $pendingRole = null;

    // Boot method: Sinkronisasi role otomatis setelah model disimpan
    protected static function boot(): void
    {
        parent::boot();

        static::saved(function (User $user) {
            if ($user->pendingRole !== null) {
                $role = Role::where('nama_role', $user->pendingRole)->first();
                if ($role) {
                    $user->roles()->sync([$role->id]);
                }
                $user->pendingRole = null;
            }
        });
    }

    // Mutator: Menyimpan input role ke pendingRole (karena kolom role tidak ada di tabel users)
    public function setRoleAttribute(mixed $value): void
    {
        $this->pendingRole = $value;
    }

    // Accessor: Mengambil nama role pertama dari relasi M:M
    public function getRoleAttribute(): ?string
    {
        if ($this->relationLoaded('roles')) {
            return $this->roles->first()?->nama_role;
        }
        return $this->roles()->value('nama_role');
    }

    // Relasi M:M (Many-to-Many) ke model Role melalui tabel pivot user_roles
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    // Mengganti/singkronisasi role berdasarkan nama role
    public function syncRoleByName(string $roleName): void
    {
        $role = Role::where('nama_role', $roleName)->first();
        if ($role) {
            $this->roles()->sync([$role->id]);
        }
    }

    // Cek apakah user memiliki role tertentu
    public function hasRole(string $roleName): bool
    {
        if ($this->relationLoaded('roles')) {
            return $this->roles->contains('nama_role', $roleName);
        }
        return $this->roles()->where('nama_role', $roleName)->exists();
    }

    // Accessor: Mengambil kolom nama menggunakan properti alias name
    public function getNameAttribute(): ?string
    {
        return $this->attributes['nama'] ?? null;
    }

    // Mutator: Menyimpan nilai properti alias name ke kolom nama
    public function setNameAttribute(?string $value): void
    {
        $this->attributes['nama'] = $value;
    }

    // Relasi 1:1 ke model Admin
    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id');
    }

    // Relasi 1:1 ke model Guru
    public function guru()
    {
        return $this->hasOne(Guru::class, 'user_id');
    }

    // Relasi 1:M ke Log Aktivitas
    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'user_id');
    }
}
