<?php

/**
 * ============================================================
 * Konfigurasi Menu Sidebar SIRAPI
 * ============================================================
 *
 * File ini menjadi SATU-SATUNYA sumber daftar menu sidebar
 * untuk seluruh role. Jika ingin menambah/mengubah/menghapus
 * menu, cukup edit file ini — tidak perlu menyentuh file Blade.
 *
 * Struktur setiap item:
 *   - key   : identifier unik, dicocokkan dengan @section('active')
 *   - label : teks yang tampil di sidebar
 *   - route : nama route Laravel (akan di-resolve via route())
 *   - icon  : key icon SVG yang di-render di sidebar component
 *
 * Role yang didukung: admin, guru, walikelas
 * ============================================================
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Admin TU — 11 menu
    |--------------------------------------------------------------------------
    */
    'admin' => [
        ['key' => 'dashboard',        'label' => 'Dashboard',        'route' => 'dashboard',        'icon' => 'dashboard'],
        ['key' => 'manajemen-user',   'label' => 'Manajemen User',   'route' => 'manajemen-user',   'icon' => 'users-manage'],
        ['key' => 'data-sekolah',     'label' => 'Data Sekolah',     'route' => 'data-sekolah',     'icon' => 'school'],
        ['key' => 'guru',             'label' => 'Guru',             'route' => 'guru-tendik',      'icon' => 'user'],
        ['key' => 'data-siswa',       'label' => 'Data Siswa',       'route' => 'siswa',            'icon' => 'users'],
        ['key' => 'akademik',         'label' => 'Akademik',         'route' => 'akademik',         'icon' => 'book'],
        ['key' => 'mata-pelajaran',   'label' => 'Mata Pelajaran',   'route' => 'mata-pelajaran',   'icon' => 'book'],
        ['key' => 'jadwal-pelajaran', 'label' => 'Jadwal Pelajaran', 'route' => 'jadwal',           'icon' => 'calendar'],
        ['key' => 'aturan-nilai',     'label' => 'Aturan Nilai',     'route' => 'aturan-nilai',     'icon' => 'clipboard'],
        ['key' => 'laporan-nilai',    'label' => 'Laporan Nilai',    'route' => 'laporan-nilai',    'icon' => 'book'],
        ['key' => 'rekap-nilai',      'label' => 'Rekap Nilai',      'route' => 'rekap-nilai',      'icon' => 'clipboard'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Guru Mata Pelajaran — 7 menu
    |--------------------------------------------------------------------------
    */
    'guru' => [
        ['key' => 'dashboard',          'label' => 'Dashboard',           'route' => 'dashboard',          'icon' => 'dashboard'],
        ['key' => 'jadwal-mengajar',    'label' => 'Jadwal Mengajar',     'route' => 'jadwal',             'icon' => 'calendar'],
        ['key' => 'data-siswa',         'label' => 'Data Siswa',          'route' => 'siswa',              'icon' => 'users'],
        ['key' => 'penilaian',          'label' => 'Penilaian',           'route' => 'penilaian',          'icon' => 'star'],
        ['key' => 'capaian-kompetensi', 'label' => 'Capaian Kompetensi',  'route' => 'capaian-kompetensi', 'icon' => 'file-text'],
        ['key' => 'laporan-nilai',      'label' => 'Laporan Nilai',       'route' => 'laporan-nilai',      'icon' => 'chart'],
        ['key' => 'rekap-nilai',        'label' => 'Rekap Nilai Kelas',   'route' => 'rekap-nilai',        'icon' => 'check-square'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Wali Kelas — 8 menu
    |--------------------------------------------------------------------------
    */
    'walikelas' => [
        ['key' => 'dashboard',     'label' => 'Dashboard',        'route' => 'dashboard',       'icon' => 'dashboard'],
        ['key' => 'profil-kelas',  'label' => 'Profil Kelas',     'route' => 'profil-kelas',    'icon' => 'home'],
        ['key' => 'jadwal-kelas',  'label' => 'Jadwal Kelas',     'route' => 'jadwal',          'icon' => 'calendar'],
        ['key' => 'kehadiran',     'label' => 'Kehadiran Siswa',  'route' => 'kehadiran',       'icon' => 'check-square'],
        ['key' => 'penilaian',     'label' => 'Penilaian Kelas',  'route' => 'penilaian',       'icon' => 'star'],
        ['key' => 'rapor',         'label' => 'Rapor Siswa',      'route' => 'rapor',           'icon' => 'file-text'],
        ['key' => 'laporan-nilai', 'label' => 'Laporan Nilai',    'route' => 'laporan-nilai',   'icon' => 'file-text'],
        ['key' => 'rekap-nilai',   'label' => 'Rekap Nilai',      'route' => 'rekap-nilai',     'icon' => 'check-square'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Label & Badge per Role (untuk sidebar header)
    |--------------------------------------------------------------------------
    */
    'role_labels' => [
        'admin'     => ['subtitle' => 'Admin TU',              'badge' => 'TU Administration'],
        'guru'      => ['subtitle' => 'Guru Mata Pelajaran',   'badge' => 'Panel Guru'],
        'walikelas' => ['subtitle' => 'Sistem Rapor Pintar',   'badge' => 'Panel Wali Kelas'],
    ],

];
