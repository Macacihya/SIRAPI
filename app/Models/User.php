<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Traits\LogsActivity;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, LogsActivity;

    protected $fillable = [
        'nama',     
        'username',
        'email',
        'password',
        'role',
        'jenis_kelamin',
        'no_hp',
        'alamat',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Penyimpanan sementara role yang akan disinkronkan ke user_roles
     * setelah model disimpan ke database.
     */
        public ?string $pendingRole = null;

    // ─── Boot: Sync role ke user_roles setelah save ───────────

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

    // ─── Role Accessor & Mutator (M:M) ───────────────────────

    /**
     * Mutator: Menangkap nilai 'role' saat create/update.
     * Tidak menulis ke $attributes karena kolom tidak ada di DB.
     */
    public function setRoleAttribute(mixed $value): void
    {
        // Simpan ke pendingRole, bukan ke attributes
        $this->pendingRole = $value;
    }

    /**
     * Accessor: Mengembalikan nama role pertama dari relasi M:M.
     */
    public function getRoleAttribute(): ?string
    {
        if ($this->relationLoaded('roles')) {
            return $this->roles->first()?->nama_role;
        }
        return $this->roles()->value('nama_role');
    }

    // ─── Relasi M:M ke Role ───────────────────────────────────

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    /**
     * Sinkronisasi role berdasarkan nama role (ganti role lama).
     */
    public function syncRoleByName(string $roleName): void
    {
        $role = Role::where('nama_role', $roleName)->first();
        if ($role) {
            $this->roles()->sync([$role->id]);
        }
    }

    /**
     * Cek apakah user memiliki role tertentu.
     */
    public function hasRole(string $roleName): bool
    {
        if ($this->relationLoaded('roles')) {
            return $this->roles->contains('nama_role', $roleName);
        }
        return $this->roles()->where('nama_role', $roleName)->exists();
    }

    // ─── ISA Relations ───────────────────────────────────────

    public function getNameAttribute(): ?string
    {
        return $this->attributes['nama'] ?? null;
    }

    public function setNameAttribute(?string $value): void
    {
        $this->attributes['nama'] = $value;
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id');
    }

    public function guru()
    {
        return $this->hasOne(Guru::class, 'user_id');
    }

    // ─── Log Aktivitas ───────────────────────────────────────

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'user_id');
    }
}
