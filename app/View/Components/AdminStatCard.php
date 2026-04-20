<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * AdminStatCard — Komponen kartu statistik reusable.
 *
 * Menampilkan kartu dengan label, value, dan subtitle.
 * Digunakan di dashboard, manajemen user, data guru, data siswa, dll.
 *
 * Cara pakai di Blade:
 *   <x-admin-stat-card label="Total Siswa" value="1,248" subtitle="Aktif" />
 *   <x-admin-stat-card label="Guru" value="94" subtitle="Terdaftar" color="blue" />
 */
class AdminStatCard extends Component
{
    /**
     * Label di atas angka (misal: "Total Siswa").
     */
    public string $label;

    /**
     * Nilai utama yang ditampilkan besar (misal: "1,248").
     */
    public string $value;

    /**
     * Teks kecil di bawah value (misal: "↑ Aktif").
     */
    public string $subtitle;

    /**
     * Warna subtitle: 'green', 'blue', atau 'muted'.
     */
    public string $color;

    /**
     * Buat instance komponen baru.
     */
    public function __construct(
        string $label = '',
        string $value = '0',
        string $subtitle = '',
        string $color = 'green'
    ) {
        $this->label = $label;
        $this->value = $value;
        $this->subtitle = $subtitle;
        $this->color = $color;
    }

    /**
     * Render view untuk komponen ini.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin-stat-card');
    }
}
