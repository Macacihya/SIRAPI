{{-- Dashboard Partial: Admin TU
     Konten dari pages/admin/dashboard-admin.blade.php (tanpa <x-admin-shell> wrapper) --}}

<div x-data="{ showAllActivity: false, showSupport: false, supportForm: { nama: '', email: '', pesan: '' } }" class="space-y-6">

    @if(!$tahunAktif)
        <div class="rounded-[14px] border border-amber-200 bg-amber-50 p-6 flex items-start gap-4 shadow-sm animate-pulse">
            <div class="flex h-12 w-12 flex-none items-center justify-center rounded-[8px] bg-amber-100 text-amber-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-base font-bold text-amber-800">Periode Akademik Aktif Belum Ditentukan</h3>
                <p class="mt-1 text-[13px] leading-relaxed text-amber-700">Untuk dapat mengaktifkan sistem secara penuh, silakan tentukan dan aktifkan Tahun Ajaran terlebih dahulu. Ini adalah langkah awal sebelum website dapat digunakan oleh guru dan wali kelas.</p>
                <div class="mt-3">
                    <a href="{{ route('akademik') }}" class="inline-flex items-center justify-center h-[34px] rounded bg-amber-600 px-4 text-xs font-bold text-white transition hover:bg-amber-700">
                        Atur Periode Akademik Sekarang
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- ─── TOP: Heading + Tahun Ajaran ─────────────────── --}}
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_300px]">
        <div>
            <h1 class="text-[32px] font-black tracking-[-0.04em] text-[#0f172a] lg:text-[40px]">Ringkasan Administrasi</h1>
            <p class="mt-3 max-w-[560px] text-[14px] leading-[1.8] text-[#475569]">Pantau seluruh operasional administratif sekolah dalam satu tampilan pusat. Data disinkronkan secara real-time dari setiap departemen.</p>
        </div>
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Tahun Ajaran Aktif</p>
            <p class="mt-2 text-[28px] font-black tracking-[-0.04em] text-[#0f172a]">{{ $tahunAktif ? $tahunAktif->tahun_mulai.'/'.$tahunAktif->tahun_selesai.' '.$tahunAktif->semester : 'Belum diatur' }}</p>
            <span class="mt-2 inline-flex rounded-[4px] bg-[#f1f5f9] px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-[0.08em] text-[#64748b]">{{ $tahunAktif ? 'Status: Aktif & Berjalan' : 'Belum ada tahun ajaran aktif' }}</span>
        </div>
    </div>

    {{-- ─── STAT CARDS + ABSENSI ────────────────────────── --}}
    <div class="grid gap-4 lg:grid-cols-[1fr_1fr_1fr_280px]">
        <x-card-stat title="Siswa Aktif" :value="$totalSiswa">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" stroke-linecap="round" stroke-width="2"></path><circle cx="9.5" cy="7" r="3" stroke-width="2"></circle><path d="M20 21v-2a4 4 0 0 0-3-3.87" stroke-linecap="round" stroke-width="2"></path><path d="M16 4.13a3 3 0 0 1 0 5.74" stroke-linecap="round" stroke-width="2"></path></svg>
            </x-slot:icon>
        </x-card-stat>

        <x-card-stat title="Guru" :value="$totalGuru" variant="blue">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </x-slot:icon>
        </x-card-stat>

        <x-card-stat title="Ruang Kelas" :value="$totalKelas">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </x-slot:icon>
        </x-card-stat>

        <x-card-stat title="Absensi Hari Ini" value="98.2%">
            <div class="mt-4 flex items-end gap-[3px]">
                @foreach ([65, 80, 55, 90, 75, 95, 85] as $h)
                    <div class="flex-1 rounded-sm bg-[#1d4ed8]" style="height: {{ $h * 0.4 }}px; opacity: {{ 0.4 + ($h / 100) * 0.6 }}"></div>
                @endforeach
            </div>
        </x-card-stat>
    </div>

    {{-- ─── AKTIVITAS + STATUS DEPARTEMEN ───────────────── --}}
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_300px]">
        <x-section-card 
            title="Aktivitas Sistem Terkini" 
            subtitle="Log transaksi data administrasi terbaru"
        >
            <x-slot:action>
                <button @click="showAllActivity = true" class="text-[11px] font-bold uppercase tracking-[0.12em] text-[#1d4ed8] hover:text-[#1e40af]">Lihat Semua</button>
            </x-slot:action>

            <div class="mt-6 space-y-0 divide-y divide-[#f1f5f9]">
                @forelse ($logAktivitas->take(3) as $log)
                    <div class="flex items-start gap-4 py-4">
                        <div class="flex h-10 w-10 flex-none items-center justify-center rounded-[8px] bg-[#eff6ff] text-[#1d4ed8]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6l4 2" stroke-width="2" stroke-linecap="round"></path><circle cx="12" cy="12" r="10" stroke-width="2"></circle></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-[14px] font-bold text-[#0f172a]">{{ $log->judul }}</p>
                                <span class="flex-none text-[10px] font-bold uppercase tracking-[0.08em] text-[#94a3b8]">{{ \Carbon\Carbon::parse($log->waktu)->diffForHumans() }}</span>
                            </div>
                            <p class="mt-1 text-[13px] leading-[1.6] text-[#64748b]">{{ $log->deskripsi }}</p>
                        </div>
                    </div>
                @empty
                    <div class="py-4 text-center text-[13px] text-[#64748b]">Belum ada aktivitas.</div>
                @endforelse
            </div>
        </x-section-card>

        <div class="space-y-4">
            <x-section-card title="Status Departemen" padding="p-6">
                <div class="mt-5 space-y-4">
                    @foreach ([['name' => 'Kepegawaian', 'value' => 90, 'label' => '90% Lengkap'],['name' => 'Kesiswaan', 'value' => 75, 'label' => '75% Terverifikasi'],['name' => 'Sarana Prasarana', 'value' => 62, 'label' => '62% Terdata']] as $dept)
                        <div>
                            <div class="flex items-center justify-between text-[12px]"><span class="font-semibold text-[#334155]">{{ $dept['name'] }}</span><span class="font-bold text-[#64748b]">{{ $dept['label'] }}</span></div>
                            <div class="mt-2 h-[6px] overflow-hidden rounded-full bg-[#e2e8f0]"><div class="h-full rounded-full bg-[#1d4ed8]" style="width: {{ $dept['value'] }}%"></div></div>
                        </div>
                    @endforeach
                </div>
            </x-section-card>

            <x-section-card padding="p-6" class="text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#eff6ff] text-[#1d4ed8]"><svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" stroke-width="2" stroke-linecap="round"></path><path d="M12 17h.01" stroke-width="2" stroke-linecap="round"></path></svg></div>
                <h4 class="mt-3 text-[15px] font-bold text-[#0f172a]">Butuh Bantuan Teknis?</h4>
                <p class="mt-1 text-[12px] leading-[1.7] text-[#64748b]">Hubungi departemen IT untuk masalah akses aplikasi SIRAPI.</p>
                <button @click="showSupport = true" class="mt-4 flex h-[40px] w-full items-center justify-center rounded-[4px] bg-[#1d4ed8] text-[11px] font-bold uppercase tracking-[0.12em] text-white transition hover:bg-[#1e40af]">Support Center</button>
            </x-section-card>
        </div>
    </div>

    {{-- ═══ MODAL: Lihat Semua Aktivitas ═══ --}}
    <x-modal alpineShow="showAllActivity" title="Semua Aktivitas Sistem" maxWidth="2xl">
        <div class="overflow-y-auto px-6 py-4 divide-y divide-[#f1f5f9] -mx-6 -my-5 max-h-[70vh]">
            @forelse ($logAktivitas as $log)
                <div class="flex items-start gap-4 py-4 first:pt-0 last:pb-0">
                    <div class="flex h-9 w-9 flex-none items-center justify-center rounded-[8px] bg-[#eff6ff] text-[#1d4ed8]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"></circle><path d="M12 6v6l4 2" stroke-width="2" stroke-linecap="round"></path></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2"><p class="text-[13px] font-bold text-[#0f172a]">{{ $log->judul }}</p><span class="flex-none text-[10px] font-bold uppercase tracking-[0.08em] text-[#94a3b8]">{{ \Carbon\Carbon::parse($log->waktu)->format('d M Y H:i') }}</span></div>
                        <p class="mt-1 text-[12px] text-[#64748b]">{{ $log->deskripsi }}</p>
                    </div>
                </div>
            @empty
                <div class="py-4 text-center text-[13px] text-[#64748b]">Belum ada aktivitas.</div>
            @endforelse
        </div>
    </x-modal>

    {{-- ═══ MODAL: Support Center ═══ --}}
    <x-modal alpineShow="showSupport" title="Hubungi Support" maxWidth="md">
        <div class="space-y-4">
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama</label><input x-model="supportForm.nama" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" type="text" placeholder="Nama lengkap"></div>
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email</label><input x-model="supportForm.email" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" type="email" placeholder="email@sirapi.sch.id"></div>
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Pesan</label><textarea x-model="supportForm.pesan" class="mt-1 flex w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 py-3 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" rows="3" placeholder="Jelaskan kendala Anda..."></textarea></div>
        </div>
        <x-slot:footer>
            <button @click="showSupport = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569] hover:bg-[#f1f5f9]">Batal</button>
            <button @click="showSupport = false; supportForm = {nama:'',email:'',pesan:''}; $dispatch('toast', {message: 'Pesan berhasil dikirim ke IT Support!', type: 'success'})" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white hover:bg-[#1e40af]">Kirim Pesan</button>
        </x-slot:footer>
    </x-modal>

</div>
