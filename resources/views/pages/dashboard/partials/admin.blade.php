{{-- Dashboard Partial: Admin TU
     Konten dari pages/admin/dashboard-admin.blade.php (tanpa <x-admin-shell> wrapper) --}}

<div x-data="{ showAllActivity: false, showSupport: false, supportForm: { nama: '', email: '', pesan: '' } }" class="space-y-6">

    {{-- ─── TOP: Heading + Tahun Ajaran ─────────────────── --}}
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_300px]">
        <div>
            <h1 class="text-[32px] font-black tracking-[-0.04em] text-[#0f172a] lg:text-[40px]">Ringkasan Administrasi</h1>
            <p class="mt-3 max-w-[560px] text-[14px] leading-[1.8] text-[#475569]">Pantau seluruh operasional administratif sekolah dalam satu tampilan pusat. Data disinkronkan secara real-time dari setiap departemen.</p>
        </div>
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Tahun Ajaran Aktif</p>
            <p class="mt-2 text-[28px] font-black tracking-[-0.04em] text-[#0f172a]">2025/2026 Ganjil</p>
            <span class="mt-2 inline-flex rounded-[4px] bg-[#f1f5f9] px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-[0.08em] text-[#64748b]">Status: Aktif & Berjalan</span>
        </div>
    </div>

    {{-- ─── STAT CARDS + ABSENSI ────────────────────────── --}}
    <div class="grid gap-4 lg:grid-cols-[1fr_1fr_1fr_280px]">
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
            <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-[8px] bg-[#f1f5f9] text-[#64748b]"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" stroke-linecap="round" stroke-width="2"></path><circle cx="9.5" cy="7" r="3" stroke-width="2"></circle><path d="M20 21v-2a4 4 0 0 0-3-3.87" stroke-linecap="round" stroke-width="2"></path><path d="M16 4.13a3 3 0 0 1 0 5.74" stroke-linecap="round" stroke-width="2"></path></svg></div>
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Siswa Aktif</p>
            <p class="mt-2 text-[48px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">1,248</p>
        </div>
        <div class="rounded-[14px] bg-[#1d4ed8] p-6 text-white">
            <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-[8px] bg-white/20"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></div>
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-white/70">Guru</p>
            <p class="mt-2 text-[48px] font-black leading-none tracking-[-0.06em]">94</p>
        </div>
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
            <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-[8px] bg-[#f1f5f9] text-[#64748b]"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></div>
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Ruang Kelas</p>
            <p class="mt-2 text-[48px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">36</p>
        </div>
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Absensi Hari Ini</p>
            <p class="mt-2 text-[48px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">98.2%</p>
            <div class="mt-4 flex items-end gap-[3px]">
                @foreach ([65, 80, 55, 90, 75, 95, 85] as $h)
                    <div class="flex-1 rounded-sm bg-[#1d4ed8]" style="height: {{ $h * 0.4 }}px; opacity: {{ 0.4 + ($h / 100) * 0.6 }}"></div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ─── AKTIVITAS + STATUS DEPARTEMEN ───────────────── --}}
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_300px]">
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-[20px] font-black tracking-[-0.04em] text-[#0f172a]">Aktivitas Sistem Terkini</h3>
                    <p class="mt-1 text-[13px] text-[#64748b]">Log transaksi data administrasi terbaru</p>
                </div>
                <button @click="showAllActivity = true" class="text-[11px] font-bold uppercase tracking-[0.12em] text-[#1d4ed8] hover:text-[#1e40af]">Lihat Semua</button>
            </div>
            <div class="mt-6 space-y-0 divide-y divide-[#f1f5f9]">
                @foreach ([
                    ['icon' => 'user-plus', 'title' => 'Pendaftaran Siswa Baru', 'desc' => 'Admin TU-02 telah menambahkan 5 berkas siswa baru ke Database Siswa.', 'time' => '10 Menit Lalu'],
                    ['icon' => 'calendar', 'title' => 'Update Jadwal Akademik', 'desc' => 'Perubahan jadwal mata pelajaran Matematika Kelas XI-A oleh Kurikulum.', 'time' => '2 Jam Lalu'],
                    ['icon' => 'check', 'title' => 'Validasi Nilai Akhir Semester', 'desc' => 'Sistem otomatis memproses laporan penilaian untuk 12 kelas di jenjang Kelas X.', 'time' => 'Dibuat Kemarin'],
                ] as $log)
                    <div class="flex items-start gap-4 py-4">
                        <div class="flex h-10 w-10 flex-none items-center justify-center rounded-[8px] bg-[#eff6ff] text-[#1d4ed8]">
                            @if ($log['icon'] === 'user-plus')
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke-width="2" stroke-linecap="round"></path><circle cx="8.5" cy="7" r="4" stroke-width="2"></circle><path d="M20 8v6m3-3h-6" stroke-width="2" stroke-linecap="round"></path></svg>
                            @elseif ($log['icon'] === 'calendar')
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"></rect><path d="M16 2v4M8 2v4M3 10h18" stroke-width="2" stroke-linecap="round"></path></svg>
                            @else
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"></rect><path d="m9 12 2 2 4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-[14px] font-bold text-[#0f172a]">{{ $log['title'] }}</p>
                                <span class="flex-none text-[10px] font-bold uppercase tracking-[0.08em] text-[#94a3b8]">{{ $log['time'] }}</span>
                            </div>
                            <p class="mt-1 text-[13px] leading-[1.6] text-[#64748b]">{{ $log['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="space-y-4">
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Status Departemen</p>
                <div class="mt-5 space-y-4">
                    @foreach ([['name' => 'Kepegawaian', 'value' => 90, 'label' => '90% Lengkap'],['name' => 'Kesiswaan', 'value' => 75, 'label' => '75% Terverifikasi'],['name' => 'Sarana Prasarana', 'value' => 62, 'label' => '62% Terdata']] as $dept)
                        <div>
                            <div class="flex items-center justify-between text-[12px]"><span class="font-semibold text-[#334155]">{{ $dept['name'] }}</span><span class="font-bold text-[#64748b]">{{ $dept['label'] }}</span></div>
                            <div class="mt-2 h-[6px] overflow-hidden rounded-full bg-[#e2e8f0]"><div class="h-full rounded-full bg-[#1d4ed8]" style="width: {{ $dept['value'] }}%"></div></div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#eff6ff] text-[#1d4ed8]"><svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" stroke-width="2" stroke-linecap="round"></path><path d="M12 17h.01" stroke-width="2" stroke-linecap="round"></path></svg></div>
                <h4 class="mt-3 text-[15px] font-bold text-[#0f172a]">Butuh Bantuan Teknis?</h4>
                <p class="mt-1 text-[12px] leading-[1.7] text-[#64748b]">Hubungi departemen IT untuk masalah akses aplikasi SIRAPI.</p>
                <button @click="showSupport = true" class="mt-4 flex h-[40px] w-full items-center justify-center rounded-[4px] bg-[#1d4ed8] text-[11px] font-bold uppercase tracking-[0.12em] text-white transition hover:bg-[#1e40af]">Support Center</button>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Lihat Semua Aktivitas ═══ --}}
    <div x-show="showAllActivity" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showAllActivity = false">
        <div class="w-[90%] max-w-2xl rounded-2xl bg-white shadow-2xl max-h-[80vh] flex flex-col" @click.stop>
            <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4">
                <h3 class="text-[18px] font-black text-[#0f172a]">Semua Aktivitas Sistem</h3>
                <button @click="showAllActivity = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path></svg></button>
            </div>
            <div class="flex-1 overflow-y-auto px-6 py-4 divide-y divide-[#f1f5f9]">
                @foreach ([
                    ['title' => 'Pendaftaran Siswa Baru', 'desc' => 'Admin TU-02 menambahkan 5 berkas siswa baru.', 'time' => '10 Menit Lalu'],
                    ['title' => 'Update Jadwal Akademik', 'desc' => 'Perubahan jadwal Matematika Kelas XI-A.', 'time' => '2 Jam Lalu'],
                    ['title' => 'Validasi Nilai Akhir Semester', 'desc' => 'Sistem memproses laporan 12 kelas Kelas X.', 'time' => 'Kemarin'],
                    ['title' => 'Sinkronisasi Data DAPODIK', 'desc' => 'Berhasil sinkron 1,248 data siswa aktif.', 'time' => '2 Hari Lalu'],
                    ['title' => 'Perubahan Jadwal Mengajar', 'desc' => 'Guru B. Wijaya dipindahkan ke jadwal Kamis.', 'time' => '3 Hari Lalu'],
                    ['title' => 'Backup Database Triwulan', 'desc' => 'Backup otomatis berhasil (2.4GB).', 'time' => '1 Minggu Lalu'],
                ] as $log)
                    <div class="flex items-start gap-4 py-4">
                        <div class="flex h-9 w-9 flex-none items-center justify-center rounded-[8px] bg-[#eff6ff] text-[#1d4ed8]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"></circle><path d="M12 6v6l4 2" stroke-width="2" stroke-linecap="round"></path></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2"><p class="text-[13px] font-bold text-[#0f172a]">{{ $log['title'] }}</p><span class="flex-none text-[10px] font-bold uppercase tracking-[0.08em] text-[#94a3b8]">{{ $log['time'] }}</span></div>
                            <p class="mt-1 text-[12px] text-[#64748b]">{{ $log['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Support Center ═══ --}}
    <div x-show="showSupport" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showSupport = false">
        <div class="w-[90%] max-w-md rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4">
                <h3 class="text-[18px] font-black text-[#0f172a]">Hubungi Support</h3>
                <button @click="showSupport = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path></svg></button>
            </div>
            <div class="space-y-4 px-6 py-5">
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama</label><input x-model="supportForm.nama" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" type="text" placeholder="Nama lengkap"></div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email</label><input x-model="supportForm.email" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" type="email" placeholder="email@sirapi.sch.id"></div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Pesan</label><textarea x-model="supportForm.pesan" class="mt-1 flex w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 py-3 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" rows="3" placeholder="Jelaskan kendala Anda..."></textarea></div>
            </div>
            <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                <button @click="showSupport = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569] hover:bg-[#f1f5f9]">Batal</button>
                <button @click="showSupport = false; supportForm = {nama:'',email:'',pesan:''}; $dispatch('toast', {message: 'Pesan berhasil dikirim ke IT Support!', type: 'success'})" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white hover:bg-[#1e40af]">Kirim Pesan</button>
            </div>
        </div>
    </div>

</div>
