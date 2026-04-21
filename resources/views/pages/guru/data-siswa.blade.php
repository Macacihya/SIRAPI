<x-guru-shell
    :user="auth()->user()"
    active="data-siswa"
    title="Data Siswa"
    subtitle="Panel Guru Mata Pelajaran"
>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
    </style>

    <div class="space-y-8" x-data="{
        // ── Search & Filter ──
        searchQuery: '',
        filterKelas: 'VI-A',
        filterJK: '',
        filterOpen: false,

        // ── Pagination ──
        currentPage: 1,
        perPage: 10,

        siswa: [
            { no: '01', nis: '12001 / 005432101', init: 'AA', nama: 'ACHMAD ALBAR', jk: 'Laki-laki', kelas: 'VI-A' },
            { no: '02', nis: '12002 / 005432102', init: 'BM', nama: 'BELLA MONICA', jk: 'Perempuan', kelas: 'VI-A' },
            { no: '03', nis: '12003 / 005432103', init: 'DP', nama: 'DANDI PRATAMA', jk: 'Laki-laki', kelas: 'VI-A' },
            { no: '04', nis: '12004 / 005432104', init: 'EK', nama: 'ENDAH KARTIKA', jk: 'Perempuan', kelas: 'VI-A' },
            { no: '05', nis: '12005 / 005432105', init: 'FA', nama: 'FARHAN AZIS', jk: 'Laki-laki', kelas: 'VI-A' },
            { no: '06', nis: '12006 / 005432106', init: 'GA', nama: 'GITA ANANDA', jk: 'Perempuan', kelas: 'VI-A' },
            { no: '07', nis: '12007 / 005432107', init: 'HY', nama: 'HENDRA YULIAN', jk: 'Laki-laki', kelas: 'VI-A' },
            { no: '08', nis: '12008 / 005432108', init: 'IS', nama: 'INTAN SARI', jk: 'Perempuan', kelas: 'VI-A' },
            { no: '09', nis: '11009 / 005432109', init: 'BW', nama: 'BAMBANG WIDJAJANTO', jk: 'Laki-laki', kelas: 'VI-B' },
            { no: '10', nis: '11010 / 005432110', init: 'CT', nama: 'CINTA TANIA', jk: 'Perempuan', kelas: 'VI-B' },
            { no: '11', nis: '01011 / 005432111', init: 'AB', nama: 'ANDI BUDIMAN', jk: 'Laki-laki', kelas: 'I-A' },
            { no: '12', nis: '01012 / 005432112', init: 'SA', nama: 'SITI AMINAH', jk: 'Perempuan', kelas: 'I-A' }
        ],

        // ── Filtering & Pagination ──
        get filteredSiswa() {
            let result = this.siswa.map((s, i) => ({...s, _idx: i}));
            
            // Filter by Kelas
            if (this.filterKelas) {
                result = result.filter(s => s.kelas === this.filterKelas);
            }
            
            // Filter by Search Query
            if (this.searchQuery) {
                const q = this.searchQuery.toLowerCase();
                result = result.filter(s => s.nama.toLowerCase().includes(q) || s.nis.toLowerCase().includes(q));
            }
            
            // Filter by Jenis Kelamin
            if (this.filterJK) result = result.filter(s => s.jk === this.filterJK);
            
            // Recalculate numbering after filtering
            result = result.map((s, index) => ({...s, displayNo: (index + 1).toString().padStart(2, '0')}));
            
            return result;
        },

        get totalPages() {
            return Math.ceil(this.filteredSiswa.length / this.perPage) || 1;
        },

        get paginatedSiswa() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredSiswa.slice(start, start + this.perPage);
        },

        get pageNumbers() {
            const pages = [];
            for (let i = 1; i <= this.totalPages; i++) pages.push(i);
            return pages;
        },

        get hasActiveFilters() {
            return this.filterJK !== '';
        },

        goToPage(p) {
            if (p >= 1 && p <= this.totalPages) this.currentPage = p;
        },

        resetFilters() {
            this.searchQuery = '';
            this.filterJK = '';
            this.currentPage = 1;
            this.filterOpen = false;
        },

        exportCSV() {
            let csv = 'No,NIS / NISN,Nama Siswa,Jenis Kelamin,Kelas\n';
            this.filteredSiswa.forEach(s => {
                csv += s.displayNo + ',' + s.nis + ',' + s.nama + ',' + s.jk + ',' + s.kelas + '\n';
            });
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'daftar-siswa-' + (this.filterKelas || 'semua') + '.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }
    }">
        {{-- Header Section --}}
        <div>
            <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a] sm:text-[36px]">Database Siswa</h1>
            <p class="mt-3 max-w-[600px] text-[14px] leading-[1.8] text-[#475569] sm:text-[16px]">
                Melihat daftar lengkap seluruh siswa yang diajar berdasarkan pembagian kelas. Silakan gunakan filter di bawah untuk berpindah kelas.
            </p>
        </div>

        {{-- Info Cards --}}
        <div class="grid gap-4 sm:grid-cols-3">
            {{-- Filter Kelas --}}
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Kelas Aktif</p>
                <div class="mt-4">
                    <select x-model="filterKelas" @change="currentPage = 1" class="h-12 w-full rounded-lg border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[15px] font-bold text-[#0f172a] outline-none transition focus:border-[#3b82f6] focus:bg-white focus:ring-2 focus:ring-[#3b82f6]/20">
                        <option value="">Semua Kelas</option>
                        <option value="I-A">Kelas I-A</option>
                        <option value="VI-A">Kelas VI-A</option>
                        <option value="VI-B">Kelas VI-B</option>
                    </select>
                </div>
            </div>

            {{-- Total Siswa Kelas Ini --}}
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0] flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Total Siswa</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#1d4ed8]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
                <div class="mt-3 flex items-end gap-2">
                    <span class="text-[42px] font-black leading-none tracking-[-0.06em] text-[#0f172a]" x-text="filteredSiswa.length"></span>
                    <span class="pb-1 text-[13px] font-semibold text-[#64748b]">Berdasarkan Filter</span>
                </div>
            </div>

            {{-- Rekap L/P --}}
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0] flex flex-col justify-between">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Komposisi (L/P)</p>
                <div class="mt-4 flex gap-4">
                    <div class="flex-1 rounded-lg bg-[#eff6ff] p-3 ring-1 ring-[#bfdbfe]">
                        <div class="text-[11px] font-bold text-[#1d4ed8]">Laki-laki</div>
                        <div class="mt-1 text-[24px] font-black text-[#1e40af]" x-text="filteredSiswa.filter(s => s.jk === 'Laki-laki').length"></div>
                    </div>
                    <div class="flex-1 rounded-lg bg-[#fdf4ff] p-3 ring-1 ring-[#fbcfe8]">
                        <div class="text-[11px] font-bold text-[#be185d]">Perempuan</div>
                        <div class="mt-1 text-[24px] font-black text-[#9d174d]" x-text="filteredSiswa.filter(s => s.jk === 'Perempuan').length"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Siswa Section --}}
        <div class="space-y-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-[22px] font-black tracking-[-0.03em] text-[#0f172a] sm:text-[28px]">Daftar Data Siswa</h2>
                    <p class="mt-1 text-[13px] text-[#64748b]" x-text="'Siswa yg berada di ' + (filterKelas ? 'Kelas ' + filterKelas : 'Seluruh Kelas')"></p>
                </div>
            </div>

            {{-- Search + Actions --}}
            <div class="no-print flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"/><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"/></svg>
                    <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Cari berdasarkan Nama atau NIS/NISN..." class="h-11 w-full rounded-lg border border-[#e2e8f0] bg-white pl-11 pr-4 text-[13px] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                </div>

                {{-- Filter Dropdown --}}
                <div class="relative" @click.outside="filterOpen = false">
                    <button @click="filterOpen = !filterOpen" class="flex h-11 items-center gap-2 rounded-lg border border-[#e2e8f0] bg-white px-5 text-[11px] font-extrabold uppercase tracking-[0.12em] text-[#475569] transition hover:bg-[#f1f5f9]" :class="hasActiveFilters ? 'border-[#3b82f6] text-[#1d4ed8]' : ''">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 4h18M7 8h10M10 12h4" stroke-width="2" stroke-linecap="round"/></svg>
                        Filter Detail
                        <span x-show="hasActiveFilters" class="flex h-5 w-5 items-center justify-center rounded-full bg-[#1d4ed8] text-[9px] font-black text-white">!</span>
                    </button>
                    <div x-show="filterOpen" x-transition class="absolute right-0 top-full z-50 mt-2 w-72 rounded-xl bg-white p-5 shadow-xl ring-1 ring-[#e2e8f0]" style="display: none;">
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Filter Data Siswa</p>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="mb-1.5 block text-[11px] font-bold text-[#475569]">Jenis Kelamin</label>
                                <select x-model="filterJK" @change="currentPage = 1" class="h-10 w-full rounded-lg border border-[#e2e8f0] bg-white px-3 text-[13px] font-semibold text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                                    <option value="">Semua</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <button @click="resetFilters()" class="flex h-9 w-full items-center justify-center rounded-lg border border-[#e2e8f0] text-[11px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">
                                Reset Filter Detail
                            </button>
                        </div>
                    </div>
                </div>

                <button @click="window.print()" class="flex h-11 items-center gap-2 rounded-lg border border-[#e2e8f0] bg-white px-5 text-[11px] font-extrabold uppercase tracking-[0.12em] text-[#475569] transition hover:bg-[#f1f5f9]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Cetak
                </button>
                <button @click="exportCSV()" class="flex h-11 items-center gap-2 rounded-lg bg-[#0f172a] px-5 text-[11px] font-extrabold uppercase tracking-[0.12em] text-white transition hover:bg-[#1e293b]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Export
                </button>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto rounded-xl bg-white ring-1 ring-[#e2e8f0]">
                <table class="w-full text-left text-[13px]">
                    <thead>
                        <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">No</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Kelas</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">NIS / NISN</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Nama Siswa</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Jenis Kelamin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="s in paginatedSiswa" :key="s._idx">
                            <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                                <td class="px-5 py-4 text-[#64748b]" x-text="s.displayNo"></td>
                                <td class="px-5 py-4">
                                    <span class="inline-block rounded-md bg-[#f1f5f9] px-2 py-1 text-[11px] font-bold text-[#475569]" x-text="s.kelas"></span>
                                </td>
                                <td class="px-5 py-4 font-bold text-[#0f172a]" x-text="s.nis"></td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-[#1e40af] text-[10px] font-bold text-white" x-text="s.init"></div>
                                        <span class="font-bold text-[#0f172a]" x-text="s.nama"></span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-[#475569]" x-text="s.jk"></td>
                            </tr>
                        </template>
                        {{-- Empty State --}}
                        <template x-if="paginatedSiswa.length === 0">
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center">
                                    <svg class="mx-auto h-10 w-10 text-[#cbd5e1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round"/></svg>
                                    <p class="mt-3 text-[14px] font-bold text-[#64748b]">Tidak ada data ditemukan</p>
                                    <p class="mt-1 text-[12px] text-[#94a3b8]">Coba ubah kata kunci pencarian atau ganti kelas aktif.</p>
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
                    Menampilkan <span x-text="paginatedSiswa.length"></span> dari <span x-text="filteredSiswa.length"></span> siswa
                </p>
                <div class="flex items-center gap-1" x-show="totalPages > 1">
                    <button @click="goToPage(currentPage - 1)" :disabled="currentPage <= 1" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] transition hover:bg-[#f1f5f9] disabled:opacity-30 disabled:cursor-not-allowed">‹</button>
                    <template x-for="p in pageNumbers" :key="p">
                        <button @click="goToPage(p)" :class="currentPage === p ? 'bg-[#1d4ed8] text-white' : 'text-[#64748b] hover:bg-[#f1f5f9]'" class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-bold transition" x-text="p"></button>
                    </template>
                    <button @click="goToPage(currentPage + 1)" :disabled="currentPage >= totalPages" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#64748b] transition hover:bg-[#f1f5f9] disabled:opacity-30 disabled:cursor-not-allowed">›</button>
                </div>
            </div>
        </div>
    </div>
</x-guru-shell>
