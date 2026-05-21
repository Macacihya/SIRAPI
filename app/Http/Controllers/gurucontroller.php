<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use App\Models\Sekolah;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        return $this->tampilkan($request);
    }

    public function tampilkan(Request $request)
    {
        $query = Guru::with(['user', 'guruPengampus.mataPelajaran', 'sekolah']);

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

            return (object)[
                'id'    => $guru->user_id,
                'name'  => $guru->user->nama ?? '-',
                'nama'  => $guru->user->nama ?? '-',
                'email' => $guru->user->email ?? '-',
                'nip'   => $guru->nip,
                'roles' => [($guru->user->role ?? 'guru') === 'walikelas' ? 'WALI KELAS' : 'GURU MAPEL'],
                'mapel' => $mapels ?: ($guru->mata_pelajaran ?? '-'),
            ];
        });

        // Daftar mapel untuk dropdown di form
        $daftarMapel = MataPelajaran::pluck('nama_mapel', 'kode_mapel')->toArray();
        $sekolahs = Sekolah::all();

        // Riwayat status guru
        $riwayatGuru = \App\Models\RiwayatStatusGuru::with('guru.user')->orderBy('tanggal_perubahan', 'desc')->take(20)->get();

        return view('pages.guru-tendik.index', compact('gurus', 'daftarMapel', 'sekolahs', 'riwayatGuru'));
    }

    public function create()
    {
        $daftarMapel = MataPelajaran::pluck('nama_mapel', 'kode_mapel')->toArray();
        $sekolahs = Sekolah::all();
        return view('pages.guru.create', compact('daftarMapel', 'sekolahs'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'username'   => 'required|string|unique:users,username',
            'nip'        => 'required|numeric|digits_between:1,18|unique:gurus,nip',
            'peran'      => 'required|in:GURU MAPEL,WALI KELAS',
            'sekolah_id' => 'nullable|exists:sekolahs,id',
            'jabatan'    => 'nullable|string|max:255',
        ]);

        $role = $validated['peran'] === 'WALI KELAS' ? 'walikelas' : 'guru';
        
        // Dapatkan sekolah_id default jika tidak diisi
        $sekolahId = $validated['sekolah_id'] ?? (Sekolah::first()->id ?? null);
        
        if (!$sekolahId) {
            return back()->withErrors(['sekolah_id' => 'Belum ada data sekolah terdaftar. Harap daftarkan sekolah terlebih dahulu.']);
        }

        DB::transaction(function () use ($validated, $role, $sekolahId) {
            $user = User::create([
                'nama'     => $validated['nama'],
                'email'    => $validated['email'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['nip']), // Password default = NIP
                'role'     => $role,
            ]);

            Guru::create([
                'user_id'    => $user->id,
                'nip'        => $validated['nip'],
                'sekolah_id' => $sekolahId,
                'jabatan'    => $validated['jabatan'] ?? ($validated['peran'] === 'WALI KELAS' ? 'Wali Kelas' : 'Guru Mapel'),
            ]);
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
            'sekolah_id' => 'nullable|exists:sekolahs,id',
            'jabatan'    => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($guru, $validated) {
            $guru->user->update([
                'nama'  => $validated['nama'],
                'email' => $validated['email'],
            ]);

            $updateData = [];
            if (isset($validated['sekolah_id'])) {
                $updateData['sekolah_id'] = $validated['sekolah_id'];
            }
            if (isset($validated['jabatan'])) {
                $updateData['jabatan'] = $validated['jabatan'];
            }

            if (!empty($updateData)) {
                $guru->update($updateData);
            }
        });

        return redirect()
            ->route('guru-tendik')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        $user = $guru->user;
        $guru->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()
            ->route('guru-tendik')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}
