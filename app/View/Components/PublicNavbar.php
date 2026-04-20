<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * PublicNavbar — Komponen navigasi untuk halaman publik SIRAPI.
 *
 * Komponen ini menampilkan navbar dengan menu: Home, About, Features, Services, Contact, dan Login.
 * Prop $active digunakan untuk menandai menu mana yang sedang aktif (highlight).
 *
 * Cara pakai di Blade:
 *   <x-public-navbar active="home" />
 */
class PublicNavbar extends Component
{
    /**
     * Menu yang sedang aktif (contoh: 'home', 'about', 'features', dll).
     */
    public string $active;

    /**
     * Buat instance komponen baru.
     *
     * @param string $active  — key menu yang aktif
     */
    public function __construct(string $active = 'home')
    {
        $this->active = $active;
    }

    /**
     * Render view untuk komponen ini.
     */
    public function render(): View|Closure|string
    {
        return view('components.public-navbar');
    }
}
