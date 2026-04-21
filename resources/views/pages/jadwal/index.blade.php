@extends(getLayout())
@section('title', 'Jadwal')
@section('subtitle', 'Jadwal pelajaran')
@section('active', 'jadwal-pelajaran')

@section('content')

    @if(getUserRole() === 'admin')
        {{-- Redirect admin ke jadwal admin --}}
        @include('pages.jadwal.partials.admin')
    @elseif(getUserRole() === 'guru')
        {{-- Konten Guru: Jadwal Mengajar --}}
<div class="space-y-6" x-data="{
        activeTab: 'Senin',
        hariList: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
        jadwal: {
            'Senin': [
                { waktu: '07:30 - 09:00', durasi: '90 Menit', mapel: 'Bahasa Indonesia', kelas: 'Kelas VI - A', ruangan: 'R. Teori 6A', type: 'jadwal', status: 'Selesai' },
                { waktu: '09:00 - 10:30', durasi: '90 Menit', mapel: 'Bahasa Indonesia', kelas: 'Kelas V - A', ruangan: 'R. Teori 5A', type: 'jadwal', status: 'Menunggu' },
                { durasi: '30 Menit', type: 'istirahat' },
                { waktu: '11:00 - 12:30', durasi: '90 Menit', mapel: 'Karya Ilmiah (Ekskul)', kelas: 'Kelas Gabungan', ruangan: 'Perpustakaan', type: 'jadwal', status: 'Menunggu' }
            ],
            'Selasa': [
                { waktu: '07:30 - 09:00', durasi: '90 Menit', mapel: 'Bahasa Indonesia', kelas: 'Kelas IV - A', ruangan: 'R. Teori 4A', type: 'jadwal', status: 'Menunggu' },
                { durasi: '30 Menit', type: 'istirahat' },
                { waktu: '09:30 - 11:00', durasi: '90 Menit', mapel: 'Bahasa Indonesia', kelas: 'Kelas VI - B', ruangan: 'R. Teori 6B', type: 'jadwal', status: 'Menunggu' }
            ],
            'Rabu': [],
            'Kamis': [],
            'Jumat': []
        }
    }">
        {{-- Header Section --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a] sm:text-[36px]">Jadwal Mengajar</h1>
                <p class="mt-1 max-w-2xl text-[13px] text-[#64748b]">Tinjauan mingguan aktivitas instruksional Anda. Pastikan semua materi dan ruangan telah dipersiapkan sebelum jam pelajaran dimulai.</p>
            </div>
            <button class="flex items-center gap-2 rounded-lg bg-[#0f172a] px-4 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#1e293b]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"></path></svg>
                Cetak Jadwal
            </button>
        </div>

        <div class="grid gap-6 lg:grid-cols-[200px_1fr] xl:grid-cols-[240px_1fr]">
            
            {{-- Sidebar Kiri --}}
            <div class="flex flex-col gap-6">
                {{-- Pemilih Hari --}}
                <div class="rounded-xl bg-white p-2 shadow-sm ring-1 ring-[#e2e8f0]">
                    <p class="mb-2 px-3 pt-2 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Pilih Hari</p>
                    <div class="flex flex-col gap-1">
                        <template x-for="hari in hariList" :key="hari">
                            <button 
                                @click="activeTab = hari"
                                :class="activeTab === hari ? 'bg-[#f1f5f9] border-l-4 border-[#1d4ed8] text-[#0f172a] font-bold' : 'text-[#64748b] border-l-4 border-transparent hover:bg-[#f8fafc] font-semibold'"
                                class="flex items-center justify-between rounded-r-lg px-4 py-2.5 text-[13px] transition-all"
                            >
                                <span class="uppercase tracking-widest" x-text="hari"></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Statistik Pekan Ini --}}
                <div class="rounded-xl bg-[#f8fafc] p-5 ring-1 ring-[#e2e8f0]">
                    <div class="flex items-center gap-2 text-[#64748b]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-[10px] font-bold uppercase tracking-[0.15em]">Statistik Pekan Ini</p>
                    </div>
                    <div class="mt-4 space-y-4">
                        <div>
                            <p class="text-[28px] font-black leading-none text-[#0f172a]">24 Jam</p>
                            <p class="mt-1 text-[11px] font-bold uppercase tracking-[0.1em] text-[#64748b]">Total Durasi</p>
                        </div>
                        <div>
                            <p class="text-[28px] font-black leading-none text-[#0f172a]">12 Kelas</p>
                            <p class="mt-1 text-[11px] font-bold uppercase tracking-[0.1em] text-[#64748b]">Interaksi Siswa</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="flex flex-col gap-4">
                
                <div class="flex-1 rounded-xl bg-white shadow-sm ring-1 ring-[#e2e8f0] overflow-hidden">
                    {{-- Table Header --}}
                    <div class="grid grid-cols-[140px_1fr_140px_1fr_100px] items-center bg-[#f8fafc] border-b border-[#e2e8f0] px-6 py-4">
                        <p class="text-[10px] font-black uppercase tracking-[0.15em] text-[#64748b]">Waktu</p>
                        <p class="text-[10px] font-black uppercase tracking-[0.15em] text-[#64748b]">Mata Pelajaran</p>
                        <p class="text-[10px] font-black uppercase tracking-[0.15em] text-[#64748b]">Kelas</p>
                        <p class="text-[10px] font-black uppercase tracking-[0.15em] text-[#64748b]">Ruangan</p>
                        <p class="text-[10px] font-black uppercase tracking-[0.15em] text-[#64748b] text-center">Status</p>
                    </div>

                    {{-- Table Body --}}
                    <div class="flex flex-col h-[500px] overflow-y-auto">
                        <template x-if="jadwal[activeTab] && jadwal[activeTab].length > 0">
                            <template x-for="(item, idx) in jadwal[activeTab]" :key="idx">
                                <div>
                                    {{-- Row: Jadwal --}}
                                    <template x-if="item.type === 'jadwal'">
                                        <div class="grid grid-cols-[140px_1fr_140px_1fr_100px] items-center border-b border-[#f1f5f9] px-6 py-5 transition hover:bg-[#f8fafc]">
                                            <div>
                                                <p class="text-[14px] font-black tracking-tight text-[#0f172a]" x-text="item.waktu"></p>
                                                <p class="text-[10px] font-bold text-[#64748b] uppercase tracking-widest mt-1" x-text="item.durasi"></p>
                                            </div>
                                            <div>
                                                <p class="text-[14px] font-bold text-[#0f172a]" x-text="item.mapel"></p>
                                            </div>
                                            <div>
                                                <span class="inline-flex rounded bg-[#e2e8f0] px-3 py-1.5 text-[10px] font-black text-[#475569] uppercase tracking-wider" x-text="item.kelas"></span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <svg class="h-4 w-4 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                <p class="text-[13px] font-semibold text-[#475569]" x-text="item.ruangan"></p>
                                            </div>
                                            <div class="flex justify-center text-center relative z-10" @click.stop>
                                                <button 
                                                    x-show="item.status === 'Selesai'" 
                                                    @click="item.status = 'Menunggu'"
                                                    class="group flex items-center justify-center gap-1.5 rounded bg-[#f0fdf4] px-2.5 py-1 text-[10px] font-black tracking-wider text-[#16a34a] uppercase ring-1 ring-inset ring-[#86efac] transition hover:bg-[#dcfce7]"
                                                    title="Batalkan Status Selesai"
                                                >
                                                    <svg class="h-3 w-3 group-hover:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                    <svg class="h-3 w-3 hidden group-hover:block text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    <span>Selesai</span>
                                                </button>

                                                <button 
                                                    x-show="item.status === 'Menunggu'" 
                                                    @click="item.status = 'Selesai'"
                                                    class="group flex items-center justify-center gap-1.5 rounded bg-white px-2.5 py-1 text-[10px] font-black tracking-wider text-[#64748b] uppercase ring-1 ring-inset ring-[#cbd5e1] transition hover:bg-[#eff6ff] hover:text-[#1d4ed8] hover:ring-[#bfdbfe]"
                                                    title="Tandai Kelas Selesai"
                                                >
                                                    <span class="h-1.5 w-1.5 rounded-full bg-[#94a3b8] group-hover:bg-[#3b82f6]"></span>
                                                    <span>Tandai Selesai</span>
                                                </button>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Row: Istirahat --}}
                                    <template x-if="item.type === 'istirahat'">
                                        <div class="border-b border-[#f1f5f9] bg-[#f8fafc] px-6 py-3 text-center">
                                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Istirahat (<span x-text="item.durasi"></span>)</p>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </template>

                        {{-- Empty State --}}
                        <template x-if="!jadwal[activeTab] || jadwal[activeTab].length === 0">
                            <div class="flex h-full flex-col items-center justify-center p-10 text-center">
                                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-[#f1f5f9] text-[#94a3b8]">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <p class="text-[13px] font-bold text-[#0f172a]">Tidak Ada Jadwal</p>
                                <p class="text-[12px] text-[#64748b]">Anda tidak memiliki jadwal mengajar pada hari <span x-text="activeTab"></span>.</p>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Banner Tahun Ajaran --}}
                <div class="flex items-center gap-4 rounded-xl bg-white p-4 shadow-sm ring-1 ring-[#e2e8f0]">
                    <div class="flex h-10 w-10 flex-none items-center justify-center rounded-lg bg-[#f1f5f9] text-[#64748b]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-black tracking-tight text-[#0f172a]">TAHUN AJARAN 2026/2027</p>
                        <p class="mt-0.5 text-[10px] font-bold uppercase tracking-[0.1em] text-[#64748b]">Semester Ganjil - Revisi Ke-2</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
    @elseif(getUserRole() === 'walikelas')
        {{-- Konten Walikelas: Jadwal Kelas --}}
