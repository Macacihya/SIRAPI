{{-- ============================================================
     Komponen: AdminStatCard
     Deskripsi: Kartu statistik reusable untuk halaman admin.
     Props: $label, $value, $subtitle, $color
     Dibuat dengan: php artisan make:component AdminStatCard

     Contoh pemakaian:
       <x-admin-stat-card label="Siswa Aktif" value="1,248" subtitle="↑ Aktif" color="green" />
     ============================================================ --}}

@php
    // Warna subtitle berdasarkan prop $color
    $colorClasses = match($color) {
        'green' => 'text-[#059669]',
        'blue'  => 'text-[#1e4d9b]',
        'muted' => 'text-[#64748b]',
        default => 'text-[#059669]',
    };
@endphp

{{-- Kartu statistik dengan border dan rounded --}}
<div class="rounded-xl border border-[#e2e8f0] bg-white p-5">
    {{-- Label kecil di atas --}}
    <p class="text-[11px] font-semibold uppercase tracking-[0.05em] text-[#64748b] mb-3">
        {{ $label }}
    </p>

    {{-- Nilai utama (angka besar) --}}
    <p class="text-[30px] font-[800] text-[#0f2347]">
        {{ $value }}
    </p>

    {{-- Subtitle kecil di bawah --}}
    @if ($subtitle)
        <p class="mt-1.5 text-[12px] {{ $colorClasses }}">
            {{ $subtitle }}
        </p>
    @endif
</div>