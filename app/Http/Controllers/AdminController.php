<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::with('user')->get();
        return view('pages.admin.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'username'      => 'required|string|unique:users,username',
            'password'      => 'required|string|min:6',
            'jabatan_admin' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'nama'     => $validated['nama'],
                'email'    => $validated['email'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'role'     => 'admin',
            ]);

            Admin::create([
                'user_id'       => $user->id,
                'jabatan_admin' => $validated['jabatan_admin'] ?? 'Staff',
            ]);
        });

        return redirect()
            ->back()
            ->with('success', 'Admin berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $admin->user_id,
            'password'      => 'nullable|string|min:6',
            'jabatan_admin' => 'nullable|string|max:255',
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

            $admin->update([
                'jabatan_admin' => $validated['jabatan_admin'] ?? 'Staff',
            ]);
        });

        return redirect()
            ->back()
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        
        // Hapus user (akan cascade delete admin)
        $user = User::findOrFail($admin->user_id);
        $user->delete();

        return redirect()
            ->back()
            ->with('success', 'Admin berhasil dihapus.');
    }
}