<div class="space-y-8" x-data="{
        days: [
            { day: 'SENIN', slots: [
                { time: '07:30 - 09:30', subject: 'Pendidikan Agama', teacher: 'H. Syamsul Maarif, Lc', isBreak: false },
                { time: '09:30 - 10:00', subject: 'ISTIRAHAT', teacher: '', isBreak: true },
                { time: '10:00 - 12:00', subject: 'Matematika', teacher: 'Drs. Bambang H.', isBreak: false },
            ]},
            { day: 'SELASA', slots: [
                { time: '07:30 - 09:30', subject: 'Bahasa Indonesia', teacher: 'Drs. M. Taufik', isBreak: false },
                { time: '09:30 - 10:00', subject: 'ISTIRAHAT', teacher: '', isBreak: true },
                { time: '10:00 - 12:00', subject: 'IPAS', teacher: 'Dr. Heru Prasetyo', isBreak: false },
            ]},
            { day: 'RABU', slots: [
                { time: '07:30 - 09:30', subject: 'Pendidikan Pancasila', teacher: 'Drs. H. Anwar', isBreak: false },
                { time: '09:30 - 10:00', subject: 'ISTIRAHAT', teacher: '', isBreak: true },
                { time: '10:00 - 12:00', subject: 'Seni Budaya', teacher: 'Larasati, S.Sn', isBreak: false },
            ]},
            { day: 'KAMIS', slots: [
                { time: '07:30 - 09:30', subject: 'PJOK', teacher: 'Slamet Raharjo, S.Pd', isBreak: false },
                { time: '09:30 - 10:00', subject: 'ISTIRAHAT', teacher: '', isBreak: true },
                { time: '10:00 - 12:00', subject: 'Matematika', teacher: 'Drs. Bambang H.', isBreak: false },
            ]},
            { day: 'JUMAT', slots: [
                { time: '07:30 - 08:30', subject: 'Pembinaan Wali Kelas', teacher: 'Drs. Ahmad Subarjo', isBreak: false },
                { time: '08:30 - 09:30', subject: 'Bahasa Indonesia', teacher: 'Drs. M. Taufik', isBreak: false },
                { time: '09:30 - 10:00', subject: 'ISTIRAHAT', teacher: '', isBreak: true },
                { time: '10:00 - 11:30', subject: 'IPAS', teacher: 'Dr. Heru Prasetyo', isBreak: false },
            ]}
        ]
    }">
        {{-- Title + Actions --}}
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a] sm:text-[36px]">Jadwal Pelajaran Kelas</h1>
                <div class="mt-2 flex flex-wrap gap-2">
                    <span class="rounded-full border border-[#e2e8f0] px-3 py-1 text-[11px] font-bold text-[#475569]">VI-A</span>
                    <span class="rounded-full border border-[#e2e8f0] px-3 py-1 text-[11px] font-bold text-[#475569]">T.A 2026/2027</span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button @click="window.print()" class="flex items-center gap-2 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Cetak Jadwal
                </button>
            </div>
        </div>

        {{-- Schedule Grid (View Mode) --}}
        <div class="flex gap-4 overflow-x-auto pb-4 custom-scrollbar items-start">
            <template x-for="(day, dIndex) in days" :key="dIndex">
                <div class="flex flex-col h-[460px] w-[280px] shrink-0 rounded-xl bg-white p-4 ring-1 ring-[#e2e8f0]">
                    {{-- Header Hari --}}
                    <div class="flex items-center justify-between border-b border-[#e2e8f0] pb-3 shrink-0">
                        <h3 class="text-[13px] font-black uppercase tracking-[0.1em] text-[#0f172a]" x-text="day.day"></h3>
                        <div class="flex items-center gap-2 shrink-0">
                            <svg class="h-4 w-4 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2" stroke-width="2"/><path d="M16 3v4M8 3v4M3 10h18" stroke-linecap="round" stroke-width="2"/></svg>
                        </div>
                    </div>

                    {{-- List Jam (Scrollable) --}}
                    <div class="mt-3 space-y-3 overflow-y-auto custom-scrollbar pr-1 flex-1">
                        <template x-for="(slot, sIndex) in day.slots" :key="sIndex">
                            <div>
                                {{-- Jika Istirahat --}}
                                <template x-if="slot.isBreak">
                                    <div class="rounded-lg bg-[#f1f5f9] p-2 text-center">
                                        <p class="text-[9px] font-semibold text-[#64748b]" x-text="slot.time"></p>
                                        <div class="py-1 text-[10px] font-bold uppercase tracking-[0.15em] text-[#94a3b8]" x-text="slot.subject"></div>
                                    </div>
                                </template>

                                {{-- Jika Pelajaran Normal --}}
                                <template x-if="!slot.isBreak">
                                    <div class="rounded-lg bg-[#f8fafc] p-3 border border-transparent">
                                        <p class="text-[10px] font-semibold text-[#64748b]" x-text="slot.time"></p>
                                        <p class="mt-1 text-[13px] font-bold text-[#0f172a]" x-text="slot.subject"></p>
                                        <p class="mt-0.5 text-[11px] text-[#64748b]" x-text="slot.teacher"></p>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>



        {{-- Bottom Section --}}
        <div class="grid gap-6 lg:grid-cols-[1fr_300px]">
            {{-- Teacher Table dgn Scroll Vertikal --}}
            <div class="space-y-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <h2 class="text-[22px] font-black tracking-[-0.03em] text-[#0f172a] sm:text-[28px]">Daftar Guru Pengajar Semester Ganjil</h2>
                    <p class="text-[11px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Total: 7 Mata Pelajaran</p>
                </div>
                
                {{-- Container yang bisa di scroll vertikal dan horizontal --}}
                <div class="rounded-xl bg-white ring-1 ring-[#e2e8f0] flex flex-col max-h-[380px] overflow-hidden">
                    <div class="overflow-x-auto overflow-y-auto flex-1 custom-scrollbar">
                        <table class="w-full text-left text-[13px] relative border-collapse">
                            <thead class="sticky top-0 z-10 bg-[#f8fafc] shadow-sm">
                                <tr>
                                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b] border-b border-[#e2e8f0]">Mata Pelajaran</th>
                                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b] border-b border-[#e2e8f0]">Guru Pengajar</th>
                                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b] border-b border-[#e2e8f0]">Kontak</th>
                                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b] border-b border-[#e2e8f0]">Ruang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Memperbanyak dummy data agar scrollnya terlihat
                                    $gurus = [
                                        ['mapel' => 'Pendidikan Agama', 'guru' => 'H. Syamsul Maarif, Lc', 'kontak' => '0821-6677-XXXX', 'ruang' => 'Masjid/R.08'],
                                        ['mapel' => 'Pendidikan Pancasila', 'guru' => 'Drs. H. Anwar', 'kontak' => '0813-2211-XXXX', 'ruang' => 'Ruang 15'],
                                        ['mapel' => 'Bahasa Indonesia', 'guru' => 'Drs. M. Taufik', 'kontak' => '0812-8877-XXXX', 'ruang' => 'Ruang 18'],
                                        ['mapel' => 'Matematika', 'guru' => 'Drs. Bambang H.', 'kontak' => '0812-3344-XXXX', 'ruang' => 'Lab Mat A'],
                                        ['mapel' => 'IPAS', 'guru' => 'Dr. Heru Prasetyo', 'kontak' => '0819-9900-XXXX', 'ruang' => 'Lab Biologi'],
                                        ['mapel' => 'PJOK', 'guru' => 'Slamet Raharjo, S.Pd', 'kontak' => '0877-3311-XXXX', 'ruang' => 'Lap. Utama'],
                                        ['mapel' => 'Seni Budaya', 'guru' => 'Larasati, S.Sn', 'kontak' => '0899-4455-XXXX', 'ruang' => 'Lab Seni'],
                                    ];
                                @endphp
                                @foreach ($gurus as $g)
                                    <tr class="border-b border-[#f1f5f9] hover:bg-[#f8fafc] transition">
                                        <td class="px-5 py-3.5 font-bold text-[#0f172a]">{{ $g['mapel'] }}</td>
                                        <td class="px-5 py-3.5 text-[#475569]">{{ $g['guru'] }}</td>
                                        <td class="px-5 py-3.5 text-[#475569]">{{ $g['kontak'] }}</td>
                                        <td class="px-5 py-3.5 text-[#475569]">{{ $g['ruang'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Right Sidebar Cards --}}
            <div class="space-y-4">
                {{-- Catatan Perwalian --}}
                <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Catatan Perwalian</p>
                    <blockquote class="mt-3 text-[13px] italic leading-[1.8] text-[#334155]">
                        "Pastikan seluruh siswa sudah berada di kelas 5 menit sebelum jam pelajaran pertama dimulai. Hari Senin diwajibkan menggunakan atribut lengkap untuk Upacara Bendera."
                    </blockquote>
                    <div class="mt-4 flex items-center gap-2 text-[11px] font-bold text-[#64748b]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#1d4ed8]"></span>
                        Update: 12 Okt 2023
                    </div>
                </div>

                {{-- Ringkasan Beban Jam --}}
                <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Ringkasan Beban Jam</p>
                    <div class="mt-3 flex items-end gap-2">
                        <span class="text-[12px] text-[#475569]">Total Jam Pelajaran</span>
                        <span class="ml-auto text-[36px] font-black leading-none text-[#0f172a]">42</span>
                        <span class="pb-1 text-[11px] font-bold uppercase text-[#64748b]">JP/Minggu</span>
                    </div>
                    <div class="mt-4 border-t border-[#e2e8f0] pt-3">
                        <div class="flex items-centatmer justify-between text-[11px] font-bold uppercase tracking-wider text-[#64748b]">
                            <span>Kapasitas</span>
                            <span>85% Terisi</span>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-[#e2e8f0]">
                            <div class="h-full w-[85%] rounded-full bg-[#1d4ed8]"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
