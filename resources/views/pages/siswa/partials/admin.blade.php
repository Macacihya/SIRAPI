{{-- ============================================================
     Partial: Siswa — Admin View
     Deskripsi: Data siswa lengkap dengan CRUD (tambah, edit, hapus).
     Di-extract dari siswa/index.blade.php bagian admin.
     ============================================================ --}}

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('siswaAdmin', () => ({
            kelasFilter: 'Semua',
            statusFilter: 'Semua',
            showAdd: false,
            showImport: false,
            showDelete: false,
            deleteTarget: null,
            selectAll: false,
            showDeleteAll: false,
            daftarKelas: ['Kelas 1-A', 'Kelas 1-B', 'Kelas 2-A', 'Kelas 3-A', 'Kelas 4-A', 'Kelas 5-A', 'Kelas 6-A'],
            form: { nama: '', nisn: '', kelas: 'Kelas 1-A', gender: 'Laki-laki', status: 'AKTIF' },
            importFile: null,
            isImporting: false,
            importProgress: 0,
            searchQuery: '',
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

                if (this.siswa.length < 5) {
                    this.siswa.push(
                        { id: 1, nama: 'Aditya Pratama', nis: '12001', nisn: '0012345601', kelas: 'Kelas 1-A', jenis_kelamin: 'Laki-laki', status: 'AKTIF', selected: false },
                        { id: 2, nama: 'Bella Safira', nis: '12002', nisn: '0012345602', kelas: 'Kelas 1-A', jenis_kelamin: 'Perempuan', status: 'AKTIF', selected: false },
                        { id: 3, nama: 'Citra Kirana', nis: '12003', nisn: '0012345603', kelas: 'Kelas 2-A', jenis_kelamin: 'Perempuan', status: 'AKTIF', selected: false },
                        { id: 4, nama: 'Dimas Seto', nis: '12004', nisn: '0012345604', kelas: 'Kelas 3-A', jenis_kelamin: 'Laki-laki', status: 'AKTIF', selected: false }
                    );
                }
            },
            
            openedMenu: null,

            get filtered() {
                let r = this.siswa;
                if (this.kelasFilter !== 'Semua') r = r.filter(s => s.kelas && s.kelas.includes(this.kelasFilter));
                if (this.statusFilter !== 'Semua') r = r.filter(s => (s.status || 'AKTIF') === this.statusFilter);
                if (this.searchQuery) {
                    const q = this.searchQuery.toLowerCase();
                    r = r.filter(s =>
                        (s.nama && s.nama.toLowerCase().includes(q)) ||
                        (s.name && s.name.toLowerCase().includes(q)) ||
                        (s.nis && s.nis.toString().toLowerCase().includes(q)) ||
                        (s.nisn && s.nisn.toString().toLowerCase().includes(q))
                    );
                }
                return r;
            },
            get selectedCount() { return this.siswa.filter(s => s.selected).length; },
            toggleAll() { this.selectAll = !this.selectAll; this.filtered.forEach(s => s.selected = this.selectAll); },
            submitAdd() {
                this.siswa.unshift({ id: Date.now(), nama: this.form.nama, nis: this.form.nisn, kelas: this.form.kelas, jenis_kelamin: this.form.gender, status: this.form.status, selected: false });
                this.form = { nama:'', nisn:'', kelas:'X IPA 1', gender:'Laki-laki', status:'AKTIF' };
                this.showAdd = false;
                this.$dispatch('toast', { message: 'Siswa berhasil ditambahkan!', type: 'success' });
            },
            confirmDelete(s) { this.deleteTarget = s; this.showDelete = true; },
            doDelete() {
                this.siswa = this.siswa.filter(s => s.id !== this.deleteTarget.id);
                this.showDelete = false; this.deleteTarget = null; this.selectAll = false;
                this.$dispatch('toast', { message: 'Data siswa berhasil dihapus.', type: 'error' });
            },
            doDeleteAll() {
                this.siswa = this.siswa.filter(s => !s.selected);
                this.showDeleteAll = false; this.selectAll = false;
                this.$dispatch('toast', { message: 'Semua data siswa yang dipilih berhasil dihapus.', type: 'error' });
            },
            handleFileSelect(e) {
                if (e.target.files.length > 0) { this.importFile = e.target.files[0]; }
            },
            submitImport() {
                if (!this.importFile) return;
                this.isImporting = true;
                this.importProgress = 0;
                let interval = setInterval(() => {
                    this.importProgress += Math.floor(Math.random() * 20) + 10;
                    if (this.importProgress >= 100) {
                        this.importProgress = 100;
                        clearInterval(interval);
                        setTimeout(() => {
                            this.siswa.unshift({ 
                                id: Date.now(), 
                                nama: 'Siswa Baru (Import)', 
                                nis: '12999', 
                                kelas: 'X IPA 1', 
                                jenis_kelamin: 'Laki-laki', 
                                status: 'AKTIF', 
                                selected: false 
                            });
                            this.isImporting = false;
                            this.showImport = false;
                            this.importFile = null;
                            this.$dispatch('toast', { message: 'Data siswa berhasil di-import!', type: 'success' });
                        }, 500);
                    }
                }, 300);
            },
            downloadTemplate() {
                let csv = 'Nama,NIS,NISN,Kelas,Jenis_Kelamin\n';
                csv += 'Ahmad Albar,12001,0012345601,X IPA 1,Laki-laki\n';
                csv += 'Bella Monica,12002,0012345602,X IPA 2,Perempuan\n';
                const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'template_import_siswa.csv';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            },
        }));
    });
