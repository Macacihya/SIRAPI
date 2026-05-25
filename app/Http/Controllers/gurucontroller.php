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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class GuruController extends Controller
{
    public function __construct(
        private GuruAssignmentService $assignmentService,
        private GuruCsvService $csvService
    ) {
    }

    public function index(Request $request)
    {
        return $this->tampilkan($request);
    }

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

        // Transform agar field sesuai dengan yg dipakai di view Alpine.js
        $gurus->getCollection()->transform(function ($guru) {
            $mapels = $guru->guruPengampus
                ->map(fn($gp) => $gp->mataPelajaran->nama_mapel ?? '-')
                ->unique()
                ->implode(', ');
            $kelasDiampu = $guru->guruPengampus
                ->map(fn($gp) => $gp->kelas->nama_kelas ?? null)
                ->filter()
                ->unique()
                ->implode(', ');
            $kelasWali = $guru->kelasWali
                ->pluck('nama_kelas')
                ->filter()
                ->implode(', ');
            $roles = [];
            if ($guru->guruPengampus->isNotEmpty()) {
                $roles[] = 'GURU MAPEL';
            }
            if ($guru->kelasWali->isNotEmpty()) {
                $roles[] = 'WALI KELAS';
            }
            if (empty($roles)) {
                $roles[] = ($guru->user->role ?? 'guru') === 'walikelas' ? 'WALI KELAS' : 'GURU MAPEL';
            }

            return (object)[
                'id'                  => $guru->user_id,
                'name'                => $guru->user->nama ?? '-',
                'nama'                => $guru->user->nama ?? '-',
                'email'               => $guru->user->email ?? '-',
                'nip'                 => $guru->nip,
                'peran'               => in_array('WALI KELAS', $roles, true) ? 'GURU MAPEL & WALI KELAS' : 'GURU MAPEL',
                'roles'               => $roles,
                'mapel'               => $mapels ?: '-',
                'mapel_ids'           => $guru->guruPengampus->pluck('mapel_id')->unique()->values()->all(),
                'kelas_diampu'        => $kelasDiampu ?: '-',
                'kelas_pengampu_ids'  => $guru->guruPengampus->pluck('kelas_id')->unique()->values()->all(),
                'kelas_walikelas'     => $kelasWali ?: '-',
                'kelas_wali_id'       => $guru->kelasWali->pluck('id')->first(),
            ];
        });

        // Daftar mapel untuk dropdown di form
        $daftarMapel = MataPelajaran::orderBy('nama_mapel')
            ->get(['kode_mapel', 'nama_mapel'])
            ->map(fn($mapel) => [
                'id' => $mapel->kode_mapel,
                'nama' => $mapel->nama_mapel,
            ])
            ->values();
        $daftarKelas = Kelas::orderBy('nama_kelas')
            ->get(['id', 'nama_kelas', 'wali_guru_id'])
            ->map(fn($kelas) => [
                'id' => $kelas->id,
                'nama' => $kelas->nama_kelas,
                'wali_guru_id' => $kelas->wali_guru_id,
            ])
            ->values();
        $daftarUserGuru = User::whereIn('role', ['guru', 'walikelas'])
            ->with(['guru.guruPengampus', 'guru.kelasWali'])
            ->where(function ($query) {
                $query->whereDoesntHave('guru')
                    ->orWhereHas('guru', function ($guru) {
                        $guru->whereDoesntHave('guruPengampus')
                            ->whereDoesntHave('kelasWali');
                    });
            })
            ->orderBy('nama')
            ->get(['id', 'nama', 'email', 'username', 'role'])
            ->map(fn($user) => [
                'id' => $user->id,
                'name' => $user->nama,
                'email' => $user->email,
                'username' => $user->username,
                'nip' => $user->guru->nip ?? '',
                'roles' => [$user->role === 'walikelas' ? 'WALI KELAS' : 'GURU MAPEL'],
            ])
            ->values();
        $sekolahs = Sekolah::all();

        // Riwayat status guru
        $riwayatGuru = \App\Models\RiwayatStatusGuru::with('guru.user')->orderBy('tanggal_perubahan', 'desc')->take(20)->get();

        return view('pages.guru-tendik.index', compact('gurus', 'daftarMapel', 'daftarKelas', 'daftarUserGuru', 'sekolahs', 'riwayatGuru'));
    }

    public function create()
    {
        $daftarMapel = MataPelajaran::pluck('nama_mapel', 'kode_mapel')->toArray();
        $sekolahs = Sekolah::all();
        return view('pages.guru.create', compact('daftarMapel', 'sekolahs'));
    }

    public function export()
    {
        return $this->csvService->export();
    }

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

    // ─── AJAX Methods (mengembalikan JSON) ───────────────────────────────────

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

            $role     = str_contains($validated['peran'], 'WALI KELAS') ? 'walikelas' : 'guru';
            $sekolahId = $validated['sekolah_id'] ?? (Sekolah::first()->id ?? null);

            if (!$sekolahId) {
                return response()->json(['message' => 'Belum ada data sekolah. Daftarkan sekolah terlebih dahulu.'], 422);
            }

            $guruResult = null;
            DB::transaction(function () use ($validated, $role, $sekolahId, &$guruResult) {
                if (!empty($validated['user_id'])) {
                    $user = User::whereIn('role', ['guru', 'walikelas'])
                        ->findOrFail($validated['user_id']);
                    $user->update(['nama' => $validated['nama'], 'role' => $role]);
                } else {
                    $user = User::create([
                        'nama'     => $validated['nama'],
                        'email'    => $validated['email'],
                        'username' => $validated['username'] ?? $this->assignmentService->uniqueUsername($validated['email']),
                        'password' => Hash::make($validated['nip']),
                        'role'     => $role,
                    ]);
                }

                $guruData = ['user_id' => $user->id, 'nip' => $validated['nip'], 'sekolah_id' => $sekolahId];
                if (Schema::hasColumn('gurus', 'jabatan')) {
                    $guruData['jabatan'] = str_contains($validated['peran'], 'WALI KELAS') ? 'Guru Mapel & Wali Kelas' : 'Guru Mapel';
                }
                $guru = Guru::updateOrCreate(['user_id' => $user->id], $guruData);
                $this->assignmentService->syncGuruPengampu($guru, $validated['mapel_ids'] ?? [], $validated['kelas_pengampu_ids'] ?? []);
                $this->assignmentService->syncKelasWali($guru, str_contains($validated['peran'], 'WALI KELAS') ? ($validated['kelas_wali_id'] ?? null) : null);

                // Reload relasi untuk response
                $guru->load(['user', 'guruPengampus.mataPelajaran', 'guruPengampus.kelas', 'kelasWali']);
                $guruResult = $this->buildGuruRow($guru);
            });

            return response()->json(['message' => 'Data guru berhasil ditambahkan.', 'guru' => $guruResult]);

        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validasi gagal.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

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

            DB::transaction(function () use ($guru, $validated) {
                $guru->user->update([
                    'nama'  => $validated['nama'],
                    'email' => $validated['email'],
                    'role'  => str_contains($validated['peran'], 'WALI KELAS') ? 'walikelas' : 'guru',
                ]);

                $updateData = [];
                if (isset($validated['sekolah_id'])) {
                    $updateData['sekolah_id'] = $validated['sekolah_id'];
                }
                if (Schema::hasColumn('gurus', 'jabatan')) {
                    $updateData['jabatan'] = str_contains($validated['peran'], 'WALI KELAS') ? 'Guru Mapel & Wali Kelas' : 'Guru Mapel';
                }
                if (!empty($updateData)) {
                    $guru->update($updateData);
                }

                $this->assignmentService->syncGuruPengampu($guru, $validated['mapel_ids'] ?? [], $validated['kelas_pengampu_ids'] ?? []);
                $this->assignmentService->syncKelasWali($guru, str_contains($validated['peran'], 'WALI KELAS') ? ($validated['kelas_wali_id'] ?? null) : null);
            });

            $guru->load(['user', 'guruPengampus.mataPelajaran', 'guruPengampus.kelas', 'kelasWali']);
            $guruRow = $this->buildGuruRow($guru);

            return response()->json(['message' => 'Data guru berhasil diperbarui.', 'guru' => $guruRow]);

        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validasi gagal.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroyAjax($id)
    {
        try {
            $guru = Guru::findOrFail($id);
            $user = $guru->user;
            Kelas::where('wali_guru_id', $guru->user_id)->update(['wali_guru_id' => null]);
            $guru->delete();
            if ($user) {
                $user->delete();
            }
            return response()->json(['message' => 'Data guru berhasil dihapus.', 'id' => (int) $id]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Bangun array row guru untuk dikirim ke Alpine.js.
     */
    private function buildGuruRow(Guru $guru): array
    {
        $mapels = $guru->guruPengampus->map(fn($gp) => $gp->mataPelajaran->nama_mapel ?? '-')->unique()->implode(', ');
        $kelasDiampu = $guru->guruPengampus->map(fn($gp) => $gp->kelas->nama_kelas ?? null)->filter()->unique()->implode(', ');
        $kelasWali = $guru->kelasWali->pluck('nama_kelas')->filter()->implode(', ');
        $roles = [];
        if ($guru->guruPengampus->isNotEmpty()) $roles[] = 'GURU MAPEL';
        if ($guru->kelasWali->isNotEmpty()) $roles[] = 'WALI KELAS';
        if (empty($roles)) $roles[] = 'GURU MAPEL';

        return [
            'id'                 => $guru->user_id,
            'name'               => $guru->user->nama ?? '-',
            'nama'               => $guru->user->nama ?? '-',
            'email'              => $guru->user->email ?? '-',
            'nip'                => $guru->nip,
            'peran'              => in_array('WALI KELAS', $roles) ? 'GURU MAPEL & WALI KELAS' : 'GURU MAPEL',
            'roles'              => $roles,
            'mapel'              => $mapels ?: '-',
            'mapel_ids'          => $guru->guruPengampus->pluck('mapel_id')->unique()->values()->all(),
            'kelas_diampu'       => $kelasDiampu ?: '-',
            'kelas_pengampu_ids' => $guru->guruPengampus->pluck('kelas_id')->unique()->values()->all(),
            'kelas_walikelas'    => $kelasWali ?: '-',
            'kelas_wali_id'      => $guru->kelasWali->pluck('id')->first(),
        ];
    }

    // ─── Standard Form Methods (tetap ada sebagai fallback) ─────────────────

    public function store(Request $request)
    {
        $selectedUserId = $request->input('user_id');
        $existingGuruUserId = $selectedUserId ? Guru::where('user_id', $selectedUserId)->value('user_id') : null;

        // Validasi input
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'user_id'    => 'nullable|exists:users,id',
            'email'      => ['required_without:user_id', 'email', Rule::unique('users', 'email')->ignore($request->input('user_id'))],
            'username'   => ['required_without:user_id', 'string', Rule::unique('users', 'username')->ignore($request->input('user_id'))],
            'nip'        => ['required', 'numeric', 'digits_between:1,18', Rule::unique('gurus', 'nip')->ignore($existingGuruUserId, 'user_id')],
            'peran'      => 'required|in:GURU MAPEL,WALI KELAS,GURU MAPEL & WALI KELAS',
            'sekolah_id' => 'nullable|exists:sekolahs,id',
            'jabatan'    => 'nullable|string|max:255',
            'mapel_ids' => 'nullable|array',
            'mapel_ids.*' => 'exists:mata_pelajarans,kode_mapel',
            'kelas_pengampu_ids' => 'nullable|array',
            'kelas_pengampu_ids.*' => 'exists:kelas,id',
            'kelas_wali_id' => 'nullable|exists:kelas,id',
        ]);

        $this->assignmentService->validatePengampuSelection($validated);
        $this->assignmentService->validateKelasWaliSelection($validated, $existingGuruUserId);

        $role = str_contains($validated['peran'], 'WALI KELAS') ? 'walikelas' : 'guru';
        
        // Dapatkan sekolah_id default jika tidak diisi
        $sekolahId = $validated['sekolah_id'] ?? (Sekolah::first()->id ?? null);
        
        if (!$sekolahId) {
            return back()->withErrors(['sekolah_id' => 'Belum ada data sekolah terdaftar. Harap daftarkan sekolah terlebih dahulu.']);
        }

        DB::transaction(function () use ($validated, $role, $sekolahId) {
            if (!empty($validated['user_id'])) {
                $user = User::whereIn('role', ['guru', 'walikelas'])
                    ->findOrFail($validated['user_id']);

                $user->update([
                    'nama' => $validated['nama'],
                    'role' => $role,
                ]);
            } else {
                $user = User::create([
                    'nama'     => $validated['nama'],
                    'email'    => $validated['email'],
                    'username' => $validated['username'],
                    'password' => Hash::make($validated['nip']), // Password default = NIP
                    'role'     => $role,
                ]);
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

            $this->assignmentService->syncGuruPengampu($guru, $validated['mapel_ids'] ?? [], $validated['kelas_pengampu_ids'] ?? []);
            $this->assignmentService->syncKelasWali($guru, str_contains($validated['peran'], 'WALI KELAS') ? ($validated['kelas_wali_id'] ?? null) : null);
        });

        return redirect()
            ->route('guru-tendik')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::with('user')->findOrFail($id);

        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $guru->user_id,
            'peran'      => 'required|in:GURU MAPEL,WALI KELAS,GURU MAPEL & WALI KELAS',
            'sekolah_id' => 'nullable|exists:sekolahs,id',
            'jabatan'    => 'nullable|string|max:255',
            'mapel_ids' => 'nullable|array',
            'mapel_ids.*' => 'exists:mata_pelajarans,kode_mapel',
            'kelas_pengampu_ids' => 'nullable|array',
            'kelas_pengampu_ids.*' => 'exists:kelas,id',
            'kelas_wali_id' => 'nullable|exists:kelas,id',
        ]);

        $this->assignmentService->validatePengampuSelection($validated);
        $this->assignmentService->validateKelasWaliSelection($validated, $guru->user_id);

        DB::transaction(function () use ($guru, $validated) {
            $guru->user->update([
                'nama'  => $validated['nama'],
                'email' => $validated['email'],
                'role'  => str_contains($validated['peran'], 'WALI KELAS') ? 'walikelas' : 'guru',
            ]);

            $updateData = [];
            if (isset($validated['sekolah_id'])) {
                $updateData['sekolah_id'] = $validated['sekolah_id'];
            }
            if (Schema::hasColumn('gurus', 'jabatan') && isset($validated['jabatan'])) {
                $updateData['jabatan'] = $validated['jabatan'];
            } elseif (Schema::hasColumn('gurus', 'jabatan')) {
                $updateData['jabatan'] = str_contains($validated['peran'], 'WALI KELAS') ? 'Guru Mapel & Wali Kelas' : 'Guru Mapel';
            }

            if (!empty($updateData)) {
                $guru->update($updateData);
            }

            $this->assignmentService->syncGuruPengampu($guru, $validated['mapel_ids'] ?? [], $validated['kelas_pengampu_ids'] ?? []);
            $this->assignmentService->syncKelasWali($guru, str_contains($validated['peran'], 'WALI KELAS') ? ($validated['kelas_wali_id'] ?? null) : null);
        });

        return redirect()
            ->route('guru-tendik')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        $user = $guru->user;
        Kelas::where('wali_guru_id', $guru->user_id)->update(['wali_guru_id' => null]);
        $guru->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()
            ->route('guru-tendik')
            ->with('success', 'Data guru berhasil dihapus.');
    }

}
