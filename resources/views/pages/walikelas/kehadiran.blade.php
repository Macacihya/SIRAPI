<x-walikelas-shell
    :user="auth()->user()"
    active="kehadiran"
    title="Kehadiran Siswa"
    subtitle="Selamat datang di Panel Wali Kelas"
>
    <div class="space-y-6" x-data="{
        activeTab: 'input',
        saveModalOpen: false,
        selectedDate: new Date().toISOString().split('T')[0],

        get formattedDate() {
            const d = new Date(this.selectedDate + 'T00:00:00');
            const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            return days[d.getDay()] + ', ' + d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
        },

        siswaKehadiran: [
            { no: '01', nama: 'Achmad Albar', status: 'hadir', ket: '' },
            { no: '02', nama: 'Oscar Permana', status: 'alpa', ket: 'Tanpa Keterangan' },
            { no: '03', nama: 'Bella Monica', status: 'izin', ket: 'Acara Keluarga' },
            { no: '04', nama: 'Gita Ananda', status: 'hadir', ket: '' },
            { no: '05', nama: 'Endah Kartika', status: 'hadir', ket: '' },
            { no: '06', nama: 'Rina Agustina', status: 'hadir', ket: '' },
            { no: '07', nama: 'Dandi Pratama', status: 'hadir', ket: '' },
            { no: '08', nama: 'Farhan Azis', status: 'sakit', ket: 'Demam' },
            { no: '09', nama: 'Hendra Yulian', status: 'hadir', ket: '' },
            { no: '10', nama: 'Intan Sari', status: 'alpa', ket: '' },
            { no: '11', nama: 'Joko Wibowo', status: 'hadir', ket: '' },
            { no: '12', nama: 'Kirana Rahma', status: 'hadir', ket: '' },
            { no: '13', nama: 'Lukman Putra', status: 'izin', ket: 'Sakit Gigi' },
            { no: '14', nama: 'Maya Nurhaliza', status: 'hadir', ket: '' },
            { no: '15', nama: 'Nanda Rizky', status: 'hadir', ket: '' },
            { no: '16', nama: 'Putri Setiawan', status: 'hadir', ket: '' },
            { no: '17', nama: 'Qiyamul Haq', status: 'hadir', ket: '' },
            { no: '18', nama: 'Surya Bagaskara', status: 'hadir', ket: '' },
        ],

        get statsHadir() { return this.siswaKehadiran.filter(s => s.status === 'hadir').length; },
        get statsIzin() { return this.siswaKehadiran.filter(s => s.status === 'izin').length; },
        get statsSakit() { return this.siswaKehadiran.filter(s => s.status === 'sakit').length; },
        get statsAlpa() { return this.siswaKehadiran.filter(s => s.status === 'alpa').length; },
        get statsPercent() { return Math.round(this.statsHadir / this.siswaKehadiran.length * 100); },

        // Rekap bulanan (dummy)
        rekapBulan: [
            { bulan: 'Oktober 2023', hadir: 92, izin: 4, sakit: 3, alpa: 1 },
            { bulan: 'November 2023', hadir: 88, izin: 6, sakit: 4, alpa: 2 },
            { bulan: 'Desember 2023', hadir: 95, izin: 2, sakit: 2, alpa: 1 },
        ],

        savedToast: false,
        doSave() {
            this.saveModalOpen = false;
            this.savedToast = true;
            setTimeout(() => this.savedToast = false, 3000);
        },
        setSemuaHadir() {
            this.siswaKehadiran.forEach(s => {
                s.status = 'hadir';
                s.ket = '';
            });
        }
    }">
        <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a] sm:text-[36px]">Kehadiran Siswa</h1>

        {{-- Controls --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex rounded-lg border border-[#e2e8f0] bg-white p-1">
                <button @click="activeTab = 'input'" :class="activeTab === 'input' ? 'bg-[#0f172a] text-white' : 'text-[#64748b] hover:bg-[#f1f5f9]'" class="rounded-md px-4 py-2 text-[12px] font-bold transition">Input Kehadiran</button>
                <button @click="activeTab = 'rekap'" :class="activeTab === 'rekap' ? 'bg-[#0f172a] text-white' : 'text-[#64748b] hover:bg-[#f1f5f9]'" class="rounded-md px-4 py-2 text-[12px] font-bold transition">Rekap Kehadiran</button>
            </div>
            <div class="flex items-center gap-3" x-show="activeTab === 'input'">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Pilih Tanggal</p>
                    <div class="mt-1 flex items-center gap-2">
                        {{-- Batas minimum otomatis disesuaikan awal semester yang sedang aktif (Ganjil: Juli, Genap: Januari) --}}
                        <input type="date" min="{{ date('n') >= 7 ? date('Y').'-07-01' : date('Y').'-01-01' }}" max="{{ date('Y-m-d') }}" x-model="selectedDate" class="h-10 rounded-lg border border-[#e2e8f0] bg-white px-3 text-[13px] font-semibold text-[#0f172a] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                    </div>
                </div>
                <button @click="saveModalOpen = true" class="mt-5 rounded-lg bg-[#1d4ed8] px-5 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#2563eb]">Simpan Kehadiran</button>
            </div>
        </div>

        {{-- ═══ TAB: INPUT KEHADIRAN ═══ --}}
        <div x-show="activeTab === 'input'" x-transition>
            <div class="grid gap-6 lg:grid-cols-[1fr_280px]">
                {{-- Attendance Table --}}
                <div class="rounded-xl bg-white ring-1 ring-[#e2e8f0]">
                    <div class="flex items-center justify-between border-b border-[#e2e8f0] px-5 py-4">
                        <h3 class="text-[14px] font-bold text-[#0f172a]">Daftar Siswa Kelas XI-IPA 1</h3>
                        <div class="flex items-center gap-4">
                            <button @click="setSemuaHadir()" class="flex items-center gap-1.5 rounded-md bg-[#eff6ff] px-2.5 py-1 text-[11px] font-bold text-[#1d4ed8] transition hover:bg-[#dbeafe]">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Set Semua Hadir
                            </button>
                            <span class="flex items-center gap-1.5 text-[12px] text-[#64748b]"><span class="h-1.5 w-1.5 rounded-full bg-[#0f172a]"></span> <span x-text="siswaKehadiran.length"></span> Total Siswa</span>
                        </div>
                    </div>
                    <div class="overflow-auto max-h-[600px]">
                        <table class="w-full text-[13px]">
                            <thead>
                                <tr>
                                    <th class="sticky top-0 z-10 bg-[#f8fafc] border-b border-[#e2e8f0] px-5 py-3 text-left text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">No</th>
                                    <th class="sticky top-0 z-10 bg-[#f8fafc] border-b border-[#e2e8f0] px-5 py-3 text-left text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Nama Siswa</th>
                                    <th class="sticky top-0 z-10 bg-[#f8fafc] border-b border-[#e2e8f0] px-3 py-3 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Hadir</th>
                                    <th class="sticky top-0 z-10 bg-[#f8fafc] border-b border-[#e2e8f0] px-3 py-3 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Izin</th>
                                    <th class="sticky top-0 z-10 bg-[#f8fafc] border-b border-[#e2e8f0] px-3 py-3 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Sakit</th>
                                    <th class="sticky top-0 z-10 bg-[#f8fafc] border-b border-[#e2e8f0] px-3 py-3 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Alpa</th>
                                    <th class="sticky top-0 z-10 bg-[#f8fafc] border-b border-[#e2e8f0] px-5 py-3 text-left text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(s, idx) in siswaKehadiran" :key="idx">
                                    <tr class="border-b border-[#f1f5f9]">
                                        <td class="px-5 py-4 text-[#64748b]" x-text="s.no"></td>
                                        <td class="px-5 py-4 font-bold text-[#0f172a]" x-text="s.nama"></td>
                                        <template x-for="opt in ['hadir', 'izin', 'sakit', 'alpa']" :key="opt">
                                            <td class="px-3 py-4 text-center">
                                                <input type="radio" :name="'absen_' + s.no" :value="opt" :checked="s.status === opt" @change="siswaKehadiran[idx].status = opt"
                                                    class="h-5 w-5 border-2 border-[#cbd5e1] text-[#1d4ed8] focus:ring-[#3b82f6]/30">
                                            </td>
                                        </template>
                                        <td class="px-5 py-4">
                                            <input type="text" x-model="siswaKehadiran[idx].ket" placeholder="Opsional..." class="w-full rounded border border-[#e2e8f0] bg-[#f8fafc] px-2 py-1 text-[12px] text-[#475569] outline-none focus:border-[#3b82f6] focus:bg-white">
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Right Sidebar --}}
                <div class="space-y-4">
                    {{-- Statistik --}}
                    <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Statistik Hari Ini</p>
                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <div class="rounded-lg bg-[#f1f5f9] p-3 text-center">
                                <p class="text-[32px] font-black leading-none text-[#0f172a]" x-text="statsHadir">0</p>
                                <p class="mt-1 text-[9px] font-bold uppercase tracking-wider text-[#64748b]">Hadir</p>
                            </div>
                            <div class="rounded-lg bg-[#eff6ff] p-3 text-center">
                                <p class="text-[32px] font-black leading-none text-[#1d4ed8]" x-text="statsIzin">0</p>
                                <p class="mt-1 text-[9px] font-bold uppercase tracking-wider text-[#64748b]">Izin</p>
                            </div>
                            <div class="rounded-lg bg-[#f1f5f9] p-3 text-center">
                                <p class="text-[32px] font-black leading-none text-[#475569]" x-text="statsSakit">0</p>
                                <p class="mt-1 text-[9px] font-bold uppercase tracking-wider text-[#64748b]">Sakit</p>
                            </div>
                            <div class="rounded-lg bg-[#fef2f2] p-3 text-center">
                                <p class="text-[32px] font-black leading-none text-[#dc2626]" x-text="statsAlpa">0</p>
                                <p class="mt-1 text-[9px] font-bold uppercase tracking-wider text-[#64748b]">Alpa</p>
                            </div>
                        </div>
                        <div class="mt-4 border-t border-[#e2e8f0] pt-3">
                            <div class="flex items-center justify-between text-[12px]">
                                <span class="text-[#475569]">Persentase Kehadiran</span>
                                <span class="font-bold text-[#0f172a]" x-text="statsPercent + '%'">0%</span>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-[#e2e8f0]"><div class="h-full rounded-full bg-[#1d4ed8] transition-all" :style="'width:' + statsPercent + '%'"></div></div>
                        </div>
                    </div>

                    {{-- Informasi --}}
                    <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Informasi</p>
                        <div class="mt-3 space-y-3">
                            <div class="flex gap-2">
                                <svg class="mt-0.5 h-3.5 w-3.5 flex-none text-[#3b82f6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path d="M12 6v6l4 2" stroke-width="2" stroke-linecap="round"/></svg>
                                <p class="text-[12px] leading-relaxed text-[#475569]">Input kehadiran harus diselesaikan sebelum pukul 09:00 WIB.</p>
                            </div>
                            <div class="flex gap-2">
                                <svg class="mt-0.5 h-3.5 w-3.5 flex-none text-[#3b82f6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path d="M12 6v6l4 2" stroke-width="2" stroke-linecap="round"/></svg>
                                <p class="text-[12px] leading-relaxed text-[#475569]">Siswa dengan status Alpa akan otomatis dikirim notifikasi ke orang tua.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Kalender --}}
                    <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Kalender Akademik</p>
                        <div class="mt-3">
                            <p class="text-[32px] font-black leading-none text-[#0f172a]" x-text="['JANUARI','FEBRUARI','MARET','APRIL','MEI','JUNI','JULI','AGUSTUS','SEPTEMBER','OKTOBER','NOVEMBER','DESEMBER'][new Date(selectedDate).getMonth()]">
                            </p>
                            <p class="mt-2 text-[13px] text-[#475569]" x-text="'Tahun ' + new Date(selectedDate).getFullYear()"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ TAB: REKAP KEHADIRAN ═══ --}}
        <div x-show="activeTab === 'rekap'" x-transition style="display: none;">
            <div class="space-y-4">
                <div class="flex items-end justify-between">
                    <div>
                        <h2 class="text-[22px] font-black tracking-[-0.03em] text-[#0f172a]">Rekap Kehadiran Bulanan</h2>
                        <p class="mt-1 text-[13px] text-[#64748b]">Ringkasan persentase kehadiran per bulan untuk Semester Ganjil 2026/2027</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="flex items-center gap-2 rounded-lg border border-[#e2e8f0] bg-white px-3 py-2 text-[11px] font-extrabold uppercase tracking-[0.12em] text-[#475569] transition hover:bg-[#f1f5f9]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"/></svg>
                            Cetak
                        </button>
                        <button class="flex items-center gap-2 rounded-lg bg-[#16a34a] px-3 py-2 text-[11px] font-extrabold uppercase tracking-[0.12em] text-white transition hover:bg-[#15803d]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Export Excel
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto rounded-xl bg-white ring-1 ring-[#e2e8f0]">
                    <table class="w-full text-[13px]">
                        <thead>
                            <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                                <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Bulan</th>
                                <th class="px-4 py-3 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Hadir (%)</th>
                                <th class="px-4 py-3 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Izin (%)</th>
                                <th class="px-4 py-3 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Sakit (%)</th>
                                <th class="px-4 py-3 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Alpa (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="r in rekapBulan" :key="r.bulan">
                                <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                                    <td class="px-5 py-4 font-bold text-[#0f172a]" x-text="r.bulan"></td>
                                    <td class="px-4 py-4 text-center font-bold text-[#16a34a]" x-text="r.hadir + '%'"></td>
                                    <td class="px-4 py-4 text-center text-[#475569]" x-text="r.izin + '%'"></td>
                                    <td class="px-4 py-4 text-center text-[#475569]" x-text="r.sakit + '%'"></td>
                                    <td class="px-4 py-4 text-center" :class="r.alpa > 1 ? 'text-[#dc2626] font-bold' : 'text-[#475569]'" x-text="r.alpa + '%'"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- SUCCESS TOAST --}}
        <div x-show="savedToast" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-4 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-4 opacity-0" class="fixed bottom-6 right-6 z-[200] flex items-center gap-3 rounded-xl bg-[#0f172a] px-5 py-3.5 text-white shadow-2xl" style="display: none;">
            <svg class="h-5 w-5 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="text-[13px] font-bold">Data Kehadiran Berhasil Disimpan!</span>
        </div>

        {{-- MODAL SIMPAN --}}
        <div x-show="saveModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display: none;" x-transition @click.self="saveModalOpen = false">
            <div class="flex w-[90%] max-w-sm flex-col rounded-2xl bg-white shadow-2xl">
                <div class="p-6 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#eff6ff] text-[#1d4ed8] mb-4 ring-4 ring-[#dbeafe]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-[18px] font-black text-[#0f172a]">Simpan Kehadiran?</h3>
                    <p class="mt-2 text-[13px] leading-[1.8] text-[#64748b]">Data kehadiran untuk tanggal <strong class="text-[#0f172a]" x-text="formattedDate"></strong> akan disimpan.</p>
                </div>
                <div class="flex gap-3 bg-[#f8fafc] px-6 py-4 rounded-b-2xl border-t border-[#e2e8f0]">
                    <button @click="saveModalOpen = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Batal</button>
                    <button @click="doSave()" class="flex-1 rounded-lg bg-[#1d4ed8] px-4 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#2563eb]">Ya, Simpan</button>
                </div>
            </div>
        </div>

    </div>
</x-walikelas-shell>
