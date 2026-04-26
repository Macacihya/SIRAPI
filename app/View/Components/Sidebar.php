<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Sidebar — Komponen sidebar navigasi terpusat untuk semua role.
 *
 * Menggantikan AdminNavbar, GuruNavbar, dan WalikelasNavbar
 * menjadi SATU komponen yang membaca menu dari config/sirapi_menus.php.
 *
 * Cara pakai di Blade:
 *   <x-sidebar :active="View::yieldContent('active', 'dashboard')" />
 */
class Sidebar extends Component
{
    /**
     * Menu yang sedang aktif (contoh: 'dashboard', 'manajemen-user', dll).
     */
    public string $active;

    /**
     * Buat instance komponen baru.
     *
     * @param string $active — key menu yang aktif
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
        return view('components.sidebar');
    }
}
