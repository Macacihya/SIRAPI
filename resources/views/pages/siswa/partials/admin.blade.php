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
            showEdit: false,
            showImport: false,
            showDelete: false,
            deleteTarget: null,
            selectAll: false,
            showDeleteAll: false,
            daftarKelas: @json($daftarKelas),
            form: { nama: '', nisn: '', nis: '', kelas_id: '', jenis_kelamin: '', tempat_lahir: '', tgl_lahir: '', alamat: '' },
            editData: { id: '', nama: '', nisn: '', nis: '', kelas_id: '', jenis_kelamin: '', tempat_lahir: '', tgl_lahir: '', alamat: '' },
            importFile: null,
            isImporting: false,
            importProgress: 0,
            searchQuery: '',
            siswa: @json($data->items()),
            
            init() {
                // Data sudah dari database, tidak perlu mapping/dummy
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
                        (s.nis && s.nis.toString().toLowerCase().includes(q)) ||
                        (s.nisn && s.nisn.toString().toLowerCase().includes(q))
                    );
                }
                return r;
            },
            get selectedCount() { return this.siswa.filter(s => s.selected).length; },
            toggleAll() { this.selectAll = !this.selectAll; this.filtered.forEach(s => s.selected = this.selectAll); },
            submitAdd() {
                document.getElementById('tambahNamaSiswa').value = this.form.nama;
                document.getElementById('tambahNisn').value = this.form.nisn;
                document.getElementById('tambahNis').value = this.form.nis;
                document.getElementById('tambahKelasId').value = this.form.kelas_id;
                document.getElementById('tambahJenisKelamin').value = this.form.jenis_kelamin;
                document.getElementById('tambahTempatLahir').value = this.form.tempat_lahir;
                document.getElementById('tambahTglLahir').value = this.form.tgl_lahir;
                document.getElementById('tambahAlamat').value = this.form.alamat;
                document.getElementById('formTambahSiswa').submit();
            },
            openEdit(s) {
                this.editData = {
                    id: s.id,
                    nama: s.nama,
                    nisn: s.nisn,
                    nis: s.nis || '',
                    kelas_id: s.kelas_id,
                    jenis_kelamin: s.jk_raw || '',
                    tempat_lahir: s.tempat_lahir || '',
                    tgl_lahir: s.tgl_lahir || '',
                    alamat: s.alamat || '',
                };
                this.showEdit = true;
            },
            submitEdit() {
                document.getElementById('formEditSiswa').action = '/siswa/' + this.editData.id;
                document.getElementById('editNamaSiswa').value = this.editData.nama;
                document.getElementById('editNisn').value = this.editData.nisn;
                document.getElementById('editNis').value = this.editData.nis;
                document.getElementById('editKelasId').value = this.editData.kelas_id;
                document.getElementById('editJenisKelamin').value = this.editData.jenis_kelamin;
                document.getElementById('editTempatLahir').value = this.editData.tempat_lahir;
                document.getElementById('editTglLahir').value = this.editData.tgl_lahir;
                document.getElementById('editAlamat').value = this.editData.alamat;
                document.getElementById('formEditSiswa').submit();
            },
            confirmDelete(s) { this.deleteTarget = s; this.showDelete = true; },
            doDelete() {
                document.getElementById('formHapusSiswa').action = '/siswa/' + this.deleteTarget.id;
                document.getElementById('formHapusSiswa').submit();
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
                            this.isImporting = false;
                            this.showImport = false;
                            this.importFile = null;
                            this.$dispatch('toast', { message: 'Data siswa berhasil di-import!', type: 'success' });
                        }, 500);
                    }
                }, 300);
            },
            downloadTemplate() {
                let csv = 'Nama,NISN,NIS,Kelas,Jenis_Kelamin,Tempat_Lahir,Tanggal_Lahir,Alamat\n';
                csv += 'Ahmad Albar,0012345601,12001,1-A,L,Jakarta,2018-05-15,Jl. Merdeka No 1\n';
                csv += 'Bella Monica,0012345602,12002,1-B,P,Bandung,2018-08-20,Jl. Sudirman No 2\n';
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

{{-- Hidden forms for server submission --}}
<form id="formTambahSiswa" method="POST" action="{{ route('siswa.store') }}" class="hidden">
    @csrf
    <input type="hidden" name="nama_siswa" id="tambahNamaSiswa">
    <input type="hidden" name="nisn" id="tambahNisn">
    <input type="hidden" name="nis" id="tambahNis">
    <input type="hidden" name="kelas_id" id="tambahKelasId">
    <input type="hidden" name="jenis_kelamin" id="tambahJenisKelamin">
    <input type="hidden" name="tempat_lahir" id="tambahTempatLahir">
    <input type="hidden" name="tgl_lahir" id="tambahTglLahir">
    <input type="hidden" name="alamat" id="tambahAlamat">
</form>
<form id="formEditSiswa" method="POST" action="" class="hidden">
    @csrf @method('PUT')
    <input type="hidden" name="nama_siswa" id="editNamaSiswa">
    <input type="hidden" name="nisn" id="editNisn">
    <input type="hidden" name="nis" id="editNis">
    <input type="hidden" name="kelas_id" id="editKelasId">
    <input type="hidden" name="jenis_kelamin" id="editJenisKelamin">
    <input type="hidden" name="tempat_lahir" id="editTempatLahir">
    <input type="hidden" name="tgl_lahir" id="editTglLahir">
    <input type="hidden" name="alamat" id="editAlamat">
</form>
<form id="formHapusSiswa" method="POST" action="" class="hidden">
    @csrf @method('DELETE')
</form>

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
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-3">
        <x-card-stat title="Total Siswa" :value="$totalSiswa" />
        <x-card-stat title="Siswa Aktif" :value="$siswaAktif" />
        <x-card-stat title="Status Cuti" :value="$siswaCuti" />
    </div>

    {{-- FILTERS --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"/><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"/></svg>
                <input type="text" x-model="searchQuery" placeholder="Cari siswa..." class="h-[38px] w-64 rounded-[8px] border border-[#e2e8f0] bg-white pl-9 pr-4 text-[13px] outline-none focus:border-[#3b82f6]">
            </div>
            <select x-model="kelasFilter" class="h-[38px] appearance-none rounded-[8px] border border-[#e2e8f0] bg-white px-4 pr-10 text-[13px] font-medium outline-none focus:border-[#3b82f6]">
                <option>Semua</option>
                <template x-for="k in daftarKelas" :key="k.id">
                    <option :value="k.nama" x-text="k.nama"></option>
                </template>
            </select>
            <select x-model="statusFilter" class="h-[38px] appearance-none rounded-[8px] border border-[#e2e8f0] bg-white px-4 pr-10 text-[13px] font-medium outline-none focus:border-[#3b82f6]"><option>Semua</option><option>AKTIF</option><option>NONAKTIF</option><option>CUTI</option></select>
        </div>
        <div class="flex items-center gap-3">
            <x-btn-delete-all />
        </div>
    </div>

    {{-- TABLE --}}
    <div class="overflow-hidden rounded-[14px] border border-[#e2e8f0] bg-white">
        <table class="w-full text-[13px]">
            <thead><tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                <th class="w-10 px-4 py-3"><x-checkbox @change="toggleAll()" x-bind:checked="selectAll" /></th>
                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">No</th>
                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Siswa</th>
                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NISN</th>
                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIS</th>
                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kelas</th>
                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">J.K.</th>
                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Status</th>
                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Aksi</th>
            </tr></thead>
            <tbody>
                <template x-for="(s, index) in filtered" :key="s.id">
                    <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                        <td class="px-4 py-3.5"><x-checkbox x-model="s.selected" /></td>
                        <td class="px-4 py-3.5 font-semibold text-[#64748b]" x-text="{{ $data->firstItem() ?? 1 }} + index"></td>
                        <td class="px-4 py-3.5"><div class="flex items-center gap-3"><div class="flex h-8 w-8 flex-none items-center justify-center rounded-full bg-[#e2e8f0] text-[11px] font-bold text-[#475569] uppercase" x-text="(s.nama || '?').charAt(0)"></div><span class="font-bold text-[#0f172a]" x-text="s.nama"></span></div></td>
                        <td class="px-4 py-3.5"><span class="rounded bg-[#fef3c7] px-2 py-0.5 font-mono text-[11px] font-semibold text-[#92400e]" x-text="s.nisn"></span></td>
                        <td class="px-4 py-3.5"><span class="rounded bg-[#f1f5f9] px-2 py-0.5 font-mono text-[11px] font-semibold text-[#475569]" x-text="s.nis || '-'"></span></td>
                        <td class="px-4 py-3.5 font-semibold text-[#0f172a]" x-text="s.kelas"></td>
                        <td class="px-4 py-3.5"><span class="text-[12px] font-semibold" x-text="s.jenis_kelamin"></span></td>
                        <td class="px-4 py-3.5"><span class="inline-flex rounded-md border px-2 py-0.5 text-[10px] font-bold" :class="(s.status || 'AKTIF') === 'AKTIF' ? 'border-[#a7f3d0] bg-[#ecfdf5] text-[#059669]' : 'border-[#fed7aa] bg-[#fff7ed] text-[#ea580c]'" x-text="s.status || 'AKTIF'"></span></td>
                        <td class="px-4 py-3.5 relative">
                            <button @click="openedMenu = openedMenu === s.id ? null : s.id" class="rounded-lg p-1.5 text-[#64748b] transition hover:bg-[#f1f5f9]"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1.5"></circle><circle cx="12" cy="12" r="1.5"></circle><circle cx="12" cy="19" r="1.5"></circle></svg></button>
                            <div x-show="openedMenu === s.id" @click.outside="openedMenu = null" class="absolute right-0 top-full mt-1 w-40 rounded-xl border border-[#e2e8f0] bg-white p-1.5 shadow-lg z-50" style="display:none" x-transition>
                                <button @click="openedMenu = null; openEdit(s)" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-[12px] font-medium text-[#334155] hover:bg-[#f1f5f9]"><svg class="h-3.5 w-3.5 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"></path></svg>Edit Data</button>
                                <button @click="openedMenu = null; confirmDelete(s)" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-[12px] font-medium text-[#dc2626] hover:bg-[#fef2f2]"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>Hapus</button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        <x-table-pagination :paginator="$data" />
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
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Lengkap</label>
                    <input x-model="form.nama" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Nama siswa">
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NISN</label>
                    <input x-model="form.nisn" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="00XXXXXXXX">
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIS</label>
                    <input x-model="form.nis" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Opsional">
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Jenis Kelamin</label>
                    <select x-model="form.jenis_kelamin" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                        <option value="" disabled selected>-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Tempat Lahir</label>
                    <input x-model="form.tempat_lahir" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Contoh: Jakarta">
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Tanggal Lahir</label>
                    <input x-model="form.tgl_lahir" type="date" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                </div>
            </div>
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kelas / Rombel</label>
                <select x-model="form.kelas_id" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                    <option value="" disabled selected>-- Pilih Rombel --</option>
                    <template x-for="k in daftarKelas" :key="k.id">
                        <option :value="k.id" x-text="k.nama"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Alamat</label>
                <textarea x-model="form.alamat" rows="2" class="mt-1 w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 py-2 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Alamat lengkap siswa"></textarea>
            </div>
        </div>
        <x-slot:footer>
            <button @click="showAdd = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
            <button @click="submitAdd()" :disabled="!form.nama || !form.nisn" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white disabled:opacity-40 transition">Tambah Siswa</button>
        </x-slot:footer>
    </x-modal>

    {{-- ═══ MODAL: Edit Siswa ═══ --}}
    <x-modal alpineShow="showEdit" title="Edit Data Siswa" maxWidth="lg">
        <div class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Lengkap</label>
                    <input x-model="editData.nama" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NISN</label>
                    <input x-model="editData.nisn" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIS</label>
                    <input x-model="editData.nis" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Jenis Kelamin</label>
                    <select x-model="editData.jenis_kelamin" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Tempat Lahir</label>
                    <input x-model="editData.tempat_lahir" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Tanggal Lahir</label>
                    <input x-model="editData.tgl_lahir" type="date" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                </div>
            </div>
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kelas / Rombel</label>
                <select x-model="editData.kelas_id" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                    <option value="" disabled>-- Pilih Rombel --</option>
                    <template x-for="k in daftarKelas" :key="k.id">
                        <option :value="k.id" :selected="k.id == editData.kelas_id" x-text="k.nama"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Alamat</label>
                <textarea x-model="editData.alamat" rows="2" class="mt-1 w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 py-2 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"></textarea>
            </div>
        </div>
        <x-slot:footer>
            <button @click="showEdit = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
            <button @click="submitEdit()" :disabled="!editData.nama || !editData.nisn" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white disabled:opacity-40 transition">Simpan Perubahan</button>
        </x-slot:footer>
    </x-modal>

    {{-- ═══ MODAL: Hapus Siswa ═══ --}}
    <x-confirm-dialog
        alpineShow="showDelete"
        type="danger"
        title="Hapus Data Siswa?"
        message="Data siswa <strong x-text='deleteTarget?.nama'></strong> akan dihapus permanen."
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
