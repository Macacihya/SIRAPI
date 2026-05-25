<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GuruCsvService
{
    public function __construct(private GuruAssignmentService $assignmentService)
    {
    }

    public function export(): StreamedResponse
    {
        $gurus = Guru::with(['user', 'guruPengampus.mataPelajaran', 'guruPengampus.kelas', 'kelasWali'])
            ->orderBy('nip')
            ->get();

        return response()->streamDownload(function () use ($gurus) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Nama', 'Email', 'NIP_NUPTK', 'Peran', 'Mapel_Diampu', 'Kelas_Diampu', 'Kelas_Walikelas']);

            foreach ($gurus as $guru) {
                $roles = [];
                if ($guru->guruPengampus->isNotEmpty()) {
                    $roles[] = 'GURU MAPEL';
                }
                if ($guru->kelasWali->isNotEmpty()) {
                    $roles[] = 'WALI KELAS';
                }

                fputcsv($handle, [
                    $guru->user->nama ?? '-',
                    $guru->user->email ?? '-',
                    $guru->nip,
                    implode('|', $roles),
                    $guru->guruPengampus->pluck('mataPelajaran.nama_mapel')->filter()->unique()->implode('|'),
                    $guru->guruPengampus->pluck('kelas.nama_kelas')->filter()->unique()->implode('|'),
                    $guru->kelasWali->pluck('nama_kelas')->filter()->implode('|'),
                ]);
            }

            fclose($handle);
        }, 'data-guru.csv', ['Content-Type' => 'text/csv']);
    }

    public function import(UploadedFile $file): int
    {
        $sekolahId = Sekolah::first()->id ?? null;
        if (!$sekolahId) {
            return 0;
        }

        $handle = fopen($file->getRealPath(), 'r');
        fgetcsv($handle);
        $imported = 0;

        DB::transaction(function () use ($handle, &$imported, $sekolahId) {
            while (($row = fgetcsv($handle)) !== false) {
                [$nama, $email, $nip, $peran, $mapelText, $kelasText, $kelasWaliText] = array_pad($row, 7, null);

                if (!$nama || !$email || !$nip) {
                    continue;
                }

                $roles = collect(explode('|', strtoupper((string) $peran)))
                    ->map(fn ($role) => trim($role))
                    ->filter()
                    ->values();
                $isWaliKelas = $roles->contains('WALI KELAS') || filled($kelasWaliText);
                $role = $isWaliKelas ? 'walikelas' : 'guru';

                $user = User::firstOrCreate(
                    ['email' => trim($email)],
                    [
                        'nama' => trim($nama),
                        'username' => $this->assignmentService->uniqueUsername($email),
                        'password' => Hash::make($nip),
                        'role' => $role,
                    ]
                );
                $user->update([
                    'nama' => trim($nama),
                    'role' => $role,
                ]);

                $guruData = [
                    'nip' => trim($nip),
                    'sekolah_id' => $sekolahId,
                ];
                if (Schema::hasColumn('gurus', 'jabatan')) {
                    $guruData['jabatan'] = $isWaliKelas ? 'Wali Kelas' : 'Guru Mapel';
                }

                $guru = Guru::updateOrCreate(
                    ['user_id' => $user->id],
                    $guruData
                );

                $mapelIds = $this->assignmentService->resolveMapelIds($mapelText);
                $kelasIds = $this->assignmentService->resolveKelasIds($kelasText);
                if ($mapelIds && $kelasIds) {
                    $this->assignmentService->syncGuruPengampu($guru, $mapelIds, $kelasIds);
                }

                $kelasWali = $this->assignmentService->resolveKelasIds($kelasWaliText);
                $this->assignmentService->syncKelasWali($guru, $isWaliKelas ? ($kelasWali[0] ?? null) : null);

                $imported++;
            }
        });

        fclose($handle);

        return $imported;
    }
}
