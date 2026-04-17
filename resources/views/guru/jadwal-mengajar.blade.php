<x-guru-shell
    active="jadwal-mengajar"
    title="Jadwal Mengajar"
    subtitle="Selamat datang di Panel Guru Mata Pelajaran"
>
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
</x-guru-shell>
