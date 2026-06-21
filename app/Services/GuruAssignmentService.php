<?php

namespace App\Services;

use App\Contracts\GuruServiceInterface;
use App\Models\Guru;
use App\Models\GuruPengampu;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\User;
use App\Models\Sekolah;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

// Service untuk mengelola penugasan, registrasi, pembaruan, dan penghapusan data Guru
class GuruAssignmentService implements GuruServiceInterface
{
    // Validasi pemilihan mapel & kelas pengampu
    public function validatePengampuSelection(array $validated): void
    {
        $mapelIds = $validated['mapel_ids'] ?? [];
        $kelasIds = $validated['kelas_pengampu_ids'] ?? [];

        if (count($mapelIds) === 0 || count($kelasIds) === 0) {
            throw ValidationException::withMessages([
                'mapel_ids' => 'Mapel diampu dan kelas diampu wajib dipilih.',
            ]);
        }

        if ((count($mapelIds) > 0 && count($kelasIds) === 0) || (count($mapelIds) === 0 && count($kelasIds) > 0)) {
            throw ValidationException::withMessages([
                'mapel_ids' => 'Mapel diampu dan kelas diampu harus dipilih bersama.',
            ]);
        }
    }

    // Validasi pemilihan kelas wali (memastikan kelas belum memiliki wali)
    public function validateKelasWaliSelection(array $validated, ?int $currentGuruId = null): void
    {
        if (!str_contains($validated['peran'] ?? '', 'WALI KELAS')) {
            return;
        }

        if (empty($validated['kelas_wali_id'])) {
            throw ValidationException::withMessages([
                'kelas_wali_id' => 'Kelas wali kelas wajib dipilih.',
            ]);
        }

        $query = Kelas::where('id', $validated['kelas_wali_id'])->whereNotNull('wali_guru_id');
        if ($currentGuruId) {
            $query->where('wali_guru_id', '!=', $currentGuruId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'kelas_wali_id' => 'Kelas tersebut sudah memiliki wali kelas.',
            ]);
        }
    }

    // Sinkronisasi data pengampu mata pelajaran guru
    public function syncGuruPengampu(Guru $guru, array $mapelIds, array $kelasIds): void
    {
        $mapelIds = array_values(array_unique(array_filter($mapelIds)));
        $kelasIds = array_values(array_unique(array_filter($kelasIds)));

        GuruPengampu::where('guru_id', $guru->user_id)->delete();

        foreach ($kelasIds as $kelasId) {
            foreach ($mapelIds as $mapelId) {
                GuruPengampu::create([
                    'guru_id' => $guru->user_id,
                    'kelas_id' => $kelasId,
                    'mapel_id' => $mapelId,
                ]);
            }
        }
    }

    // Sinkronisasi data kelas wali guru
    public function syncKelasWali(Guru $guru, ?int $kelasWaliId): void
    {
        Kelas::where('wali_guru_id', $guru->user_id)->update(['wali_guru_id' => null]);

        if ($kelasWaliId) {
            Kelas::where('id', $kelasWaliId)->update(['wali_guru_id' => $guru->user_id]);
        }
    }

    // Resolusi ID mata pelajaran dari string pemisah |
    public function resolveMapelIds(?string $text): array
    {
        return collect(explode('|', (string) $text))
            ->map(fn ($value) => trim($value))
            ->filter()
            ->map(function ($value) {
                $mapel = MataPelajaran::where('kode_mapel', $value)
                    ->orWhere('nama_mapel', $value)
                    ->first();

                return $mapel?->kode_mapel;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    // Resolusi ID kelas dari string pemisah |
    public function resolveKelasIds(?string $text): array
    {
        return collect(explode('|', (string) $text))
            ->map(fn ($value) => trim($value))
            ->filter()
            ->map(fn ($value) => Kelas::where('nama_kelas', $value)->value('id'))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    // Generate username unik berdasarkan email guru
    public function uniqueUsername(string $email): string
    {
        $base = Str::slug(Str::before($email, '@'), '_') ?: 'guru';
        $username = $base;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $base . '_' . $counter++;
        }

        return $username;
    }

    // Memproses penyimpanan data guru baru ke database
    public function prosesSimpan(array $validated): Guru
    {
        $role = str_contains($validated['peran'], 'WALI KELAS') ? 'walikelas' : 'guru';
        $sekolahId = $validated['sekolah_id'] ?? (Sekolah::first()->id ?? null);

        if (!$sekolahId) {
            throw new \Exception('Belum ada data sekolah terdaftar. Harap daftarkan sekolah terlebih dahulu.');
        }

        return DB::transaction(function () use ($validated, $role, $sekolahId) {
            if (!empty($validated['user_id'])) {
                $user = User::whereHas('roles', fn($q) => $q->whereIn('nama_role', ['guru', 'walikelas']))
                    ->findOrFail($validated['user_id']);
                $user->update(['nama' => $validated['nama']]);
                
                $newRoleNames = ($role === 'walikelas') ? ['guru', 'walikelas'] : ['guru'];
                $roleIds = \App\Models\Role::whereIn('nama_role', $newRoleNames)->pluck('id')->toArray();
                $user->roles()->sync($roleIds);
            } else {
                $user = User::create([
                    'nama'     => $validated['nama'],
                    'email'    => $validated['email'],
                    'username' => $validated['username'] ?? $this->uniqueUsername($validated['email']),
                    'password' => Hash::make($validated['nip']),
                ]);
                
                $newRoleNames = ($role === 'walikelas') ? ['guru', 'walikelas'] : ['guru'];
                $roleIds = \App\Models\Role::whereIn('nama_role', $newRoleNames)->pluck('id')->toArray();
                $user->roles()->sync($roleIds);
            }

            $guruData = [
                'user_id'    => $user->id,
                'nip'        => $validated['nip'],
                'sekolah_id' => $sekolahId,
            ];
            if (Schema::hasColumn('gurus', 'jabatan')) {
                $guruData['jabatan'] = $validated['jabatan'] ?? (str_contains($validated['peran'], 'WALI KELAS') ? 'Guru Mapel & Wali Kelas' : 'Guru Mapel');
            }

            $guru = Guru::updateOrCreate(['user_id' => $user->id], $guruData);

            $this->syncGuruPengampu($guru, $validated['mapel_ids'] ?? [], $validated['kelas_pengampu_ids'] ?? []);
            $this->syncKelasWali($guru, str_contains($validated['peran'], 'WALI KELAS') ? ($validated['kelas_wali_id'] ?? null) : null);

            return $guru;
        });
    }

    // Memproses pembaruan data guru di database
    public function prosesUpdate(Guru $guru, array $validated): Guru
    {
        return DB::transaction(function () use ($guru, $validated) {
            $guru->user->update([
                'nama'  => $validated['nama'],
                'email' => $validated['email'],
            ]);
            
            $newRoleNames = str_contains($validated['peran'], 'WALI KELAS') ? ['guru', 'walikelas'] : ['guru'];
            $roleIds = \App\Models\Role::whereIn('nama_role', $newRoleNames)->pluck('id')->toArray();
            $guru->user->roles()->sync($roleIds);

            $updateData = [];
            if (isset($validated['sekolah_id'])) {
                $updateData['sekolah_id'] = $validated['sekolah_id'];
            }
            if (Schema::hasColumn('gurus', 'jabatan')) {
                if (isset($validated['jabatan'])) {
                    $updateData['jabatan'] = $validated['jabatan'];
                } else {
                    $updateData['jabatan'] = str_contains($validated['peran'], 'WALI KELAS') ? 'Guru Mapel & Wali Kelas' : 'Guru Mapel';
                }
            }

            if (!empty($updateData)) {
                $guru->update($updateData);
            }

            $this->syncGuruPengampu($guru, $validated['mapel_ids'] ?? [], $validated['kelas_pengampu_ids'] ?? []);
            $this->syncKelasWali($guru, str_contains($validated['peran'], 'WALI KELAS') ? ($validated['kelas_wali_id'] ?? null) : null);

            return $guru;
        });
    }

    // Memproses penghapusan data guru beserta data user terkait dari database
    public function prosesHapus(Guru $guru): void
    {
        $user = $guru->user;
        Kelas::where('wali_guru_id', $guru->user_id)->update(['wali_guru_id' => null]);
        $guru->delete();
        if ($user) {
            $user->delete();
        }
    }

    // Membangun array row data guru untuk format response Alpine.js
    public function buildGuruRow(Guru $guru): array
    {
        $mapels = $guru->guruPengampus->map(fn($gp) => $gp->mataPelajaran->nama_mapel ?? '-')->unique()->implode(', ');
        $kelasDiampu = $guru->guruPengampus->map(fn($gp) => $gp->kelas->nama_kelas ?? null)->filter()->unique()->implode(', ');
        $kelasWali = $guru->kelasWali->pluck('nama_kelas')->filter()->implode(', ');
        $roles = [];
        if ($guru->guruPengampus->isNotEmpty()) {
            $roles[] = 'GURU MAPEL';
        }
        if ($guru->kelasWali->isNotEmpty()) {
            $roles[] = 'WALI KELAS';
        }
        if (empty($roles)) {
            $roles[] = 'GURU MAPEL';
        }

        return [
            'id'                 => $guru->user_id,
            'name'               => $guru->user->nama ?? '-',
            'nama'               => $guru->user->nama ?? '-',
            'email'              => $guru->user->email ?? '-',
            'nip'                => $guru->nip,
            'peran'              => in_array('WALI KELAS', $roles, true) ? 'GURU MAPEL & WALI KELAS' : 'GURU MAPEL',
            'roles'              => $roles,
            'mapel'              => $mapels ?: '-',
            'mapel_ids'          => $guru->guruPengampus->pluck('mapel_id')->unique()->values()->all(),
            'kelas_diampu'       => $kelasDiampu ?: '-',
            'kelas_pengampu_ids' => $guru->guruPengampus->pluck('kelas_id')->unique()->values()->all(),
            'kelas_walikelas'    => $kelasWali ?: '-',
            'kelas_wali_id'      => $guru->kelasWali->pluck('id')->first(),
        ];
    }
}
