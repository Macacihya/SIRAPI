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
            showToggleStatus: false,
            toggleTarget: null,
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
            confirmToggleStatus(s) { this.toggleTarget = s; this.showToggleStatus = true; },
            doToggleStatus() {
                document.getElementById('formToggleStatusSiswa').action = '/siswa/' + this.toggleTarget.id + '/toggle-status';
                document.getElementById('formToggleStatusSiswa').submit();
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

        Alpine.data('penempatanData', () => ({
            leftSearch: '',
            rightSearch: '',
            leftSelected: [],
            rightSelected: [],
            
            // Raw data injected from PHP
            siswaBelumDitempatkan: @json($siswaBelumDitempatkan ?? []),
            siswaDiKelas: @json($siswaDiKelas ?? []),

            // Client side filtering for Left list
            get filteredLeft() {
                const q = this.leftSearch.toLowerCase().trim();
                if (!q) return this.siswaBelumDitempatkan;
                return this.siswaBelumDitempatkan.filter(s => 
                    s.nama.toLowerCase().includes(q) || 
                    s.nisn.toLowerCase().includes(q)
                );
            },

            // Client side filtering for Right list
            get filteredRight() {
                const q = this.rightSearch.toLowerCase().trim();
                if (!q) return this.siswaDiKelas;
                return this.siswaDiKelas.filter(s => 
                    s.nama.toLowerCase().includes(q) || 
                    s.nisn.toLowerCase().includes(q)
                );
            },

            // Toggle select all left
            toggleAllLeft() {
                if (this.leftSelected.length > 0) {
                    this.leftSelected = [];
                } else {
                    this.leftSelected = this.filteredLeft.map(s => s.id);
                }
            },

            // Toggle select all right
            toggleAllRight() {
                if (this.rightSelected.length > 0) {
                    this.rightSelected = [];
                } else {
                    this.rightSelected = this.filteredRight.map(s => s.id);
                }
            },

            // Form submissions
            submitAssign() {
                if (this.leftSelected.length === 0) return;
                this.$nextTick(() => {
                    document.getElementById('formAssignSiswa').submit();
                });
            },

            submitRemove() {
                if (this.rightSelected.length === 0) return;
                this.$nextTick(() => {
                    document.getElementById('formRemoveSiswa').submit();
                });
            }
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
<form id="formToggleStatusSiswa" method="POST" action="" class="hidden">
    @csrf @method('PATCH')
</form>@php
    $activeTab = request('tab', 'semua');
@endphp

<div class="space-y-6">

    {{-- TABS --}}
    <div class="flex border-b border-[#e2e8f0]">
        <a href="{{ route('siswa', ['tab' => 'semua']) }}" class="px-6 py-3 text-[13px] font-bold transition-all border-b-2 {{ $activeTab === 'semua' ? 'border-[#1d4ed8] text-[#1d4ed8]' : 'border-transparent text-[#64748b] hover:text-[#0f172a]' }}">
            Daftar Siswa
        </a>
        <a href="{{ route('siswa', ['tab' => 'penempatan']) }}" class="px-6 py-3 text-[13px] font-bold transition-all border-b-2 {{ $activeTab === 'penempatan' ? 'border-[#1d4ed8] text-[#1d4ed8]' : 'border-transparent text-[#64748b] hover:text-[#0f172a]' }}">
            Penempatan Kelas
        </a>
    </div>

    @if($activeTab === 'semua')
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
        <div class="grid grid-cols-2 gap-4">
            <x-card-stat title="Total Siswa" :value="$totalSiswa" />
            <x-card-stat title="Siswa Aktif" :value="$siswaAktif" />
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
                                    <button @click="openedMenu = null; confirmToggleStatus(s)" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-[12px] font-medium hover:bg-[#f1f5f9]" :class="s.status_aktif ? 'text-[#ea580c]' : 'text-[#059669]'">
                                        <template x-if="s.status_aktif">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                        </template>
                                        <template x-if="!s.status_aktif">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                        </template>
                                        <span x-text="s.status_aktif ? 'Nonaktifkan' : 'Aktifkan'"></span>
                                    </button>
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

        {{-- ═══ MODAL: Toggle Status Siswa ═══ --}}
        <template x-teleport="body">
            <div
                x-show="showToggleStatus"
                class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm"
                style="display:none"
                x-transition
                @click.self="showToggleStatus = false"
            >
                <div class="w-[90%] max-w-sm rounded-2xl bg-white shadow-2xl" @click.stop>
                    <div class="p-6 text-center">
                        {{-- Icon --}}
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full ring-4"
                            :class="toggleTarget?.status_aktif ? 'bg-[#fff7ed] text-[#ea580c] ring-[#fed7aa]' : 'bg-[#ecfdf5] text-[#059669] ring-[#a7f3d0]'">
                            <template x-if="toggleTarget?.status_aktif">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            </template>
                            <template x-if="!toggleTarget?.status_aktif">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            </template>
                        </div>
                        <h3 class="mt-4 text-[18px] font-black text-[#0f172a]" x-text="toggleTarget?.status_aktif ? 'Nonaktifkan Siswa?' : 'Aktifkan Siswa?'"></h3>
                        <p class="mt-2 text-[13px] text-[#64748b]">
                            Status siswa <strong x-text="toggleTarget?.nama"></strong> akan diubah menjadi
                            <strong x-text="toggleTarget?.status_aktif ? 'Nonaktif' : 'Aktif'"></strong>.
                        </p>
                    </div>
                    <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                        <button @click="showToggleStatus = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
                        <button
                            @click="doToggleStatus()"
                            class="flex-1 rounded-lg py-2.5 text-[12px] font-bold text-white"
                            :class="toggleTarget?.status_aktif ? 'bg-[#ea580c] hover:bg-[#c2410c]' : 'bg-[#059669] hover:bg-[#047857]'"
                            x-text="toggleTarget?.status_aktif ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan'"
                        ></button>
                    </div>
                </div>
            </div>
        </template>

    </div>
    @else
    <div x-data="penempatanData" class="space-y-6">
        {{-- SELEKTOR PERIODE & KELAS --}}
        <x-section-card padding="p-6">
            <form method="GET" action="{{ route('siswa') }}" x-ref="filterForm" class="grid gap-4 sm:grid-cols-2">
                <input type="hidden" name="tab" value="penempatan">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#64748b] mb-2">Pilih Tahun Ajaran / Periode</label>
                    <select 
                        name="tahun_ajaran_id" 
                        @change="$refs.filterForm.submit()" 
                        class="h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-white px-4 text-[13px] font-semibold text-[#334155] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20 transition"
                    >
                        @foreach($tahunAjarans as $ta)
                            <option value="{{ $ta->id }}" {{ $ta->id == $tahunAjaranId ? 'selected' : '' }}>
                                Tahun Ajaran {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} - Semester {{ $ta->semester }} {!! $ta->is_active ? '(&bull; Aktif)' : '' !!}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#64748b] mb-2">Pilih Kelas Tujuan</label>
                    <select 
                        name="kelas_id" 
                        @change="$refs.filterForm.submit()" 
                        class="h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-white px-4 text-[13px] font-semibold text-[#334155] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20 transition"
                        @disabled(empty($kelasPenempatan))
                    >
                        @if(empty($kelasPenempatan))
                            <option value="">-- Belum ada kelas di periode ini --</option>
                        @else
                            @foreach($kelasPenempatan as $k)
                                <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>
                                    Kelas {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </form>
        </x-section-card>

        @if($kelasId)
        {{-- LAYOUT DUA KOLOM PANEL --}}
        <div class="grid gap-6 lg:grid-cols-2">

            {{-- KIRI: SISWA BELUM DITEMPATKAN --}}
            <x-section-card padding="p-6">
                <x-slot:action>
                    <button 
                        @click="submitAssign()" 
                        :disabled="leftSelected.length === 0"
                        class="flex h-[36px] items-center gap-2 rounded-[6px] bg-[#1d4ed8] px-4 text-[12px] font-bold text-white transition hover:bg-[#1e40af] disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Tempatkan Siswa (<span x-text="leftSelected.length"></span>)
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </button>
                </x-slot:action>

                <div>
                    <h3 class="text-[18px] font-black tracking-[-0.04em] text-[#0f172a]">Siswa Belum Ditempatkan</h3>
                    <p class="mt-1 text-[12px] text-[#64748b]">Siswa aktif yang tidak terdaftar di kelas manapun untuk periode ini</p>
                    
                    {{-- Search & Bulk Check --}}
                    <div class="mt-4 flex items-center gap-3">
                        <div class="relative flex-1">
                            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"/><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"/></svg>
                            <input 
                                type="text" 
                                x-model="leftSearch" 
                                placeholder="Cari nama atau NISN..." 
                                class="h-[36px] w-full rounded-[6px] border border-[#e2e8f0] bg-white pl-9 pr-4 text-[13px] outline-none focus:border-[#3b82f6] transition"
                            >
                        </div>
                    </div>

                    {{-- List --}}
                    <div class="mt-4 border border-[#e2e8f0] rounded-[8px] overflow-hidden">
                        <div class="max-h-[400px] overflow-y-auto divide-y divide-[#e2e8f0]">
                            <div class="flex items-center gap-3 bg-[#f8fafc] px-4 py-2.5 text-[11px] font-bold text-[#64748b]">
                                <x-checkbox @change="toggleAllLeft()" :checked="leftSelected.length > 0 && leftSelected.length === filteredLeft.length" />
                                <span class="uppercase tracking-wider">Pilih Semua (<span x-text="filteredLeft.length"></span>)</span>
                            </div>
                            <template x-for="s in filteredLeft" :key="s.id">
                                <label class="flex items-center gap-3 px-4 py-3 hover:bg-[#f8fafc] transition cursor-pointer select-none">
                                    <x-checkbox :value="s.id" x-model="leftSelected" />
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[13px] font-bold text-[#0f172a] truncate" x-text="s.nama"></p>
                                        <p class="text-[11px] font-medium text-[#64748b] mt-0.5">
                                            NISN: <span x-text="s.nisn"></span> &bull; 
                                            L/P: <span x-text="s.jk === 'Pria' ? 'L' : 'P'"></span> &bull;
                                            <span class="text-amber-600 font-semibold" x-text="s.current_kelas ? 'Asal: Kelas ' + s.current_kelas : 'Baru (Tanpa Kelas)'"></span>
                                        </p>
                                    </div>
                                </label>
                            </template>
                            <div x-show="filteredLeft.length === 0" class="py-12 text-center text-[13px] text-[#94a3b8]" style="display:none">
                                Tidak ada siswa yang ditemukan.
                            </div>
                        </div>
                    </div>
                </div>
            </x-section-card>

            {{-- KANAN: SISWA DI KELAS INI --}}
            <x-section-card padding="p-6">
                <x-slot:action>
                    <button 
                        @click="submitRemove()" 
                        :disabled="rightSelected.length === 0"
                        class="flex h-[36px] items-center gap-2 rounded-[6px] bg-red-600 px-4 text-[12px] font-bold text-white transition hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        Keluarkan Siswa (<span x-text="rightSelected.length"></span>)
                    </button>
                </x-slot:action>

                <div>
                    <h3 class="text-[18px] font-black tracking-[-0.04em] text-[#0f172a]">Siswa di Kelas Ini</h3>
                    <p class="mt-1 text-[12px] text-[#64748b]">Siswa yang terdaftar di kelas tujuan pada periode ini</p>
                    
                    {{-- Search & Bulk Check --}}
                    <div class="mt-4 flex items-center gap-3">
                        <div class="relative flex-1">
                            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"/><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"/></svg>
                            <input 
                                type="text" 
                                x-model="rightSearch" 
                                placeholder="Cari nama atau NISN..." 
                                class="h-[36px] w-full rounded-[6px] border border-[#e2e8f0] bg-white pl-9 pr-4 text-[13px] outline-none focus:border-[#3b82f6] transition"
                            >
                        </div>
                    </div>

                    {{-- List --}}
                    <div class="mt-4 border border-[#e2e8f0] rounded-[8px] overflow-hidden">
                        <div class="max-h-[400px] overflow-y-auto divide-y divide-[#e2e8f0]">
                            <div class="flex items-center gap-3 bg-[#f8fafc] px-4 py-2.5 text-[11px] font-bold text-[#64748b]">
                                <x-checkbox @change="toggleAllRight()" :checked="rightSelected.length > 0 && rightSelected.length === filteredRight.length" />
                                <span class="uppercase tracking-wider">Pilih Semua (<span x-text="filteredRight.length"></span>)</span>
                            </div>
                            <template x-for="s in filteredRight" :key="s.id">
                                <label class="flex items-center gap-3 px-4 py-3 hover:bg-[#f8fafc] transition cursor-pointer select-none">
                                    <x-checkbox :value="s.id" x-model="rightSelected" />
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[13px] font-bold text-[#0f172a] truncate" x-text="s.nama"></p>
                                        <p class="text-[11px] font-medium text-[#64748b] mt-0.5">
                                            NISN: <span x-text="s.nisn"></span> &bull; 
                                            L/P: <span x-text="s.jk === 'Pria' ? 'L' : 'P'"></span>
                                        </p>
                                    </div>
                                </label>
                            </template>
                            <div x-show="filteredRight.length === 0" class="py-12 text-center text-[13px] text-[#94a3b8]" style="display:none">
                                Kelas ini masih kosong.
                            </div>
                        </div>
                    </div>
                </div>
            </x-section-card>

        </div>

        <form id="formAssignSiswa" method="POST" action="{{ route('siswa.penempatan.store') }}" class="hidden">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
            <template x-for="id in leftSelected" :key="id">
                <input type="hidden" name="siswa_ids[]" :value="id">
            </template>
        </form>
        <form id="formRemoveSiswa" method="POST" action="{{ route('siswa.penempatan.remove') }}" class="hidden">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
            <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranId }}">
            <template x-for="id in rightSelected" :key="id">
                <input type="hidden" name="siswa_ids[]" :value="id">
            </template>
        </form>
        @else
        <x-section-card padding="p-12" class="text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-width="2"></path></svg>
            </div>
            <h3 class="mt-4 text-[18px] font-black text-[#0f172a]">Kelas Belum Ditentukan</h3>
            <p class="mt-2 text-sm text-[#64748b] max-w-md mx-auto">Silakan pilih atau tambahkan kelas untuk periode akademik aktif terlebih dahulu untuk memulai penempatan siswa.</p>
            <div class="mt-5">
                <a href="{{ route('kelas') }}" class="inline-flex h-[42px] items-center justify-center rounded-[8px] bg-[#1d4ed8] px-6 text-[13px] font-bold text-white transition hover:bg-[#1e40af]">
                    Kelola Data Kelas
                </a>
            </div>
        </x-section-card>
        @endif
    </div>
    @endif
</div>
