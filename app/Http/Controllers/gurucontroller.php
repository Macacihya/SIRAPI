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
            ->whereDoesntHave('guru')
            ->orderBy('nama')
            ->get(['id', 'nama', 'email', 'username', 'role'])
            ->map(fn($user) => [
                'id' => $user->id,
                'name' => $user->nama,
                'email' => $user->email,
                'username' => $user->username,
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

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'user_id'    => 'nullable|exists:users,id',
            'email'      => ['required_without:user_id', 'email', Rule::unique('users', 'email')->ignore($request->input('user_id'))],
            'username'   => ['required_without:user_id', 'string', Rule::unique('users', 'username')->ignore($request->input('user_id'))],
            'nip'        => 'required|numeric|digits_between:1,18|unique:gurus,nip',
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
        $this->assignmentService->validateKelasWaliSelection($validated);

        $role = str_contains($validated['peran'], 'WALI KELAS') ? 'walikelas' : 'guru';
        
        // Dapatkan sekolah_id default jika tidak diisi
        $sekolahId = $validated['sekolah_id'] ?? (Sekolah::first()->id ?? null);
        
        if (!$sekolahId) {
            return back()->withErrors(['sekolah_id' => 'Belum ada data sekolah terdaftar. Harap daftarkan sekolah terlebih dahulu.']);
        }

        DB::transaction(function () use ($validated, $role, $sekolahId) {
            if (!empty($validated['user_id'])) {
                $user = User::whereIn('role', ['guru', 'walikelas'])
                    ->whereDoesntHave('guru')
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

            $guru = Guru::create($guruData);

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
