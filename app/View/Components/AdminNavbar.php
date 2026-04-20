<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * AdminNavbar — Komponen sidebar navigasi untuk halaman Admin TU.
 *
 * Menampilkan sidebar dengan brand, badge tahun ajaran, menu navigasi,
 * dan profil user di bagian bawah. Prop $active untuk highlight menu aktif.
 *
 * Cara pakai di Blade:
 *   <x-admin-navbar active="dashboard" />
 */
class AdminNavbar extends Component
{
    /**
     * Menu yang sedang aktif (contoh: 'dashboard', 'manajemen-user', dll).
     */
    public string $active;

    /**
     * Buat instance komponen baru.
     *
     * @param string $active  — key menu yang aktif
     */
    public function __construct(string $active = 'dashboard')
    {
        $this->active = $active;
    }

    /**
     * Render view untuk komponen ini.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin-navbar');
    }
}
