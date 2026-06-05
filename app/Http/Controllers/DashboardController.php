<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\GuruPengampu;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Raport;
use App\Models\RekapKehadiran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = request()->user();

        // Statistik untuk dashboard
        $totalSiswa   = Siswa::count();
        $totalGuru    = Guru::count();
        $totalKelas   = Kelas::count();
        $totalMapel   = MataPelajaran::count();
        $tahunAktif   = TahunAjaran::where('is_active', true)->first();
        $logAktivitas = \App\Models\LogAktivitas::with('user')->orderBy('waktu', 'desc')->take(20)->get();
        $role         = getUserRole();
        $dashboardGuru = $role === 'guru' ? $this->buildGuruDashboard($user->id, $tahunAktif) : [];
        $dashboardWalikelas = $role === 'walikelas' ? $this->buildWalikelasDashboard($user->id, $tahunAktif) : [];

        return view('pages.dashboard.index', compact(
            'user',
            'totalSiswa',
            'totalGuru',
            'totalKelas',
            'totalMapel',
            'tahunAktif',
            'logAktivitas',
            'dashboardGuru',
            'dashboardWalikelas'
        ));
    }

    private function buildGuruDashboard(int $userId, ?TahunAjaran $tahunAktif): array
    {
        $pengampus = GuruPengampu::with(['kelas.siswas', 'mataPelajaran'])
            ->where('guru_id', $userId)
            ->when($tahunAktif, function ($query) use ($tahunAktif) {
                $query->whereHas('kelas', fn ($kelas) => $kelas->where('tahun_ajaran_id', $tahunAktif->id));
            })
            ->get()
            ->unique(fn ($pengampu) => $pengampu->kelas_id.'|'.$pengampu->mapel_id)
            ->values();

        $progress = $pengampus->map(function ($pengampu) use ($tahunAktif) {
            $totalSiswa = Siswa::where('kelas_id', $pengampu->kelas_id)
                ->where('status_aktif', true)
                ->count();

            $dinilai = Nilai::where('mapel_id', $pengampu->mapel_id)
                ->whereNotNull('nilai_akhir')
                ->whereHas('siswa', function ($siswa) use ($pengampu) {
                    $siswa->where('kelas_id', $pengampu->kelas_id)
                        ->where('status_aktif', true);
                })
                ->when($tahunAktif, function ($query) use ($tahunAktif) {
                    $query->whereHas('raport', fn ($raport) => $raport->where('tahun_ajaran_id', $tahunAktif->id));
                })
                ->count();

            $pending = max(0, $totalSiswa - $dinilai);
            $percentage = $totalSiswa > 0 ? round(($dinilai / $totalSiswa) * 100) : 0;

            return [
                'kelas_id' => $pengampu->kelas_id,
                'mapel_id' => $pengampu->mapel_id,
                'kelas' => $pengampu->kelas?->nama_kelas ?? '-',
                'mapel' => $pengampu->mataPelajaran?->nama_mapel ?? $pengampu->mapel_id,
                'total_siswa' => $totalSiswa,
                'dinilai' => $dinilai,
                'pending' => $pending,
                'percentage' => $percentage,
                'bar_class' => $percentage >= 80 ? 'bg-[#16a34a]' : ($percentage >= 50 ? 'bg-[#f59e0b]' : 'bg-[#dc2626]'),
                'text_class' => $percentage >= 80 ? 'text-[#16a34a]' : ($percentage >= 50 ? 'text-[#f59e0b]' : 'text-[#dc2626]'),
                'box_class' => $percentage === 0 ? 'border-red-100 bg-red-50/50 hover:bg-red-50' : 'border-[#e2e8f0] hover:bg-[#f8fafc]',
            ];
        })->sortBy('percentage')->values();

        return [
            'kelas_count' => $pengampus->pluck('kelas_id')->filter()->unique()->count(),
            'mapel_count' => $pengampus->pluck('mapel_id')->filter()->unique()->count(),
            'pending_count' => $progress->sum('pending'),
            'progress' => $progress,
        ];
    }

    private function buildWalikelasDashboard(int $userId, ?TahunAjaran $tahunAktif): array
    {
        $kelasList = Kelas::with('siswas')
            ->where('wali_guru_id', $userId)
            ->when($tahunAktif, fn ($query) => $query->where('tahun_ajaran_id', $tahunAktif->id))
            ->orderBy('nama_kelas')
            ->get();

        $kelasIds = $kelasList->pluck('id');
        $siswaIds = Siswa::whereIn('kelas_id', $kelasIds)
            ->where('status_aktif', true)
            ->pluck('id');

        $totalSiswaWali = $siswaIds->count();

        $raports = Raport::with(['siswa', 'rekapKehadiran'])
            ->whereIn('siswa_id', $siswaIds)
            ->when($tahunAktif, fn ($query) => $query->where('tahun_ajaran_id', $tahunAktif->id))
            ->get();

        $raporFinal = $raports->where('status', 'final')->count();
        $raporPending = max(0, $totalSiswaWali - $raporFinal);
        $raporProgress = $totalSiswaWali > 0 ? round(($raporFinal / $totalSiswaWali) * 100) : 0;

        $nilaiQuery = Nilai::whereHas('siswa', function ($siswa) use ($kelasIds) {
                $siswa->whereIn('kelas_id', $kelasIds)
                    ->where('status_aktif', true);
            })
            ->when($tahunAktif, function ($query) use ($tahunAktif) {
                $query->whereHas('raport', fn ($raport) => $raport->where('tahun_ajaran_id', $tahunAktif->id));
            });

        $rataNilai = round((float) (clone $nilaiQuery)->avg('nilai_akhir'), 2);

        $totalAlpha = RekapKehadiran::whereHas('raport', function ($raport) use ($siswaIds, $tahunAktif) {
                $raport->whereIn('siswa_id', $siswaIds);
                if ($tahunAktif) {
                    $raport->where('tahun_ajaran_id', $tahunAktif->id);
                }
            })
            ->sum('alpha');

        $attentionFromAbsensi = RekapKehadiran::with('raport.siswa')
            ->where('alpha', '>', 0)
            ->whereHas('raport', function ($raport) use ($siswaIds, $tahunAktif) {
                $raport->whereIn('siswa_id', $siswaIds);
                if ($tahunAktif) {
                    $raport->where('tahun_ajaran_id', $tahunAktif->id);
                }
            })
            ->orderByDesc('alpha')
            ->get()
            ->map(function ($rekap) {
                $nama = $rekap->raport?->siswa?->nama_siswa ?? '-';
                return [
                    'initials' => $this->initials($nama),
                    'name' => $nama,
                    'detail' => "Alpha: {$rekap->alpha} hari tanpa keterangan",
                    'badge' => 'KRITIS',
                    'badgeClass' => 'bg-[#fef2f2] text-[#dc2626]',
                ];
            });

        $attentionFromNilai = (clone $nilaiQuery)
            ->with(['siswa', 'mataPelajaran'])
            ->whereNotNull('nilai_akhir')
            ->get()
            ->filter(fn ($nilai) => $nilai->mataPelajaran && (float) $nilai->nilai_akhir < (float) $nilai->mataPelajaran->kkm)
            ->sortBy('nilai_akhir')
            ->map(function ($nilai) {
                $nama = $nilai->siswa?->nama_siswa ?? '-';
                $mapel = $nilai->mataPelajaran?->nama_mapel ?? 'Mapel';
                $kkm = number_format((float) $nilai->mataPelajaran?->kkm, 2);
                $akhir = number_format((float) $nilai->nilai_akhir, 2);

                return [
                    'initials' => $this->initials($nama),
                    'name' => $nama,
                    'detail' => "{$mapel}: {$akhir} di bawah KKM {$kkm}",
                    'badge' => 'PERINGATAN',
                    'badgeClass' => 'bg-[#eff6ff] text-[#1d4ed8]',
                ];
            });

        return [
            'kelas_names' => $kelasList->pluck('nama_kelas')->implode(', ') ?: '-',
            'total_siswa' => $totalSiswaWali,
            'rata_nilai' => $rataNilai,
            'alpha_count' => $totalAlpha,
            'rapor_final' => $raporFinal,
            'rapor_pending' => $raporPending,
            'rapor_progress' => $raporProgress,
            'attention_items' => $attentionFromAbsensi->merge($attentionFromNilai)->take(3)->values(),
        ];
    }

    private function initials(string $name): string
    {
        return collect(explode(' ', trim($name)))
            ->filter()
            ->take(2)
            ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
            ->implode('') ?: '-';
    }
}
