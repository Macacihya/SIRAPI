{{-- ============================================================
     Komponen: Modal Logout
     Deskripsi: Modal konfirmasi logout yang otomatis menampilkan
     nama panel sesuai role user yang login.

     Output HTML IDENTIK dengan modal logout di layouts lama.
     ============================================================ --}}

@php
    // Deskripsi panel berdasarkan role
    $panelNames = [
        'admin'     => 'Admin TU',
        'guru'      => 'Guru',
        'walikelas' => 'Wali Kelas',
    ];
    $panelName = $panelNames[getUserRole()] ?? 'SIRAPI';
@endphp

<div x-show="logoutModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display: none;" x-transition @click.self="logoutModalOpen = false">
    <div class="flex w-[90%] max-w-sm flex-col rounded-2xl bg-white shadow-2xl">
        <div class="p-6 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#fef2f2] text-[#dc2626] mb-4 ring-4 ring-[#fee2e2]">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </div>
            <h3 class="text-[18px] font-black text-[#0f172a]">Konfirmasi Keluar</h3>
            <p class="mt-2 text-[13px] leading-[1.8] text-[#64748b]">Apakah Anda yakin ingin keluar dari sesi panel {{ $panelName }} ini?</p>
        </div>
        <div class="flex gap-3 bg-[#f8fafc] px-6 py-4 rounded-b-2xl border-t border-[#e2e8f0]">
            <button @click="logoutModalOpen = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Batal</button>
            <form action="{{ route('logout') }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full rounded-lg bg-[#dc2626] px-4 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#b91c1c]">Ya, Keluar</button>
            </form>
        </div>
    </div>
</div>
