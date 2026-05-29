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
    public function index()
    {
        // Ambil user yang BUKAN admin (pakai M:M whereDoesntHave)
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
                    // Fallback ke roles M:M
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
                'status' => 'Aktif',
            ];
        });

        return view('pages.manajemen-user.index', compact('usersData'));
    }

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
        ]);

        $validated['roles'] = $this->normalizeGuruRoles($validated['roles']);
        $roleName = in_array('WALI KELAS', $validated['roles']) ? 'walikelas' : 'guru';

        DB::transaction(function () use ($validated, $roleName) {
            $user = User::create([
                'nama'     => $validated['nama'],
                'email'    => $validated['email'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['nip']),
                'role'     => $roleName,   // ditangani oleh mutator → user_roles
            ]);

            // Jika guru adalah walikelas, tambah JUGA role 'guru'
            if ($roleName === 'walikelas') {
                $user->syncRoleByName('guru');   // beri dua role: guru + walikelas
                $user->syncRoleByName('walikelas'); // hapus yang lama, isi ulang
                // Gunakan cara yang benar: sync keduanya sekaligus
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
                $guruData['jabatan'] = $roleName === 'walikelas'
                    ? 'Guru Mapel & Wali Kelas'
                    : 'Guru Mapel';
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

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'roles'    => 'required|array',
            'roles.*'  => 'in:GURU MAPEL,WALI KELAS',
        ]);

        $isAdmin = $user->hasRole('admin');

        if (!$isAdmin) {
            $validated['roles'] = $this->normalizeGuruRoles($validated['roles']);
            $roleName = in_array('WALI KELAS', $validated['roles']) ? 'walikelas' : 'guru';
        }

        DB::transaction(function () use ($user, $validated, $isAdmin) {
            $updateData = [
                'nama'  => $validated['nama'],
                'email' => $validated['email'],
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            // Update roles M:M (hanya untuk non-admin)
            if (!$isAdmin) {
                $newRoleNames = in_array('WALI KELAS', $validated['roles'])
                    ? ['guru', 'walikelas']
                    : ['guru'];

                $roleIds = \App\Models\Role::whereIn('nama_role', $newRoleNames)
                    ->pluck('id')->toArray();
                $user->roles()->sync($roleIds);

                // Update jabatan di tabel guru
                $jabatan = in_array('walikelas', $newRoleNames)
                    ? 'Guru Mapel & Wali Kelas'
                    : 'Guru Mapel';

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

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()
            ->route('manajemen-user')
            ->with('success', 'User berhasil dihapus.');
    }

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

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'jenis_kelamin' => 'nullable|string|in:Laki-laki,Perempuan',
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
