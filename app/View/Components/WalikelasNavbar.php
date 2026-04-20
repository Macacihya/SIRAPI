<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * WalikelasNavbar — Komponen sidebar navigasi untuk halaman Wali Kelas.
 *
 * Menampilkan sidebar dengan brand, menu navigasi wali kelas,
 * dan profil user. Prop $active untuk highlight menu aktif.
 *
 * Cara pakai di Blade:
 *   <x-walikelas-navbar active="profil-kelas" />
 */
class WalikelasNavbar extends Component
{
    /**
     * Menu yang sedang aktif (contoh: 'dashboard', 'profil-kelas', dll).
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
        return view('components.walikelas-navbar');
    }
}
