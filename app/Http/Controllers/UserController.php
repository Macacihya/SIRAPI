<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guru;
use App\Models\Admin;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserController extends Controller
{
    // Menampilkan halaman daftar user non-admin
    public function index()
    {
        $users = User::with(['guru.guruPengampus', 'guru.kelasWali', 'admin', 'roles'])
            ->whereDoesntHave('roles', fn($q) => $q->where('nama_role', 'admin'))
            ->get();

        $usersData = $users->map(function ($user) {
            $roles = [];

            if ($user->guru) {
                if ($user->guru->guruPengampus->isNotEmpty()) {
                    $roles[] = 'GURU MAPEL';
                }
                if ($user->guru->kelasWali->isNotEmpty()) {
                    $roles[] = 'WALI KELAS';
                }
                if (empty($roles)) {
                    $userRoles = $user->roles->pluck('nama_role');
                    if ($userRoles->contains('walikelas')) {
                        $roles[] = 'WALI KELAS';
                    }
                    if ($userRoles->contains('guru') || empty($roles)) {
                        $roles[] = 'GURU MAPEL';
                    }
                }
            }

            $idLabel = '-';
            if ($user->roles->contains('nama_role', 'admin')) {
                $idLabel = 'ADMIN';
            } elseif ($user->guru) {
                $idLabel = 'NIP: ' . $user->guru->nip;
            }

            return [
                'id_db'  => $user->id,
                'name'   => $user->nama,
                'email'  => $user->email,
                'id'     => $idLabel,
                'roles'  => $roles,
                'status' => $user->status,
            ];
        });

        return view('pages.manajemen-user.index', compact('usersData'));
    }

    // Menyimpan data user baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'nip'      => 'required|numeric|digits_between:1,18',
            'roles'    => 'required|array',
            'roles.*'  => 'in:GURU MAPEL,WALI KELAS',
            'whatsapp' => 'nullable|string',
            'status'   => 'required|in:Aktif,Nonaktif',
        ]);

        $validated['roles'] = $this->normalizeGuruRoles($validated['roles']);
        $roleName = in_array('WALI KELAS', $validated['roles']) ? 'walikelas' : 'guru';

        DB::transaction(function () use ($validated, $roleName) {
            $user = User::create([
                'nama'     => $validated['nama'],
                'email'    => $validated['email'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['nip']),
                'role'     => $roleName, // Disinkronkan ke pivot table user_roles via mutator
                'status'   => $validated['status'],
            ]);

            // Sinkronisasi peran guru & wali kelas jika peran yang dipilih adalah wali kelas
            if ($roleName === 'walikelas') {
                $roleIds = \App\Models\Role::whereIn('nama_role', ['guru', 'walikelas'])
                    ->pluck('id')->toArray();
                $user->roles()->sync($roleIds);
            }

            $sekolahId = Sekolah::first()->id ?? 1;

            $guruData = [
                'user_id'    => $user->id,
                'nip'        => $validated['nip'],
                'sekolah_id' => $sekolahId,
            ];
            if (Schema::hasColumn('gurus', 'jabatan')) {
                $guruData['jabatan'] = $roleName === 'walikelas' ? 'Guru Mapel & Wali Kelas' : 'Guru Mapel';
            }

            Guru::create($guruData);
        });

        if ($request->wantsJson()) {
            return response()->json(['message' => 'User berhasil ditambahkan.']);
        }

        return redirect()
            ->route('manajemen-user')
            ->with('success', 'User berhasil ditambahkan.');
    }

    // Memperbarui data user di database
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'roles'    => 'required|array',
            'roles.*'  => 'in:GURU MAPEL,WALI KELAS',
            'status'   => 'required|in:Aktif,Nonaktif',
        ]);

        $isAdmin = $user->hasRole('admin');

        if (!$isAdmin) {
            $validated['roles'] = $this->normalizeGuruRoles($validated['roles']);
            $roleName = in_array('WALI KELAS', $validated['roles']) ? 'walikelas' : 'guru';
        }

        DB::transaction(function () use ($user, $validated, $isAdmin) {
            $updateData = [
                'nama'   => $validated['nama'],
                'email'  => $validated['email'],
                'status' => $validated['status'],
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            // Perbarui peran user (hanya untuk non-admin)
            if (!$isAdmin) {
                $newRoleNames = in_array('WALI KELAS', $validated['roles']) ? ['guru', 'walikelas'] : ['guru'];
                $roleIds = \App\Models\Role::whereIn('nama_role', $newRoleNames)->pluck('id')->toArray();
                $user->roles()->sync($roleIds);

                // Perbarui kolom jabatan di tabel guru
                $jabatan = in_array('walikelas', $newRoleNames) ? 'Guru Mapel & Wali Kelas' : 'Guru Mapel';
                if ($user->guru && Schema::hasColumn('gurus', 'jabatan')) {
                    $user->guru->update(['jabatan' => $jabatan]);
                }
            }
        });

        if ($request->wantsJson()) {
            return response()->json(['message' => 'User berhasil diperbarui.']);
        }

        return redirect()
            ->route('manajemen-user')
            ->with('success', 'User berhasil diperbarui.');
    }

    // Menghapus data user dari database (hanya jika status Nonaktif)
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Hanya user dengan status Nonaktif yang boleh dihapus
        if ($user->status !== 'Nonaktif') {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'message' => 'User harus dinonaktifkan terlebih dahulu sebelum dihapus.'
                ], 422);
            }

            return redirect()
                ->route('manajemen-user')
                ->with('error', 'User harus dinonaktifkan terlebih dahulu sebelum dihapus.');
        }

        $user->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['message' => 'User berhasil dihapus.']);
        }

        return redirect()
            ->route('manajemen-user')
            ->with('success', 'User berhasil dihapus.');
    }

    // Normalisasi peran guru agar wali kelas selalu mendapatkan peran guru mapel juga
    private function normalizeGuruRoles(array $roles): array
    {
        $roles = collect($roles)
            ->filter(fn($r) => in_array($r, ['GURU MAPEL', 'WALI KELAS'], true))
            ->unique()
            ->values()
            ->all();

        if (in_array('WALI KELAS', $roles, true) && !in_array('GURU MAPEL', $roles, true)) {
            array_unshift($roles, 'GURU MAPEL');
        }

        return $roles ?: ['GURU MAPEL'];
    }

    // Memperbarui data profil user yang sedang login
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'jenis_kelamin' => 'nullable|string|in:Pria,Wanita',
            'no_hp'         => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'email'         => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'nama'          => $validated['nama'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'no_hp'         => $validated['no_hp'],
            'alamat'        => $validated['alamat'],
            'email'         => $validated['email'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui!',
            'user'    => $user,
        ]);
    }

    // Mengubah password user yang sedang login
    public function changePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'lama'       => 'required|string',
            'baru'       => 'required|string|min:6',
            'konfirmasi' => 'required|string|same:baru',
        ]);

        if (!Hash::check($validated['lama'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kata sandi lama tidak sesuai.',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($validated['baru']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diubah!',
        ]);
    }
}
