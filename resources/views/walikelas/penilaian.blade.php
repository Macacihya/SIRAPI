<x-walikelas-shell
    :user="auth()->user()"
    active="penilaian"
    title="Penilaian Kelas"
    subtitle="Selamat datang di Panel Wali Kelas"
>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
    </style>

    <div class="space-y-6" x-data="{
        searchQuery: '',
        selectedMapel: '',
        filterStatus: '',
        filterOpen: false,
        currentPage: 1,
        perPage: 8,

        grades: [
            { no: '01', nis: '12001 / 005432101', init: 'AA', nama: 'Achmad Albar', agm: 88, pan: 85, bi: 90, mtk: 85, ipas: 78, pj: 92, sb: 85, avg: 85.7, status: 'TUNTAS' },
            { no: '02', nis: '12002 / 005432102', init: 'BM', nama: 'Bella Monica', agm: 90, pan: 92, bi: 88, mtk: 92, ipas: 88, pj: 90, sb: 87, avg: 90.0, status: 'TUNTAS' },
            { no: '03', nis: '12003 / 005432103', init: 'DP', nama: 'Dandi Pratama', agm: 85, pan: 80, bi: 70, mtk: 68, ipas: 68, pj: 85, sb: 75, avg: 74.5, status: 'BELUM' },
            { no: '04', nis: '12004 / 005432104', init: 'EK', nama: 'Endah Kartika', agm: 88, pan: 85, bi: 82, mtk: 65, ipas: 80, pj: 85, sb: 80, avg: 80.5, status: 'TUNTAS' },
            { no: '05', nis: '12005 / 005432105', init: 'FA', nama: 'Farhan Azis', agm: 82, pan: 85, bi: 85, mtk: 78, ipas: 77, pj: 80, sb: 80, avg: 81.0, status: 'TUNTAS' },
            { no: '06', nis: '12006 / 005432106', init: 'GA', nama: 'Gita Ananda', agm: 92, pan: 95, bi: 93, mtk: 90, ipas: 88, pj: 95, sb: 90, avg: 91.7, status: 'TUNTAS' },
            { no: '07', nis: '12007 / 005432107', init: 'HY', nama: 'Hendra Yulian', agm: 80, pan: 75, bi: 68, mtk: 70, ipas: 72, pj: 80, sb: 75, avg: 74.2, status: 'BELUM' },
            { no: '08', nis: '12008 / 005432108', init: 'IS', nama: 'Intan Sari', agm: 86, pan: 88, bi: 86, mtk: 82, ipas: 84, pj: 88, sb: 85, avg: 85.3, status: 'TUNTAS' },
            { no: '09', nis: '12009 / 005432109', init: 'JW', nama: 'Joko Wibowo', agm: 85, pan: 82, bi: 80, mtk: 76, ipas: 78, pj: 85, sb: 82, avg: 81.1, status: 'TUNTAS' },
            { no: '10', nis: '12010 / 005432110', init: 'KR', nama: 'Kirana Rahma', agm: 95, pan: 94, bi: 92, mtk: 95, ipas: 90, pj: 96, sb: 93, avg: 93.5, status: 'TUNTAS' },
            { no: '11', nis: '12011 / 005432111', init: 'LP', nama: 'Lukman Putra', agm: 75, pan: 70, bi: 65, mtk: 60, ipas: 58, pj: 75, sb: 70, avg: 67.2, status: 'BELUM' },
            { no: '12', nis: '12012 / 005432112', init: 'MN', nama: 'Maya Nurhaliza', agm: 88, pan: 90, bi: 84, mtk: 88, ipas: 86, pj: 88, sb: 85, avg: 86.6, status: 'TUNTAS' },
            { no: '13', nis: '12013 / 005432113', init: 'NR', nama: 'Nanda Rizky', agm: 85, pan: 82, bi: 76, mtk: 79, ipas: 81, pj: 88, sb: 84, avg: 81.6, status: 'TUNTAS' },
            { no: '14', nis: '12014 / 005432114', init: 'OP', nama: 'Oscar Permana', agm: 78, pan: 75, bi: 74, mtk: 72, ipas: 68, pj: 78, sb: 75, avg: 73.8, status: 'BELUM' },
            { no: '15', nis: '12015 / 005432115', init: 'PS', nama: 'Putri Setiawan', agm: 90, pan: 88, bi: 89, mtk: 86, ipas: 82, pj: 90, sb: 88, avg: 87.1, status: 'TUNTAS' },
            { no: '16', nis: '12016 / 005432116', init: 'QH', nama: 'Qiyamul Haq', agm: 92, pan: 90, bi: 87, mtk: 91, ipas: 93, pj: 92, sb: 90, avg: 90.5, status: 'TUNTAS' },
            { no: '17', nis: '12017 / 005432117', init: 'RA', nama: 'Rina Agustina', agm: 82, pan: 80, bi: 72, mtk: 68, ipas: 65, pj: 82, sb: 78, avg: 74.4, status: 'BELUM' },
            { no: '18', nis: '12018 / 005432118', init: 'SB', nama: 'Surya Bagaskara', agm: 88, pan: 86, bi: 81, mtk: 84, ipas: 87, pj: 88, sb: 85, avg: 85.0, status: 'TUNTAS' },
        ],

        get filteredGrades() {
            let result = [...this.grades];
            if (this.searchQuery) {
                const q = this.searchQuery.toLowerCase();
                result = result.filter(g => g.nama.toLowerCase().includes(q) || g.nis.includes(q));
            }
            if (this.filterStatus) result = result.filter(g => g.status === this.filterStatus);
            if (this.selectedMapel) {
                result.sort((a, b) => a[this.selectedMapel] - b[this.selectedMapel]);
            }
            return result;
        },

        get totalPages() {
            return Math.ceil(this.filteredGrades.length / this.perPage) || 1;
        },

        get paginatedGrades() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredGrades.slice(start, start + this.perPage);
        },

        get pageNumbers() {
            const pages = [];
            for (let i = 1; i <= this.totalPages; i++) pages.push(i);
            return pages;
        },

        get tuntasCount() {
            return this.grades.filter(g => g.status === 'TUNTAS').length;
        },

        get tuntasPercent() {
            return Math.round(this.tuntasCount / this.grades.length * 100);
        },

        get classAverage() {
            const sum = this.grades.reduce((acc, g) => acc + g.avg, 0);
            return (sum / this.grades.length).toFixed(1);
        },

        goToPage(p) {
            if (p >= 1 && p <= this.totalPages) this.currentPage = p;
        },

        resetFilters() {
            this.searchQuery = '';
            this.selectedMapel = '';
            this.filterStatus = '';
            this.currentPage = 1;
            this.filterOpen = false;
        }
    }">
        {{-- Title + Actions --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a] sm:text-[36px]">Penilaian Kelas</h1>
                <p class="mt-1 text-[14px] text-[#475569]">Rekapitulasi capaian akademik siswa Kelas VI-A untuk Semester Ganjil 2026/2027.</p>
            </div>
            <div class="flex items-center gap-2 no-print">
                <button @click="window.print()" class="flex items-center gap-2 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[11px] font-extrabold uppercase tracking-wider text-[#475569] transition hover:bg-[#f1f5f9]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Export PDF
                </button>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Total Siswa</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#1d4ed8]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
                <p class="mt-2"><span class="text-[36px] font-black leading-none text-[#0f172a]" x-text="grades.length">18</span> <span class="text-[13px] text-[#64748b]">Peserta</span></p>
            </div>
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Rata-rata Kelas</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#3b82f6]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m3 17 6-6 4 4 7-8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    </div>
                </div>
                <p class="mt-2"><span class="text-[36px] font-black leading-none text-[#0f172a]" x-text="classAverage">80.6</span> <span class="text-[13px] text-[#64748b]">Poin</span></p>
            </div>
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#3b82f6]/30">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Ketuntasan</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#f0fdf4] text-[#16a34a]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
                <p class="mt-2"><span class="text-[36px] font-black leading-none text-[#0f172a]" x-text="tuntasPercent + '%'">72%</span> <span class="text-[13px] text-[#64748b]">Tuntas</span></p>
            </div>
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Update Terakhir</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#60a5fa]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2" stroke-width="2"/><path d="M16 3v4M8 3v4M3 10h18" stroke-linecap="round" stroke-width="2"/></svg>
                    </div>
                </div>
                <p class="mt-2 text-[24px] font-black leading-tight text-[#0f172a]">12 Okt 2023</p>
            </div>
        </div>

        {{-- Search + Filter --}}
        <div class="no-print flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"/><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"/></svg>
                <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Cari nama atau NIS/NISN siswa..." class="h-11 w-full rounded-lg border border-[#e2e8f0] bg-white pl-11 pr-4 text-[13px] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
            </div>
            <div class="flex items-center gap-2">
                <select x-model="selectedMapel" @change="currentPage = 1" class="h-11 rounded-lg border border-[#e2e8f0] bg-white px-4 pr-8 text-[11px] font-bold uppercase tracking-wider text-[#475569] outline-none">
                    <option value="">Semua Mapel</option>
                    <option value="agm">Pendidikan Agama</option>
                    <option value="pan">Pancasila</option>
                    <option value="bi">B. Indonesia</option>
                    <option value="mtk">Matematika</option>
                    <option value="ipas">IPAS</option>
                    <option value="pj">PJOK</option>
                    <option value="sb">Seni & Budaya</option>
                </select>
                <div class="relative" @click.outside="filterOpen = false">
                    <button @click="filterOpen = !filterOpen" class="flex h-11 w-11 items-center justify-center rounded-lg border border-[#e2e8f0] text-[#64748b] transition hover:bg-[#f1f5f9]" :class="filterStatus ? 'border-[#3b82f6] text-[#1d4ed8]' : ''">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 4h18M7 8h10M10 12h4" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <div x-show="filterOpen" x-transition class="absolute right-0 top-full z-50 mt-2 w-56 rounded-xl bg-white p-4 shadow-xl ring-1 ring-[#e2e8f0]" style="display: none;">
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Filter Status</p>
                        <div class="mt-3 space-y-2">
                            <button @click="filterStatus = ''; currentPage = 1; filterOpen = false" :class="filterStatus === '' ? 'bg-[#eff6ff] text-[#1d4ed8] font-bold' : 'text-[#475569] hover:bg-[#f1f5f9]'" class="w-full rounded-lg px-3 py-2 text-left text-[12px] transition">Semua Status</button>
                            <button @click="filterStatus = 'TUNTAS'; currentPage = 1; filterOpen = false" :class="filterStatus === 'TUNTAS' ? 'bg-[#f0fdf4] text-[#16a34a] font-bold' : 'text-[#475569] hover:bg-[#f1f5f9]'" class="w-full rounded-lg px-3 py-2 text-left text-[12px] transition">✓ Tuntas</button>
                            <button @click="filterStatus = 'BELUM'; currentPage = 1; filterOpen = false" :class="filterStatus === 'BELUM' ? 'bg-[#fef2f2] text-[#dc2626] font-bold' : 'text-[#475569] hover:bg-[#f1f5f9]'" class="w-full rounded-lg px-3 py-2 text-left text-[12px] transition">✗ Belum Tuntas</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grade Table --}}
        <div class="overflow-x-auto rounded-xl bg-white ring-1 ring-[#e2e8f0]">
            <table class="w-full text-[13px] whitespace-nowrap">
                <thead>
                    <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                        <th class="sticky left-0 z-10 bg-[#f8fafc] border-r border-[#e2e8f0] px-5 py-4 text-left text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b] min-w-[200px]">Nama Siswa</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] min-w-[140px]" :class="selectedMapel === 'agm' ? 'text-[#1d4ed8]' : 'text-[#64748b]'">Pendidikan Agama</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] min-w-[120px]" :class="selectedMapel === 'pan' ? 'text-[#1d4ed8]' : 'text-[#64748b]'">Pancasila</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] min-w-[150px]" :class="selectedMapel === 'bi' ? 'text-[#1d4ed8]' : 'text-[#64748b]'">Bahasa Indonesia</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] min-w-[120px]" :class="selectedMapel === 'mtk' ? 'text-[#1d4ed8]' : 'text-[#64748b]'">Matematika</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] min-w-[100px]" :class="selectedMapel === 'ipas' ? 'text-[#1d4ed8]' : 'text-[#64748b]'">IPAS</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] min-w-[100px]" :class="selectedMapel === 'pj' ? 'text-[#1d4ed8]' : 'text-[#64748b]'">PJOK</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] min-w-[140px]" :class="selectedMapel === 'sb' ? 'text-[#1d4ed8]' : 'text-[#64748b]'">Seni Budaya</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#0f172a] border-l border-[#e2e8f0] min-w-[100px]">Rata-rata</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b] min-w-[120px]">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="g in paginatedGrades" :key="g.nama">
                        <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                            <td class="sticky left-0 z-10 bg-white border-r border-[#f1f5f9] px-5 py-4 group-hover:bg-[#f8fafc]">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center flex-shrink-0 rounded-md bg-[#1e40af] text-[10px] font-bold text-white" x-text="g.init"></div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-[#0f172a]" x-text="g.nama"></span>
                                        <span class="text-[11px] font-semibold text-[#64748b]" x-text="g.nis"></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center" :class="g.agm < 75 ? 'text-[#dc2626] font-bold' : 'text-[#475569]'" x-text="g.agm"></td>
                            <td class="px-4 py-4 text-center" :class="g.pan < 75 ? 'text-[#dc2626] font-bold' : 'text-[#475569]'" x-text="g.pan"></td>
                            <td class="px-4 py-4 text-center" :class="g.bi < 75 ? 'text-[#dc2626] font-bold' : 'text-[#475569]'" x-text="g.bi"></td>
                            <td class="px-4 py-4 text-center" :class="g.mtk < 75 ? 'text-[#dc2626] font-bold' : 'text-[#475569]'" x-text="g.mtk"></td>
                            <td class="px-4 py-4 text-center" :class="g.ipas < 75 ? 'text-[#dc2626] font-bold' : 'text-[#475569]'" x-text="g.ipas"></td>
                            <td class="px-4 py-4 text-center" :class="g.pj < 75 ? 'text-[#dc2626] font-bold' : 'text-[#475569]'" x-text="g.pj"></td>
                            <td class="px-4 py-4 text-center" :class="g.sb < 75 ? 'text-[#dc2626] font-bold' : 'text-[#475569]'" x-text="g.sb"></td>
                            <td class="px-4 py-4 text-center font-black border-l border-[#f1f5f9]" :class="g.avg < 75 ? 'text-[#dc2626]' : 'text-[#0f172a]'" x-text="g.avg.toFixed(1)"></td>
                            <td class="px-5 py-4 text-center">
                                <span x-show="g.status === 'TUNTAS'" class="rounded px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider bg-[#f0fdf4] text-[#16a34a]">Tuntas</span>
                                <span x-show="g.status === 'BELUM'" class="rounded px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider bg-[#fef2f2] text-[#dc2626]">Belum</span>
                            </td>
                        </tr>
                    </template>
                    <template x-if="paginatedGrades.length === 0">
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <svg class="mx-auto h-10 w-10 text-[#cbd5e1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round"/></svg>
                                <p class="mt-3 text-[14px] font-bold text-[#64748b]">Tidak ada data ditemukan</p>
                                <button @click="resetFilters()" class="mt-4 rounded-lg border border-[#e2e8f0] px-4 py-2 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Reset Filter</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="no-print flex flex-col items-center justify-between gap-3 sm:flex-row">
            <p class="text-[11px] font-bold uppercase tracking-[0.1em] text-[#64748b]">
                Menampilkan <span x-text="paginatedGrades.length"></span> dari <span x-text="filteredGrades.length"></span> siswa
            </p>
            <div class="flex items-center gap-1" x-show="totalPages > 1">
                <button @click="goToPage(currentPage - 1)" :disabled="currentPage <= 1" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] transition hover:bg-[#f1f5f9] disabled:opacity-30 disabled:cursor-not-allowed">‹</button>
                <template x-for="p in pageNumbers" :key="p">
                    <button @click="goToPage(p)" :class="currentPage === p ? 'bg-[#1d4ed8] text-white' : 'text-[#64748b] hover:bg-[#f1f5f9]'" class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-bold transition" x-text="p"></button>
                </template>
                <button @click="goToPage(currentPage + 1)" :disabled="currentPage >= totalPages" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#64748b] transition hover:bg-[#f1f5f9] disabled:opacity-30 disabled:cursor-not-allowed">›</button>
            </div>
        </div>

        {{-- Bottom: Catatan + Pengesahan --}}
        <div class="grid gap-6 lg:grid-cols-[1fr_300px]">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Catatan Wali Kelas</p>
                <div class="mt-3 rounded-xl bg-[#f1f5f9] p-5 ring-1 ring-[#e2e8f0]">
                    <p class="text-[14px] italic leading-[1.8] text-[#334155]">
                        "Sebagian besar siswa menunjukkan progress positif pada mata pelajaran sains. Perlu perhatian khusus bagi 3 siswa yang belum mencapai KKM pada mata pelajaran Fisika sebelum penutupan nilai semester."
                    </p>
                </div>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Pengesahan</p>
                <div class="mt-3 flex flex-col items-center rounded-xl bg-white p-6 ring-1 ring-[#e2e8f0]">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-[#f1f5f9] text-[#94a3b8]">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <p class="mt-3 text-[14px] font-bold text-[#0f172a]">Budi Santoso, S.Pd</p>
                    <p class="mt-0.5 text-[11px] text-[#64748b]">NIP. 198503122010121004</p>
                </div>
            </div>
        </div>
    </div>
</x-walikelas-shell>
