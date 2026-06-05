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
    /**
     * Menampilkan dashboard manajemen akademik.
     * Mengambil data tahun ajaran, daftar kelas, siswa yang belum masuk kelas, dan daftar guru.
     */
    public function index(Request $request)
    {
        // Mengambil semua data tahun ajaran diurutkan dari tahun terbaru
        $tahunAjarans = TahunAjaran::orderByDesc('tahun_mulai')
            ->orderByRaw("CASE WHEN semester = 'Ganjil' THEN 0 ELSE 1 END")
            ->get();

        // Mencari tahun ajaran yang saat ini aktif
        $activeTahun = $tahunAjarans->where('is_active', true)->first();
        // Menentukan ID tahun ajaran yang sedang dipilih di filter halaman (default: tahun ajaran aktif)
        $tahunAjaranId = $request->input('tahun_ajaran_id', $activeTahun?->id ?? $tahunAjarans->first()?->id);

        $selectedTahun = null;
        if ($tahunAjaranId) {
            $selectedTahun = $tahunAjarans->where('id', $tahunAjaranId)->first();
        }

        // Mengambil kelas-kelas yang terdaftar pada tahun ajaran yang dipilih
        $kelasRaw = collect();
        if ($tahunAjaranId) {
            $kelasRaw = Kelas::where('tahun_ajaran_id', $tahunAjaranId)
                ->with(['siswas', 'waliGuru.user'])
                ->orderBy('nama_kelas')
                ->get();
        }

        // Format data kelas agar siap dipakai oleh template tampilan (Alpine.js)
        $kelasFormatted = $kelasRaw->map(function ($k) {
            $siswaNames = $k->siswas->pluck('nama_siswa')->toArray();
            $count = count($siswaNames);
            $max = 32; // Kapasitas maksimal per kelas
            $pct = $max > 0 ? Math_round(($count / $max) * 100) : 0; // Persentase keterisian kelas
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

        // Mengambil siswa aktif yang BELUM ditempatkan di kelas manapun pada periode tahun ajaran yang dipilih
        $antreanRaw = collect();
        if ($tahunAjaranId) {
            $antreanRaw = Siswa::where('status_aktif', true)
                ->where(function ($q) use ($tahunAjaranId) {
                    // Kriteria belum ditempatkan: kelas_id masih kosong (NULL) ATAU kelas lamanya bukan di tahun ajaran aktif ini
                    $q->whereNull('kelas_id')
                      ->orWhereHas('kelas', function ($qk) use ($tahunAjaranId) {
                          $qk->where('tahun_ajaran_id', '!=', $tahunAjaranId);
                      });
                })
                ->orderBy('nama_siswa')
                ->get();
        }

        // Format data antrean siswa untuk tampilan Alpine.js
        $antreanFormatted = $antreanRaw->map(function ($s) {
            // Membuat inisial nama siswa (contoh: "Budi Santoso" -> "BS")
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

        // Mengambil semua data guru untuk dijadikan pilihan Wali Kelas di dropdown
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

    /**
     * Menyimpan data Tahun Ajaran & Semester baru ke database.
     */
    public function storeTahunAjaran(Request $request)
    {
        // Validasi input data dari form
        $validated = $request->validate([
            'tahun_mulai' => ['required', 'integer', 'digits:4', 'min:2000'],
            'semester' => ['required', Rule::in(['Ganjil', 'Genap'])],
            'is_active' => ['nullable', 'boolean'],
            'tahun_selesai' => [
                'required',
                'integer',
                'digits:4',
                'min:2001',
                // Validasi agar kombinasi tahun_mulai + semester tidak boleh ganda di database
                Rule::unique('tahun_ajarans')
                    ->where(fn ($query) => $query
                        ->where('tahun_mulai', $request->tahun_mulai)
                        ->where('semester', $request->semester)),
            ],
        ], [
            'tahun_selesai.required' => 'Tahun selesai wajib diisi.',
            'tahun_selesai.unique' => 'Kombinasi tahun ajaran dan semester sudah ada.',
        ]);

        // Memastikan tahun selesai harus t+1 dari tahun mulai (misal: mulai 2025 selesai harus 2026)
        if ((int) $validated['tahun_selesai'] !== (int) $validated['tahun_mulai'] + 1) {
            return back()
                ->withErrors(['tahun_selesai' => 'Tahun selesai harus satu tahun setelah tahun mulai.'])
                ->withInput();
        }

        $validated['is_active'] = $request->boolean('is_active');

        // Jika tahun ajaran baru ini diset aktif, matikan status aktif tahun ajaran yang lain terlebih dahulu
        if ($validated['is_active']) {
            TahunAjaran::query()->update(['is_active' => false]);
        }

        // Menyimpan data tahun ajaran baru ke database
        $ta = TahunAjaran::create($validated);

        return redirect()
            ->route('akademik', ['tahun_ajaran_id' => $ta->id])
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    /**
     * Memperbarui data Tahun Ajaran & Semester yang sudah ada di database.
     */
    public function updateTahunAjaran(Request $request, $id)
    {
        // Cari data tahun ajaran berdasarkan ID
        $tahunAjaran = TahunAjaran::findOrFail($id);

        // Validasi input pembaruan data
        $validated = $request->validate([
            'tahun_mulai' => ['required', 'integer', 'digits:4', 'min:2000'],
            'semester' => ['required', Rule::in(['Ganjil', 'Genap'])],
            'is_active' => ['nullable', 'boolean'],
            'tahun_selesai' => [
                'required',
                'integer',
                'digits:4',
                'min:2001',
                // Validasi agar kombinasi tahun_mulai + semester tidak ganda (abaikan ID tahun ajaran yang sedang diubah)
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

        // Memastikan tahun selesai harus t+1 dari tahun mulai
        if ((int) $validated['tahun_selesai'] !== (int) $validated['tahun_mulai'] + 1) {
            return back()
                ->withErrors(['tahun_selesai' => 'Tahun selesai harus satu tahun setelah tahun mulai.'])
                ->withInput();
        }

        $validated['is_active'] = $request->boolean('is_active');

        // Jika diset sebagai periode aktif, nonaktifkan periode akademik yang lain
        if ($validated['is_active']) {
            TahunAjaran::query()
                ->whereKeyNot($tahunAjaran->id)
                ->update(['is_active' => false]);
        }

        // Update data di database
        $tahunAjaran->update($validated);

        return redirect()
            ->route('akademik', ['tahun_ajaran_id' => $tahunAjaran->id])
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    /**
     * Menghapus data Tahun Ajaran & Semester dari database.
     */
    public function destroyTahunAjaran($id)
    {
        // Cari data berdasarkan ID, lalu hapus
        $tahunAjaran = TahunAjaran::findOrFail($id);
        $tahunAjaran->delete();

        return redirect()
            ->route('akademik')
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    /**
     * Mengatur status periode akademik aktif yang baru.
     */
    public function setActiveTahunAjaran($id)
    {
        // 1. Matikan semua periode akademik yang tadinya berstatus aktif (set is_active = false)
        TahunAjaran::query()->update(['is_active' => false]);
        
        // 2. Cari data terpilih berdasarkan ID, kemudian ubah statusnya menjadi aktif (set is_active = true)
        $ta = TahunAjaran::findOrFail($id);
        $ta->update(['is_active' => true]);

        return redirect()
            ->route('akademik', ['tahun_ajaran_id' => $id])
            ->with('success', 'Periode akademik aktif berhasil diubah.');
    }

    /**
     * Membuat kelas baru pada periode akademik tertentu.
     */
    public function storeKelas(Request $request)
    {
        // Validasi input form pembuatan kelas
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'tingkat' => 'required|string|max:10',
            'wali_guru_id' => 'required|exists:gurus,user_id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ]);

        // Menyimpan kelas baru ke database
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

    /**
     * Melakukan plotting/pembagian siswa ke kelas secara otomatis.
     * Mengisi kelas-kelas yang masih kosong atau belum penuh (kapasitas < 32 siswa).
     */
    public function runPlottingOtomatis(Request $request)
    {
        // Pastikan ID tahun ajaran yang dipilih valid
        $tahunAjaranId = $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ])['tahun_ajaran_id'];

        // 1. Mengambil data seluruh siswa aktif yang saat ini belum mendapatkan kelas di tahun ajaran terpilih
        $siswaUnassigned = Siswa::where('status_aktif', true)
            ->where(function ($q) use ($tahunAjaranId) {
                $q->whereNull('kelas_id')
                  ->orWhereHas('kelas', function ($qk) use ($tahunAjaranId) {
                      $qk->where('tahun_ajaran_id', '!=', $tahunAjaranId);
                  });
            })
            ->get();

        // 2. Mengambil semua kelas yang tersedia pada periode tahun ajaran ini beserta jumlah siswanya
        $classes = Kelas::where('tahun_ajaran_id', $tahunAjaranId)
            ->withCount('siswas')
            ->get();

        // Cek jika belum ada kelas sama sekali
        if ($classes->isEmpty()) {
            return redirect()
                ->route('akademik', ['tahun_ajaran_id' => $tahunAjaranId])
                ->withErrors(['general' => 'Gagal plotting: Belum ada kelas yang dibuat untuk periode ini.']);
        }

        // Variabel untuk melacak jumlah siswa yang berhasil ditempatkan
        $placed = 0;
        // Peta kapasitas kelas saat ini (ID Kelas => Jumlah Siswa di dalamnya)
        $classCapacityMap = $classes->pluck('siswas_count', 'id')->toArray();

        // 3. Distribusikan siswa ke kelas secara berurutan
        foreach ($siswaUnassigned as $siswa) {
            $targetClassId = null;
            // Cari kelas pertama yang kuotanya masih kurang dari 32 siswa
            foreach ($classCapacityMap as $classId => $count) {
                if ($count < 32) {
                    $targetClassId = $classId;
                    break;
                }
            }

            // Jika ada kelas yang longgar, tempatkan siswa tersebut
            if ($targetClassId) {
                $siswa->update(['kelas_id' => $targetClassId]);
                $classCapacityMap[$targetClassId]++; // Tambah kuota penghuni kelas tersebut di memori
                $placed++;
            } else {
                // Berhenti jika semua kelas sudah penuh (maksimal 32 siswa per kelas)
                break;
            }
        }

        return redirect()
            ->route('akademik', ['tahun_ajaran_id' => $tahunAjaranId])
            ->with('success', $placed > 0 ? "{$placed} siswa berhasil diplotting secara otomatis." : "Tidak ada siswa yang berhasil diplotting (kelas penuh atau tidak ada siswa).");
    }
}

/**
 * Fungsi pembantu sederhana untuk membulatkan angka desimal.
 */
function Math_round($val) {
    return (int) round($val);
}
