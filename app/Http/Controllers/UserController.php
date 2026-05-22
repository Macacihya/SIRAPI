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
        $users = User::with(['guru.guruPengampus', 'guru.kelasWali', 'admin'])
            ->where('role', '!=', 'admin')
            ->get();

        $usersData = $users->map(function ($user) {
            $roles = [];
            if ($user->role === 'admin') {
                $roles[] = 'ADMIN';
            } elseif ($user->guru) {
                if ($user->guru->guruPengampus->isNotEmpty()) {
                    $roles[] = 'GURU MAPEL';
                }
                if ($user->guru->kelasWali->isNotEmpty()) {
                    $roles[] = 'WALI KELAS';
                }
                if (empty($roles)) {
                    $roles = $user->role === 'walikelas'
                        ? ['GURU MAPEL', 'WALI KELAS']
                        : ['GURU MAPEL'];
                }
            } elseif ($user->role === 'guru') {
                $roles[] = 'GURU MAPEL';
            } elseif ($user->role === 'walikelas') {
                $roles[] = 'GURU MAPEL';
                $roles[] = 'WALI KELAS';
            }

            $idLabel = '-';
            if ($user->role === 'admin') {
                $idLabel = 'ADMIN: ' . ($user->admin->jabatan_admin ?? 'Staff');
            } elseif ($user->guru) {
                $idLabel = 'NIP: ' . $user->guru->nip;
            }

            return [
                'id_db'  => $user->id,
                'name'   => $user->nama,
                'email'  => $user->email,
                'id'     => $idLabel,
                'roles'  => $roles,
                'status' => 'Aktif', // Default status aktif
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
        $role = in_array('WALI KELAS', $validated['roles']) ? 'walikelas' : 'guru';

        DB::transaction(function () use ($validated, $role) {
            $user = User::create([
                'nama'     => $validated['nama'],
                'email'    => $validated['email'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['nip']), // default password = NIP
                'role'     => $role,
            ]);

            // Ambil sekolah_id default untuk guru
            $sekolahId = Sekolah::first()->id ?? 1;

            $guruData = [
                'user_id'    => $user->id,
                'nip'        => $validated['nip'],
                'sekolah_id' => $sekolahId,
            ];
            if (Schema::hasColumn('gurus', 'jabatan')) {
                $guruData['jabatan'] = $role === 'walikelas' ? 'Guru Mapel & Wali Kelas' : 'Guru Mapel';
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

        $role = $user->role;
        if ($user->role !== 'admin') {
            $validated['roles'] = $this->normalizeGuruRoles($validated['roles']);
            $role = in_array('WALI KELAS', $validated['roles']) ? 'walikelas' : 'guru';
        }

        DB::transaction(function () use ($user, $validated, $role) {
            $updateData = [
                'nama'  => $validated['nama'],
                'email' => $validated['email'],
                'role'  => $role,
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            // Update role/jabatan di tabel guru jika ada
            if ($user->guru && Schema::hasColumn('gurus', 'jabatan')) {
                $user->guru->update([
                    'jabatan' => $role === 'walikelas' ? 'Guru Mapel & Wali Kelas' : 'Guru Mapel',
                ]);
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
            ->filter(fn($role) => in_array($role, ['GURU MAPEL', 'WALI KELAS'], true))
            ->unique()
            ->values()
            ->all();

        if (in_array('WALI KELAS', $roles, true) && !in_array('GURU MAPEL', $roles, true)) {
            array_unshift($roles, 'GURU MAPEL');
        }

        return $roles ?: ['GURU MAPEL'];
    }
}
