<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\User;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        return $this->tampilkan($request);
    }

    public function tampilkan(Request $request)
    {
        $query = Guru::with(['user', 'guruPengampus.mataPelajaran']);

        // Pencarian
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

        // Daftar mapel untuk dropdown di form
        $daftarMapel = MataPelajaran::pluck('nama_mapel', 'kode_mapel')->toArray();

        return view('pages.guru-tendik.index', compact('gurus', 'daftarMapel'));
    }

    public function create()
    {
        $daftarMapel = MataPelajaran::pluck('nama_mapel', 'kode_mapel')->toArray();
        return view('pages.guru.create', compact('daftarMapel'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'username'        => 'required|string|unique:users,username',
            'nip'             => 'required|string|unique:gurus,nip',
            'role'            => 'required|in:guru,walikelas',
            'mata_pelajaran'  => 'nullable|string',
        ]);

        $user = User::create([
            'nama'     => $validated['nama'],
            'email'    => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['nip']), // Password default = NIP
            'role'     => $validated['role'],
        ]);

        Guru::create([
            'user_id'         => $user->id,
            'nip'             => $validated['nip'],
            'mata_pelajaran'  => $validated['mata_pelajaran'] ?? null,
        ]);

        return redirect()
            ->route('guru-tendik')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::with('user')->findOrFail($id);

        $validated = $request->validate([
            'nama'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email,' . $guru->user_id,
            'mata_pelajaran' => 'nullable|string',
        ]);

        $guru->user->update([
            'nama'  => $validated['nama'],
            'email' => $validated['email'],
        ]);

        $guru->update([
            'mata_pelajaran' => $validated['mata_pelajaran'] ?? null,
        ]);

        return redirect()
            ->route('guru-tendik')
            ->with('success', 'Data guru berhasil diperbarui.');
    }
}
