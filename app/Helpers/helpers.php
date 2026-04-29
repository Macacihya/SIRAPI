<?php

if (!function_exists('getLayout')) {
    /**
     * Menentukan layout Blade berdasarkan role user yang login.
     * Penggunaan di Blade: @extends(getLayout())
     *
     * Arsitektur siap multi-role (role switcher) di masa depan.
     * Saat ini menggunakan single role dari database.
     */
    function getLayout(): string
    {
        // Semua role kini menggunakan satu master layout terpusat.
        // Layout mendeteksi role secara otomatis via getUserRole().
        return 'layouts.app';
    }
}

if (!function_exists('getUserRole')) {
    /**
     * Mengembalikan role user yang sudah dinormalisasi.
     * admin_tu → admin (untuk conditional di Blade dan middleware)
     *
     * Di masa depan, bisa dikembangkan untuk mendukung
     * multi-role dengan konsep "active role" (role switcher).
     */
    function getUserRole(): string
    {
        $role = auth()->user()?->role ?? 'admin';

        return in_array($role, ['admin', 'admin_tu']) ? 'admin' : $role;
    }
}
