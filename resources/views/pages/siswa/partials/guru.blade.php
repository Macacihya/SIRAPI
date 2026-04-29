{{-- ============================================================
     Partial: Siswa — Guru View (Read-only)
     Deskripsi: Tampilan data siswa untuk guru (hanya baca).
     Di-extract dari siswa/index.blade.php bagian guru.
     ============================================================ --}}

<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white !important; }
    }
</style>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('siswaGuru', () => ({
        searchQuery: '',
        filterKelas: '',
        filterJK: '',
        filterOpen: false,
        siswa: @json($data->items()),
        init() {
            // Mapping data SMA ke SD untuk demo (Mapping otomatis)
            this.siswa.forEach(s => {
                if (s.kelas && (s.kelas.includes('X') || s.kelas.includes('XI') || s.kelas.includes('XII'))) {
                    let k = s.kelas.toUpperCase();
                    let level = k.includes('XII') ? '6' : (k.includes('XI') ? '4' : '1');
                    let section = k.includes('IPA') ? 'A' : 'B';
                    s.kelas = `Kelas ${level}-${section}`;
                }
            });
        },
        get filteredSiswa() {
            let result = this.siswa.map((s, i) => ({...s, _idx: i}));
            if (this.filterKelas) result = result.filter(s => s.kelas === this.filterKelas);
            if (this.searchQuery) {
                const q = this.searchQuery.toLowerCase();
                result = result.filter(s =>
                    (s.nama && s.nama.toLowerCase().includes(q)) ||
                    (s.nis && s.nis.toString().toLowerCase().includes(q)) ||
                    (s.nisn && s.nisn.toString().toLowerCase().includes(q))
                );
            }
            if (this.filterJK) result = result.filter(s => s.jenis_kelamin === this.filterJK);
            result = result.map((s, index) => ({...s, displayNo: (index + 1).toString().padStart(2, '0')}));
            return result;
        },
        get hasActiveFilters() { return this.filterJK !== ''; },
        resetFilters() { this.searchQuery = ''; this.filterJK = ''; this.filterOpen = false; },
        exportCSV() {
            let csv = 'No,NIS / NISN,Nama Siswa,Jenis Kelamin,Kelas\n';
            this.filteredSiswa.forEach(s => { csv += s.displayNo + ',' + s.nis + ',' + s.nama + ',' + s.jenis_kelamin + ',' + s.kelas + '\n'; });
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a'); a.href = url; a.download = 'daftar-siswa-' + (this.filterKelas || 'semua') + '.csv';
            document.body.appendChild(a); a.click(); document.body.removeChild(a); URL.revokeObjectURL(url);
        }
    }));
});
</script>

