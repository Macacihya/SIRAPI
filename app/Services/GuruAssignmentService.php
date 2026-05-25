<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\GuruPengampu;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GuruAssignmentService
{
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

    public function syncKelasWali(Guru $guru, ?int $kelasWaliId): void
    {
        Kelas::where('wali_guru_id', $guru->user_id)->update(['wali_guru_id' => null]);

        if ($kelasWaliId) {
            Kelas::where('id', $kelasWaliId)->update(['wali_guru_id' => $guru->user_id]);
        }
    }

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
}
