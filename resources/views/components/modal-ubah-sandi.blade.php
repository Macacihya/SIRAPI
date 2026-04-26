{{-- ============================================================
     Komponen: Modal Ubah Sandi (Self-contained)
     Deskripsi: Modal ubah kata sandi yang siap pakai.
     Menggantikan 3x kode identik di profil admin/guru/walikelas.

     Props:
       - $alpineShow : nama variabel Alpine (default: "showUbahSandi")

     Requirement Alpine.js di parent x-data:
       - sandiForm: { lama: '', baru: '', konfirmasi: '' }
       - submitSandi() method

     Output HTML IDENTIK dengan modal ubah sandi yang ada.
     ============================================================ --}}

@props([
    'alpineShow' => 'showUbahSandi',
])

<x-modal :alpineShow="$alpineShow" title="Ubah Kata Sandi">
    <div class="space-y-4">
        <div>
            <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Password Lama</label>
            <input x-model="sandiForm.lama" type="password" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="••••••••">
        </div>
        <div>
            <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Password Baru</label>
            <input x-model="sandiForm.baru" type="password" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="••••••••">
        </div>
        <div>
            <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Konfirmasi Password</label>
            <input x-model="sandiForm.konfirmasi" type="password" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="••••••••">
        </div>
    </div>

    <x-slot:footer>
        <button @click="{{ $alpineShow }} = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
        <button @click="submitSandi()" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Ubah Sandi</button>
    </x-slot:footer>
</x-modal>