<div class="space-y-8" x-data="siswaGuru">
    {{-- Header Section --}}
    <div>
        <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a] sm:text-[36px]">Database Siswa</h1>
        <p class="mt-3 max-w-[600px] text-[14px] leading-[1.8] text-[#475569] sm:text-[16px]">
            Melihat daftar lengkap seluruh siswa yang diajar berdasarkan pembagian kelas. Silakan gunakan filter di bawah untuk berpindah kelas.
        </p>
    </div>

    {{-- Info Cards --}}
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Kelas Aktif</p>
            <div class="mt-4">
                <select x-model="filterKelas" class="h-12 w-full rounded-lg border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[15px] font-bold text-[#0f172a] outline-none transition focus:border-[#3b82f6] focus:bg-white focus:ring-2 focus:ring-[#3b82f6]/20">
                    <option value="">Semua Kelas</option>
                    <option value="Kelas 1-A">Kelas 1-A</option>
                    <option value="Kelas 1-B">Kelas 1-B</option>
                    <option value="Kelas 2-A">Kelas 2-A</option>
                    <option value="Kelas 3-A">Kelas 3-A</option>
                    <option value="Kelas 4-A">Kelas 4-A</option>
                    <option value="Kelas 5-A">Kelas 5-A</option>
                    <option value="Kelas 6-A">Kelas 6-A</option>
                </select>
            </div>
        </div>
        <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0] flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Total Siswa</p>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#1d4ed8]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-end gap-2">
                <span class="text-[42px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">{{ $data->total() }}</span>
                <span class="pb-1 text-[13px] font-semibold text-[#64748b]">Seluruh Data</span>
            </div>
        </div>
        <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0] flex flex-col justify-between">
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Komposisi (L/P)</p>
            <div class="mt-4 flex gap-4">
                <div class="flex-1 rounded-lg bg-[#eff6ff] p-3 ring-1 ring-[#bfdbfe]">
                    <div class="text-[11px] font-bold text-[#1d4ed8]">Laki-laki</div>
                    <div class="mt-1 text-[24px] font-black text-[#1e40af]" x-text="filteredSiswa.filter(s => s.jenis_kelamin === 'Laki-laki').length"></div>
                </div>
                <div class="flex-1 rounded-lg bg-[#fdf4ff] p-3 ring-1 ring-[#fbcfe8]">
                    <div class="text-[11px] font-bold text-[#be185d]">Perempuan</div>
                    <div class="mt-1 text-[24px] font-black text-[#9d174d]" x-text="filteredSiswa.filter(s => s.jenis_kelamin === 'Perempuan').length"></div>
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
                <input type="text" x-model="searchQuery" placeholder="Cari berdasarkan Nama atau NIS/NISN..." class="h-11 w-full rounded-lg border border-[#e2e8f0] bg-white pl-11 pr-4 text-[13px] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
            </div>
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
                            <select x-model="filterJK" class="h-10 w-full rounded-lg border border-[#e2e8f0] bg-white px-3 text-[13px] font-semibold text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                                <option value="">Semua</option><option value="Laki-laki">Laki-laki</option><option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <button @click="resetFilters()" class="flex h-9 w-full items-center justify-center rounded-lg border border-[#e2e8f0] text-[11px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Reset Filter Detail</button>
                    </div>
                </div>
            </div>
            <button @click="window.print()" class="flex h-11 items-center gap-2 rounded-lg border border-[#e2e8f0] bg-white px-5 text-[11px] font-extrabold uppercase tracking-[0.12em] text-[#475569] transition hover:bg-[#f1f5f9]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>Cetak
            </button>
            <button @click="exportCSV()" class="flex h-11 items-center gap-2 rounded-lg bg-[#0f172a] px-5 text-[11px] font-extrabold uppercase tracking-[0.12em] text-white transition hover:bg-[#1e293b]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>Export
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
                    <template x-for="s in filteredSiswa" :key="s._idx">
                        <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                            <td class="px-5 py-4 text-[#64748b]" x-text="s.displayNo"></td>
                            <td class="px-5 py-4"><span class="inline-block rounded-md bg-[#f1f5f9] px-2 py-1 text-[11px] font-bold text-[#475569]" x-text="s.kelas"></span></td>
                            <td class="px-5 py-4 font-bold text-[#0f172a]" x-text="s.nis"></td>
                            <td class="px-5 py-4"><div class="flex items-center gap-3"><div class="flex h-8 w-8 items-center justify-center rounded-md bg-[#1e40af] text-[10px] font-bold text-white uppercase" x-text="(s.nama || '?').charAt(0)"></div><span class="font-bold text-[#0f172a]" x-text="s.nama"></span></div></td>
                            <td class="px-5 py-4 text-[#475569]" x-text="s.jenis_kelamin"></td>
                        </tr>
                    </template>
                    <template x-if="filteredSiswa.length === 0">
                        <tr><td colspan="5" class="px-5 py-12 text-center">
                            <svg class="mx-auto h-10 w-10 text-[#cbd5e1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round"/></svg>
                            <p class="mt-3 text-[14px] font-bold text-[#64748b]">Tidak ada data ditemukan</p>
                            <p class="mt-1 text-[12px] text-[#94a3b8]">Coba ubah kata kunci pencarian atau ganti kelas aktif.</p>
                            <button @click="resetFilters()" class="mt-4 rounded-lg border border-[#e2e8f0] px-4 py-2 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Reset Filter</button>
                        </td></tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="border-t border-[#e2e8f0] px-6 py-4">
            {{ $data->links() }}
        </div>
    </div>
</div>
