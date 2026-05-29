<?php

if (!function_exists('getLayout')) {
    /**
     * Menentukan layout Blade berdasarkan role aktif user yang login.
     */
    function getLayout(): string
    {
        return 'layouts.app';
    }
}

if (!function_exists('getUserRole')) {
    /**
     * Mengembalikan role AKTIF user saat ini.
     *
     * Alur:
     * 1. Baca 'active_role' dari session (dipilih saat login).
     * 2. Pastikan user benar-benar memiliki role tsb (keamanan).
     * 3. Fallback ke role pertama di tabel user_roles jika session kosong.
     *
     * Mendukung multi-role: user dengan role guru+walikelas bisa
     * login sebagai salah satu dan mendapat tampilan yang sesuai.
     */
    function getUserRole(): string
    {
        $user = auth()->user();
        if (!$user) {
            return 'admin';
        }

        // Gunakan active_role yang disimpan di session saat login
        $activeRole = session('active_role');
        if ($activeRole && $user->hasRole($activeRole)) {
            return in_array($activeRole, ['admin', 'admin_tu']) ? 'admin' : $activeRole;
        }

        // Fallback: ambil role pertama dari tabel user_roles
        $role = $user->roles()->value('nama_role') ?? 'admin';
        return in_array($role, ['admin', 'admin_tu']) ? 'admin' : $role;
    }
}

if (!function_exists('getGuruData')) {
    /**
     * Mengambil data spesialisasi Guru dari tabel ISA anak 'gurus'
     * melalui relasi User → Guru.
     */
    function getGuruData()
    {
        if (auth()->check()) {
            return auth()->user()->guru;
        }
        return null;
    }
}
