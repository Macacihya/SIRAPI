<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Menampilkan halaman daftar admin
    public function index()
    {
        $admins = Admin::with('user')->get();
        return view('pages.admin.index', compact('admins'));
    }

    // Menyimpan data admin baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'nama'     => $validated['nama'],
                'email'    => $validated['email'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'role'     => 'admin', // Disinkronkan ke pivot table user_roles via mutator
            ]);

            Admin::create([
                'user_id' => $user->id,
            ]);
        });

        return redirect()
            ->back()
            ->with('success', 'Admin berhasil ditambahkan.');
    }

    // Memperbarui data admin di database
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $admin->user_id,
            'password' => 'nullable|string|min:6',
        ]);

        DB::transaction(function () use ($admin, $validated) {
            $updateData = [
                'nama'  => $validated['nama'],
                'email' => $validated['email'],
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $admin->user->update($updateData);
        });

        return redirect()
            ->back()
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    // Menghapus data admin beserta user terkait
    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);

        // Menghapus data user (akan menghapus data admin di tabel pivot juga karena cascade)
        $user = User::findOrFail($admin->user_id);
        $user->delete();

        return redirect()
            ->back()
            ->with('success', 'Admin berhasil dihapus.');
    }
}
