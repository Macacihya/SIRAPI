<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AkademikController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjarans = TahunAjaran::orderByDesc('tahun_mulai')
            ->orderByRaw("CASE WHEN semester = 'Ganjil' THEN 0 ELSE 1 END")
            ->get();

        $activeTahun = $tahunAjarans->where('is_active', true)->first();
        $tahunAjaranId = $request->input('tahun_ajaran_id', $activeTahun?->id ?? $tahunAjarans->first()?->id);

        $selectedTahun = null;
        if ($tahunAjaranId) {
            $selectedTahun = $tahunAjarans->where('id', $tahunAjaranId)->first();
        }

        // Get classes for selected period
        $kelasRaw = collect();
        if ($tahunAjaranId) {
            $kelasRaw = Kelas::where('tahun_ajaran_id', $tahunAjaranId)
                ->with(['siswas', 'waliGuru.user'])
                ->orderBy('nama_kelas')
                ->get();
        }

        // Format classes for Alpine template in view
        $kelasFormatted = $kelasRaw->map(function ($k) {
            $siswaNames = $k->siswas->pluck('nama_siswa')->toArray();
            $count = count($siswaNames);
            $max = 32;
            $pct = $max > 0 ? Math_round(($count / $max) * 100) : 0;
            $waliNama = $k->waliGuru && $k->waliGuru->user ? $k->waliGuru->user->nama : '-';

            return [
                'id' => $k->id,
                'nama' => $k->nama_kelas,
                'jurusan' => $k->tingkat ? 'Tingkat ' . $k->tingkat : 'Umum',
                'kapasitas' => "{$count} / {$max}",
                'max' => $max,
                'pct' => $pct,
                'wali' => "Wali Kelas: {$waliNama}",
                'siswa' => $siswaNames,
            ];
        });

        // Get unassigned students for the selected period
        // Unassigned means: status_aktif is true AND (kelas_id is null OR the class it belongs to is NOT in this tahun_ajaran)
        $antreanRaw = collect();
        if ($tahunAjaranId) {
            $antreanRaw = Siswa::where('status_aktif', true)
                ->where(function ($q) use ($tahunAjaranId) {
                    $q->whereNull('kelas_id')
                      ->orWhereHas('kelas', function ($qk) use ($tahunAjaranId) {
                          $qk->where('tahun_ajaran_id', '!=', $tahunAjaranId);
                      });
                })
                ->orderBy('nama_siswa')
                ->get();
        }

        $antreanFormatted = $antreanRaw->map(function ($s) {
            // Get first 2 letters of name for initials
            $words = explode(' ', trim($s->nama_siswa));
            $init = '';
            if (count($words) >= 2) {
                $init = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
            } else {
                $init = strtoupper(substr($s->nama_siswa, 0, 2));
            }

            return [
                'init' => $init,
                'name' => $s->nama_siswa,
                'nisn' => $s->nisn,
                'gender' => $s->jenis_kelamin === 'Pria' ? 'Laki-laki' : ($s->jenis_kelamin === 'Wanita' ? 'Perempuan' : ($s->jenis_kelamin === 'L' ? 'Laki-laki' : ($s->jenis_kelamin === 'P' ? 'Perempuan' : '-'))),
                'peminatan' => $s->kelas ? 'Pindahan (' . $s->kelas->nama_kelas . ')' : 'Baru',
            ];
        });

        // Get list of teachers for class assignment dropdown
        $daftarGuru = Guru::with('user')->get()->map(function ($g) {
            return [
                'id' => $g->user_id,
                'nama' => ($g->user ? $g->user->nama : 'Unknown') . ($g->nip ? " (NIP: {$g->nip})" : ''),
            ];
        })->toArray();

        return view('pages.akademik.index', [
            'tahunAjarans' => $tahunAjarans,
            'tahunAjaranId' => $tahunAjaranId,
            'selectedTahun' => $selectedTahun,
            'kelas' => $kelasFormatted,
            'antrean' => $antreanFormatted,
            'daftarGuru' => $daftarGuru,
        ]);
    }

    public function storeTahunAjaran(Request $request)
    {
        $validated = $request->validate([
            'tahun_mulai' => ['required', 'integer', 'digits:4', 'min:2000'],
            'semester' => ['required', Rule::in(['Ganjil', 'Genap'])],
            'is_active' => ['nullable', 'boolean'],
            'tahun_selesai' => [
                'required',
                'integer',
                'digits:4',
                'min:2001',
                Rule::unique('tahun_ajarans')
                    ->where(fn ($query) => $query
                        ->where('tahun_mulai', $request->tahun_mulai)
                        ->where('semester', $request->semester)),
            ],
        ], [
            'tahun_selesai.required' => 'Tahun selesai wajib diisi.',
            'tahun_selesai.unique' => 'Kombinasi tahun ajaran dan semester sudah ada.',
        ]);

        if ((int) $validated['tahun_selesai'] !== (int) $validated['tahun_mulai'] + 1) {
            return back()
                ->withErrors(['tahun_selesai' => 'Tahun selesai harus satu tahun setelah tahun mulai.'])
                ->withInput();
        }

        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['is_active']) {
            TahunAjaran::query()->update(['is_active' => false]);
        }

        $ta = TahunAjaran::create($validated);

        return redirect()
            ->route('akademik', ['tahun_ajaran_id' => $ta->id])
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function updateTahunAjaran(Request $request, $id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        $validated = $request->validate([
            'tahun_mulai' => ['required', 'integer', 'digits:4', 'min:2000'],
            'semester' => ['required', Rule::in(['Ganjil', 'Genap'])],
            'is_active' => ['nullable', 'boolean'],
            'tahun_selesai' => [
                'required',
                'integer',
                'digits:4',
                'min:2001',
                Rule::unique('tahun_ajarans')
                    ->where(fn ($query) => $query
                        ->where('tahun_mulai', $request->tahun_mulai)
                        ->where('semester', $request->semester))
                    ->ignore($id),
            ],
        ], [
            'tahun_selesai.required' => 'Tahun selesai wajib diisi.',
            'tahun_selesai.unique' => 'Kombinasi tahun ajaran dan semester sudah ada.',
        ]);

        if ((int) $validated['tahun_selesai'] !== (int) $validated['tahun_mulai'] + 1) {
            return back()
                ->withErrors(['tahun_selesai' => 'Tahun selesai harus satu tahun setelah tahun mulai.'])
                ->withInput();
        }

        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['is_active']) {
            TahunAjaran::query()
                ->whereKeyNot($tahunAjaran->id)
                ->update(['is_active' => false]);
        }

        $tahunAjaran->update($validated);

        return redirect()
            ->route('akademik', ['tahun_ajaran_id' => $tahunAjaran->id])
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroyTahunAjaran($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        $tahunAjaran->delete();

        return redirect()
            ->route('akademik')
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    public function setActiveTahunAjaran($id)
    {
        TahunAjaran::query()->update(['is_active' => false]);
        $ta = TahunAjaran::findOrFail($id);
        $ta->update(['is_active' => true]);

        return redirect()
            ->route('akademik', ['tahun_ajaran_id' => $id])
            ->with('success', 'Periode akademik aktif berhasil diubah.');
    }

    public function storeKelas(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'tingkat' => 'required|string|max:10',
            'wali_guru_id' => 'required|exists:gurus,user_id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ]);

        Kelas::create([
            'nama_kelas' => $validated['nama_kelas'],
            'tingkat' => $validated['tingkat'],
            'wali_guru_id' => $validated['wali_guru_id'],
            'tahun_ajaran_id' => $validated['tahun_ajaran_id'],
        ]);

        return redirect()
            ->route('akademik', ['tahun_ajaran_id' => $validated['tahun_ajaran_id']])
            ->with('success', 'Kelas baru berhasil ditambahkan.');
    }

    public function runPlottingOtomatis(Request $request)
    {
        $tahunAjaranId = $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ])['tahun_ajaran_id'];

        // Get unassigned students
        $siswaUnassigned = Siswa::where('status_aktif', true)
            ->where(function ($q) use ($tahunAjaranId) {
                $q->whereNull('kelas_id')
                  ->orWhereHas('kelas', function ($qk) use ($tahunAjaranId) {
                      $qk->where('tahun_ajaran_id', '!=', $tahunAjaranId);
                  });
            })
            ->get();

        // Get classes for this year period with current student counts
        $classes = Kelas::where('tahun_ajaran_id', $tahunAjaranId)
            ->withCount('siswas')
            ->get();

        if ($classes->isEmpty()) {
            return redirect()
                ->route('akademik', ['tahun_ajaran_id' => $tahunAjaranId])
                ->withErrors(['general' => 'Gagal plotting: Belum ada kelas yang dibuat untuk periode ini.']);
        }

        // We will assign students up to capacity of 32
        $placed = 0;
        $classCapacityMap = $classes->pluck('siswas_count', 'id')->toArray();

        foreach ($siswaUnassigned as $siswa) {
            // Find class with available capacity (< 32)
            $targetClassId = null;
            foreach ($classCapacityMap as $classId => $count) {
                if ($count < 32) {
                    $targetClassId = $classId;
                    break;
                }
            }

            if ($targetClassId) {
                $siswa->update(['kelas_id' => $targetClassId]);
                $classCapacityMap[$targetClassId]++;
                $placed++;
            } else {
                // All classes full
                break;
            }
        }

        return redirect()
            ->route('akademik', ['tahun_ajaran_id' => $tahunAjaranId])
            ->with('success', $placed > 0 ? "{$placed} siswa berhasil diplotting secara otomatis." : "Tidak ada siswa yang berhasil diplotting (kelas penuh atau tidak ada siswa).");
    }
}

// Simple Helper function
function Math_round($val) {
    return (int) round($val);
}