</script>

<div x-data="siswaAdmin" class="space-y-6">

    {{-- HEADING --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div><h1 class="text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Data Siswa</h1><p class="mt-2 max-w-[480px] text-[14px] leading-[1.8] text-[#475569]">Pengelolaan data induk siswa, verifikasi NISN, dan monitoring status.</p></div>
        <div class="flex flex-wrap items-center gap-2">
            <button @click="showImport = true" class="flex h-[42px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-5 text-[13px] font-bold text-[#475569] transition hover:bg-[#f8fafc]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4 4m0 0L8 8m4-4v12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                Import Data
            </button>
            <button @click="showAdd = true" class="flex h-[42px] items-center gap-2 rounded-[8px] bg-[#1d4ed8] px-5 text-[13px] font-bold text-white transition hover:bg-[#1e40af]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>
                Tambah Siswa
            </button>
        </div>
    </div>

    {{-- STAT --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5"><p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Total Siswa</p><p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">{{ $data->total() }}</p></div>
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5"><p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Siswa Aktif</p><p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">{{ $data->total() }}</p></div>
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5"><p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Status Cuti</p><p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">0</p></div>
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5"><p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Dipilih</p><p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#1d4ed8]" x-text="selectedCount"></p></div>
    </div>

    {{-- FILTERS --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"/><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"/></svg>
                <input type="text" x-model="searchQuery" placeholder="Cari siswa..." class="h-[38px] w-64 rounded-[8px] border border-[#e2e8f0] bg-white pl-9 pr-4 text-[13px] outline-none focus:border-[#3b82f6]">
            </div>
            <select x-model="kelasFilter" class="h-[38px] appearance-none rounded-[8px] border border-[#e2e8f0] bg-white px-4 pr-10 text-[13px] font-medium outline-none focus:border-[#3b82f6]"><option>Semua</option><template x-for="k in daftarKelas" :key="k"><option :value="k" x-text="k"></option></template></select>
            <select x-model="statusFilter" class="h-[38px] appearance-none rounded-[8px] border border-[#e2e8f0] bg-white px-4 pr-10 text-[13px] font-medium outline-none focus:border-[#3b82f6]"><option>Semua</option><option>AKTIF</option><option>LEAVE</option></select>
        </div>
        <div class="flex items-center gap-3">
            <button x-show="selectedCount === filtered.length && filtered.length > 0" @click="showDeleteAll = true" x-transition class="flex h-[38px] items-center gap-2 rounded-[8px] bg-[#dc2626] px-4 text-[12px] font-bold text-white transition hover:bg-[#b91c1c]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>Hapus Semua (<span x-text="selectedCount"></span>)</button>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="overflow-hidden rounded-[14px] border border-[#e2e8f0] bg-white">
        <table class="w-full text-[13px]">
            <thead><tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                <th class="w-10 px-4 py-3"><input type="checkbox" @change="toggleAll()" :checked="selectAll" class="rounded border-[#cbd5e1] cursor-pointer"></th>
                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Siswa</th>
                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NISN</th>
                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kelas</th>
                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Gender</th>
                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Status</th>
                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Aksi</th>
            </tr></thead>
            <tbody>
                <template x-for="s in filtered" :key="s.id">
                    <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                        <td class="px-4 py-3.5"><input type="checkbox" x-model="s.selected" class="rounded border-[#cbd5e1] cursor-pointer"></td>
                        <td class="px-4 py-3.5"><div class="flex items-center gap-3"><div class="flex h-8 w-8 flex-none items-center justify-center rounded-full bg-[#e2e8f0] text-[11px] font-bold text-[#475569] uppercase" x-text="(s.nama || s.name || '?').charAt(0)"></div><span class="font-bold text-[#0f172a]" x-text="s.nama || s.name"></span></div></td>
                        <td class="px-4 py-3.5"><span class="rounded bg-[#fef3c7] px-2 py-0.5 font-mono text-[11px] font-semibold text-[#92400e]" x-text="s.nis || s.nisn"></span></td>
                        <td class="px-4 py-3.5 font-semibold text-[#0f172a]" x-text="s.kelas"></td>
                        <td class="px-4 py-3.5 text-[#475569]" x-text="s.jenis_kelamin || s.gender"></td>
                        <td class="px-4 py-3.5"><span class="inline-flex rounded-md border px-2 py-0.5 text-[10px] font-bold" :class="(s.status || 'AKTIF') === 'AKTIF' ? 'border-[#a7f3d0] bg-[#ecfdf5] text-[#059669]' : 'border-[#fed7aa] bg-[#fff7ed] text-[#ea580c]'" x-text="s.status || 'AKTIF'"></span></td>
                        <td class="px-4 py-3.5 relative">
                            <button @click="openedMenu = openedMenu === s.id ? null : s.id" class="rounded-lg p-1.5 text-[#64748b] transition hover:bg-[#f1f5f9]"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1.5"></circle><circle cx="12" cy="12" r="1.5"></circle><circle cx="12" cy="19" r="1.5"></circle></svg></button>
                            <div x-show="openedMenu === s.id" @click.outside="openedMenu = null" class="absolute right-0 top-full mt-1 w-40 rounded-xl border border-[#e2e8f0] bg-white p-1.5 shadow-lg z-50" style="display:none" x-transition>
                                <button @click="openedMenu = null; $dispatch('toast',{message:'Detail siswa: '+(s.nama || s.name), type:'info'})" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-[12px] font-medium text-[#334155] hover:bg-[#f1f5f9]"><svg class="h-3.5 w-3.5 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2"></path></svg>Lihat Detail</button>
                                <button @click="openedMenu = null; $dispatch('toast',{message:'Mode edit untuk: '+(s.nama || s.name), type:'info'})" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-[12px] font-medium text-[#334155] hover:bg-[#f1f5f9]"><svg class="h-3.5 w-3.5 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"></path></svg>Edit Data</button>
                                <button @click="openedMenu = null; confirmDelete(s)" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-[12px] font-medium text-[#dc2626] hover:bg-[#fef2f2]"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>Hapus</button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        <div class="border-t border-[#e2e8f0] px-6 py-4">
            {{ $data->links() }}
        </div>
    </div>


    <template x-teleport="body">
        {{-- ─── MODAL IMPORT ─── --}}
        <x-modal alpineShow="showImport" title="Import Data Siswa" maxWidth="sm">
            <div class="space-y-4">
                {{-- Dropzone Area --}}
                <div class="relative group">
                    <input type="file" @change="importFile = $event.target.files[0]" accept=".csv,.xlsx,.xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="border-2 border-dashed border-[#e2e8f0] rounded-2xl p-8 text-center transition group-hover:border-[#3b82f6] group-hover:bg-[#eff6ff]/50" :class="importFile ? 'border-[#3b82f6] bg-[#eff6ff]/50' : ''">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#eff6ff] text-[#3b82f6] mb-4">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </div>
                        <h4 class="text-[14px] font-bold text-[#0f172a]" x-text="importFile ? importFile.name : 'Upload File Excel'">Upload File Excel</h4>
                        <p class="mt-1 text-[12px] text-[#64748b]">Format: .xlsx, .xls, .csv</p>
                    </div>
                </div>

                {{-- Download Template Button --}}
                <button @click="downloadTemplate()" class="flex w-full items-center justify-center gap-2 rounded-xl border border-[#e2e8f0] bg-white py-3 text-[13px] font-bold text-[#475569] transition hover:bg-[#f8fafc]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    Download Template
                </button>
            </div>

            <x-slot:footer>
                <button @click="showImport = false" class="flex-1 rounded-xl border border-[#e2e8f0] bg-white py-3 text-[13px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Batal</button>
                <button @click="submitImport()" :disabled="isImporting || !importFile" class="flex-1 relative rounded-xl bg-[#1d4ed8] py-3 text-[13px] font-bold text-white transition hover:bg-[#1e40af] disabled:opacity-50">
                    <span x-show="!isImporting" class="flex items-center justify-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        Import
                    </span>
                    <span x-show="isImporting" class="flex items-center justify-center">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </span>
                </button>
            </x-slot:footer>
        </x-modal>
    </template>

    {{-- ═══ MODAL: Tambah Siswa ═══ --}}
    <x-modal alpineShow="showAdd" title="Tambah Data Siswa" maxWidth="lg">
        <div class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Lengkap</label><input x-model="form.nama" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Nama siswa"></div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NISN</label><input x-model="form.nisn" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="00XXXXXXXX"></div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kelas</label><select x-model="form.kelas" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"><template x-for="k in daftarKelas" :key="k"><option :value="k" x-text="k"></option></template></select></div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Jenis Kelamin</label><div class="mt-2 flex gap-4"><label class="flex items-center gap-2 text-[14px] font-medium cursor-pointer"><input type="radio" value="Laki-laki" x-model="form.gender" class="accent-[#0f172a]"> Laki-laki</label><label class="flex items-center gap-2 text-[14px] font-medium cursor-pointer"><input type="radio" value="Perempuan" x-model="form.gender" class="accent-[#0f172a]"> Perempuan</label></div></div>
            </div>
        </div>
        <x-slot:footer>
            <button @click="showAdd = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
            <button @click="submitAdd()" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Tambah Siswa</button>
        </x-slot:footer>
    </x-modal>

    {{-- ═══ MODAL: Hapus Siswa ═══ --}}
    <x-confirm-dialog
        alpineShow="showDelete"
        type="danger"
        title="Hapus Data Siswa?"
        message="Data siswa <strong x-text='deleteTarget?.nama || deleteTarget?.name'></strong> akan dihapus permanen."
        confirmText="Ya, Hapus"
        confirmAction="doDelete()"
    />

    {{-- ═══ MODAL: Hapus Semua ═══ --}}
    <x-confirm-dialog
        alpineShow="showDeleteAll"
        type="danger"
        title="Hapus Semua Data Terpilih?"
        message="Sebanyak <strong x-text='selectedCount'></strong> data siswa akan dihapus secara permanen. Anda yakin?"
        confirmText="Ya, Hapus Semua"
        confirmAction="doDeleteAll()"
    />

</div>
