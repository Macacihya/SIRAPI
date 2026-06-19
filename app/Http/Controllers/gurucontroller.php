<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Sekolah;
use App\Models\MataPelajaran;
use App\Services\GuruAssignmentService;
use App\Services\GuruCsvService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class GuruController extends Controller
{
    // Konstruktor dengan dependency injection untuk service pengajaran & CSV
    public function __construct(
        private GuruAssignmentService $assignmentService,
        private GuruCsvService $csvService
    ) {
    }

    // Mengarahkan request index ke method tampilkan
    public function index(Request $request)
    {
        return $this->tampilkan($request);
    }

    // Menampilkan halaman utama manajemen guru beserta data-data pendukung
    public function tampilkan(Request $request)
    {
        $query = Guru::with(['user', 'guruPengampus.mataPelajaran', 'guruPengampus.kelas', 'kelasWali', 'sekolah']);

        // Pencarian berdasarkan nama, email, atau NIP
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('nama', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $gurus = $query->paginate(10)->withQueryString();

        // Transformasi koleksi data guru untuk kebutuhan tampilan Alpine.js
        $gurus->getCollection()->transform(fn($guru) => (object) $this->assignmentService->buildGuruRow($guru));

        // Mendapatkan daftar mata pelajaran untuk dropdown form
        $daftarMapel = MataPelajaran::orderBy('nama_mapel')
            ->get(['kode_mapel', 'nama_mapel'])
            ->map(fn($mapel) => [
                'id' => $mapel->kode_mapel,
                'nama' => $mapel->nama_mapel,
            ])
            ->values();

        // Mendapatkan daftar kelas untuk dropdown form
        $daftarKelas = Kelas::orderBy('nama_kelas')
            ->get(['id', 'nama_kelas', 'wali_guru_id'])
            ->map(fn($kelas) => [
                'id' => $kelas->id,
                'nama' => $kelas->nama_kelas,
                'wali_guru_id' => $kelas->wali_guru_id,
            ])
            ->values();

        // Mendapatkan daftar user dengan role guru/walikelas yang belum terhubung dengan data guru
        $daftarUserGuru = User::whereHas('roles', fn($q) => $q->whereIn('nama_role', ['guru', 'walikelas']))
            ->with(['guru.guruPengampus', 'guru.kelasWali'])
            ->where(function ($query) {
                $query->whereDoesntHave('guru')
                    ->orWhereHas('guru', function ($guru) {
                        $guru->whereDoesntHave('guruPengampus')
                            ->whereDoesntHave('kelasWali');
                    });
            })
            ->with('roles')
            ->orderBy('nama')
            ->get(['id', 'nama', 'email', 'username'])
            ->map(fn($user) => [
                'id'       => $user->id,
                'name'     => $user->nama,
                'email'    => $user->email,
                'username' => $user->username,
                'nip'      => $user->guru->nip ?? '',
                'roles'    => $user->roles->contains('nama_role', 'walikelas') ? ['WALI KELAS'] : ['GURU MAPEL'],
            ])
            ->values();

        $sekolahs = Sekolah::all();
        $riwayatGuru = \App\Models\RiwayatStatusGuru::with('guru.user')->orderBy('tanggal_perubahan', 'desc')->take(20)->get();

        return view('pages.guru-tendik.index', compact('gurus', 'daftarMapel', 'daftarKelas', 'daftarUserGuru', 'sekolahs', 'riwayatGuru'));
    }

    // Menampilkan form tambah guru tradisional (sebagai fallback)
    public function create()
    {
        $daftarMapel = MataPelajaran::pluck('nama_mapel', 'kode_mapel')->toArray();
        $sekolahs = Sekolah::all();
        return view('pages.guru.create', compact('daftarMapel', 'sekolahs'));
    }

    // Export data guru ke file CSV
    public function export()
    {
        return $this->csvService->export();
    }

    // Import data guru dari file CSV
    public function import(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        if (!Sekolah::exists()) {
            return back()->withErrors(['file' => 'Belum ada data sekolah terdaftar.']);
        }

        $imported = $this->csvService->import($validated['file']);

        return redirect()
            ->route('guru-tendik')
            ->with('success', "Import selesai. {$imported} data guru diproses.");
    }

    // AJAX Methods (Response JSON) 

    // Menyimpan data guru baru via request AJAX
    public function storeAjax(Request $request)
    {
        try {
            $selectedUserId = $request->input('user_id');
            $existingGuruUserId = $selectedUserId ? Guru::where('user_id', $selectedUserId)->value('user_id') : null;

            $validated = $request->validate([
                'nama'                => 'required|string|max:255',
                'user_id'             => 'nullable|exists:users,id',
                'email'               => ['nullable', 'email', Rule::unique('users', 'email')->ignore($request->input('user_id'))],
                'username'            => ['nullable', 'string', Rule::unique('users', 'username')->ignore($request->input('user_id'))],
                'nip'                 => ['required', 'numeric', 'digits_between:1,18', Rule::unique('gurus', 'nip')->ignore($existingGuruUserId, 'user_id')],
                'peran'               => 'required|in:GURU MAPEL,WALI KELAS,GURU MAPEL & WALI KELAS',
                'sekolah_id'          => 'nullable|exists:sekolahs,id',
                'mapel_ids'           => 'nullable|array',
                'mapel_ids.*'         => 'exists:mata_pelajarans,kode_mapel',
                'kelas_pengampu_ids'  => 'nullable|array',
                'kelas_pengampu_ids.*'=> 'exists:kelas,id',
                'kelas_wali_id'       => 'nullable|exists:kelas,id',
            ]);

            $this->assignmentService->validatePengampuSelection($validated);
            $this->assignmentService->validateKelasWaliSelection($validated, $existingGuruUserId);

            $guru = $this->assignmentService->prosesSimpan($validated);
            $guru->load(['user', 'guruPengampus.mataPelajaran', 'guruPengampus.kelas', 'kelasWali']);
            
            return response()->json([
                'message' => 'Data guru berhasil ditambahkan.',
                'guru'    => $this->assignmentService->buildGuruRow($guru)
            ]);

        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validasi gagal.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // Mengubah data guru via request AJAX
    public function updateAjax(Request $request, $id)
    {
        try {
            $guru = Guru::with('user')->findOrFail($id);

            $validated = $request->validate([
                'nama'                => 'required|string|max:255',
                'email'               => 'required|email|unique:users,email,' . $guru->user_id,
                'peran'               => 'required|in:GURU MAPEL,WALI KELAS,GURU MAPEL & WALI KELAS',
                'sekolah_id'          => 'nullable|exists:sekolahs,id',
                'mapel_ids'           => 'nullable|array',
                'mapel_ids.*'         => 'exists:mata_pelajarans,kode_mapel',
                'kelas_pengampu_ids'  => 'nullable|array',
                'kelas_pengampu_ids.*'=> 'exists:kelas,id',
                'kelas_wali_id'       => 'nullable|exists:kelas,id',
            ]);

            $this->assignmentService->validatePengampuSelection($validated);
            $this->assignmentService->validateKelasWaliSelection($validated, $guru->user_id);

            $this->assignmentService->prosesUpdate($guru, $validated);
            $guru->load(['user', 'guruPengampus.mataPelajaran', 'guruPengampus.kelas', 'kelasWali']);

            return response()->json([
                'message' => 'Data guru berhasil diperbarui.',
                'guru'    => $this->assignmentService->buildGuruRow($guru)
            ]);

        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validasi gagal.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // Menghapus data guru via request AJAX
    public function destroyAjax($id)
    {
        try {
            $guru = Guru::findOrFail($id);
            $this->assignmentService->prosesHapus($guru);
            return response()->json(['message' => 'Data guru berhasil dihapus.', 'id' => (int) $id]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // Menghapus banyak data guru sekaligus via request AJAX
    public function bulkDestroyAjax(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            if (empty($ids)) {
                return response()->json(['message' => 'Tidak ada data guru yang dipilih.'], 400);
            }

            foreach ($ids as $id) {
                $guru = Guru::find($id);
                if ($guru) {
                    $this->assignmentService->prosesHapus($guru);
                }
            }

            return response()->json(['message' => 'Semua data guru terpilih berhasil dihapus.', 'ids' => $ids]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // Standard Form Methods (Fallback Form Submit)

    // Menyimpan data guru baru via form submit biasa
    public function store(Request $request)
    {
        $selectedUserId = $request->input('user_id');
        $existingGuruUserId = $selectedUserId ? Guru::where('user_id', $selectedUserId)->value('user_id') : null;

        $validated = $request->validate([
            'nama'                 => 'required|string|max:255',
            'user_id'              => 'nullable|exists:users,id',
            'email'                => ['required_without:user_id', 'email', Rule::unique('users', 'email')->ignore($request->input('user_id'))],
            'username'             => ['required_without:user_id', 'string', Rule::unique('users', 'username')->ignore($request->input('user_id'))],
            'nip'                  => ['required', 'numeric', 'digits_between:1,18', Rule::unique('gurus', 'nip')->ignore($existingGuruUserId, 'user_id')],
            'peran'                => 'required|in:GURU MAPEL,WALI KELAS,GURU MAPEL & WALI KELAS',
            'sekolah_id'           => 'nullable|exists:sekolahs,id',
            'jabatan'              => 'nullable|string|max:255',
            'mapel_ids'            => 'nullable|array',
            'mapel_ids.*'          => 'exists:mata_pelajarans,kode_mapel',
            'kelas_pengampu_ids'   => 'nullable|array',
            'kelas_pengampu_ids.*' => 'exists:kelas,id',
            'kelas_wali_id'        => 'nullable|exists:kelas,id',
        ]);

        $this->assignmentService->validatePengampuSelection($validated);
        $this->assignmentService->validateKelasWaliSelection($validated, $existingGuruUserId);

        try {
            $this->assignmentService->prosesSimpan($validated);
        } catch (\Exception $e) {
            return back()->withErrors(['sekolah_id' => $e->getMessage()]);
        }

        return redirect()
            ->route('guru-tendik')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    // Mengubah data guru via form submit biasa
    public function update(Request $request, $id)
    {
        $guru = Guru::with('user')->findOrFail($id);

        $validated = $request->validate([
            'nama'                 => 'required|string|max:255',
            'email'                => 'required|email|unique:users,email,' . $guru->user_id,
            'peran'                => 'required|in:GURU MAPEL,WALI KELAS,GURU MAPEL & WALI KELAS',
            'sekolah_id'           => 'nullable|exists:sekolahs,id',
            'jabatan'              => 'nullable|string|max:255',
            'mapel_ids'            => 'nullable|array',
            'mapel_ids.*'          => 'exists:mata_pelajarans,kode_mapel',
            'kelas_pengampu_ids'   => 'nullable|array',
            'kelas_pengampu_ids.*' => 'exists:kelas,id',
            'kelas_wali_id'        => 'nullable|exists:kelas,id',
        ]);

        $this->assignmentService->validatePengampuSelection($validated);
        $this->assignmentService->validateKelasWaliSelection($validated, $guru->user_id);

        $this->assignmentService->prosesUpdate($guru, $validated);

        return redirect()
            ->route('guru-tendik')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    // Menghapus data guru via request submit biasa
    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        $this->assignmentService->prosesHapus($guru);

        return redirect()
            ->route('guru-tendik')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}
