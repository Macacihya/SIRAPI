@extends('layouts.app')
@section('title', 'Guru & Tenaga Kependidikan')
@section('subtitle', 'Data guru dan tenaga kependidikan')
@section('active', 'guru')

@section('content')
<link rel="stylesheet" href="https://fonts.fontaine.fyi/css?family=Inter&token=weHNunUCikrC3YnBmvrf">

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('guruTendik', () => ({
        search: '',
        roleFilter: 'Semua',
        showAdd: false,
        showEdit: false,
        showDelete: false,
        showDeleteAll: false,
        selectAll: false,
        deleteTarget: null,
        showImport: false,
        loadingImport: false,
        importFile: null,
        editData: {
            id: '', name: '', email: '', nip: '', roles: [], peran: 'GURU MAPEL',
            mapel_ids: [], kelas_pengampu_ids: [], kelas_wali_id: '', mapel_search: '', kelas_search: ''
        },
        allUsers: @json($daftarUserGuru),
        userSearch: '',
        dropdownOpen: false,
        manualMode: false,
        form: {
            user_id: '', user_name: '', user_email: '', user_nip: '',
            peran: 'GURU MAPEL', mapel_ids: [], kelas_pengampu_ids: [], kelas_wali_id: '', gelar: '',
            username: '', whatsapp: '', mapel_search: '', kelas_search: ''
        },
        fonnte_token: 'weHNunUCikrC3YnBmvrf',
        daftarMapel: @json($daftarMapel),
        daftarKelas: @json($daftarKelas),
        gurus: @json($gurus->items()),

        init() {
            // Data sudah dari database via controller
        },

        get eligibleUsers() {
            let q = (this.userSearch || '').toLowerCase();
            return this.allUsers.filter(u =>
                u.roles.some(r => r === 'GURU MAPEL' || r === 'WALI KELAS') &&
                (!q || u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q) || (u.nip || '').includes(q))
            );
        },
        get availableKelasWali() {
            return this.daftarKelas.filter(k => !k.wali_guru_id);
        },
        get filteredMapelOptions() {
            let q = (this.form.mapel_search || '').toLowerCase();
            return this.daftarMapel.filter(m => !q || m.nama.toLowerCase().includes(q) || m.id.toLowerCase().includes(q));
        },
        get filteredKelasOptions() {
            let q = (this.form.kelas_search || '').toLowerCase();
            return this.daftarKelas.filter(k => !q || k.nama.toLowerCase().includes(q));
        },
        get filteredEditMapelOptions() {
            let q = (this.editData.mapel_search || '').toLowerCase();
            return this.daftarMapel.filter(m => !q || m.nama.toLowerCase().includes(q) || m.id.toLowerCase().includes(q));
        },
        get filteredEditKelasOptions() {
            let q = (this.editData.kelas_search || '').toLowerCase();
            return this.daftarKelas.filter(k => !q || k.nama.toLowerCase().includes(q));
        },
        kelasWaliOptionsFor(selectedId) {
            return this.daftarKelas.filter(k => !k.wali_guru_id || String(k.id) === String(selectedId));
        },
        toggleSelection(values, value) {
            const stringValue = String(value);
            const current = (values || []).map(v => String(v));
            return current.includes(stringValue)
                ? current.filter(v => v !== stringValue)
                : [...current, stringValue];
        },
        isSelected(values, value) {
            return (values || []).map(v => String(v)).includes(String(value));
        },
        get filtered() {
            let r = this.gurus;
            if (this.search) { 
                let s = this.search.toLowerCase(); 
                r = r.filter(g => 
                    (g.name && g.name.toLowerCase().includes(s)) || 
                    (g.nama && g.nama.toLowerCase().includes(s)) || 
                    (g.nip && g.nip.includes(s))
                ); 
            }
            if (this.roleFilter !== 'Semua') { 
                r = r.filter(g => g.roles && g.roles.some(role => role.includes(this.roleFilter === 'Wali Kelas' ? 'WALI' : 'GURU MAPEL'))); 
            }
            return r;
        },
        get selectedCount() { return this.gurus.filter(g => g.selected).length; },
        toggleAll() { this.selectAll = !this.selectAll; this.filtered.forEach(g => g.selected = this.selectAll); },
        selectUser(u) {
            this.form.user_id = u.id; this.form.user_name = u.name; this.form.user_email = u.email; this.form.user_nip = u.nip || ''; this.form.username = u.username || '';
            this.userSearch = u.name; this.dropdownOpen = false;
        },
        clearUser() { this.form.user_id = ''; this.form.user_name = ''; this.form.user_email = ''; this.form.user_nip = ''; this.userSearch = ''; },
        toggleManual() { this.manualMode = !this.manualMode; this.clearUser(); this.form.username = ''; this.form.whatsapp = ''; },
        syncArrayInputs(containerId, name, values) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';
            (values || []).forEach(value => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                container.appendChild(input);
            });
        },
        async submitAdd() {
            const payload = {
                nama:                this.form.user_name,
                user_id:             this.form.user_id || null,
                email:               this.form.user_email,
                username:            this.form.username || (this.form.user_email ? this.form.user_email.split('@')[0] : ''),
                nip:                 this.form.user_nip,
                peran:               this.form.peran,
                kelas_wali_id:       this.form.peran.includes('WALI KELAS') ? (this.form.kelas_wali_id || null) : null,
                mapel_ids:           this.form.mapel_ids,
                kelas_pengampu_ids:  this.form.kelas_pengampu_ids,
            };

            try {
                const res = await fetch('{{ route("guru.store-ajax") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(payload)
                });
                const result = await res.json();
                if (!res.ok) throw new Error(result.message || 'Gagal menambah guru.');

                const existingIndex = this.gurus.findIndex(g => String(g.id) === String(result.guru.id));
                if (existingIndex >= 0) {
                    this.gurus.splice(existingIndex, 1, result.guru);
                } else {
                    this.gurus.unshift(result.guru);
                }
                if (payload.user_id) {
                    this.allUsers = this.allUsers.filter(u => String(u.id) !== String(payload.user_id));
                }
                this.showAdd = false;
                this.clearUser();
                this.manualMode = false;
                this.form = { user_id: '', user_name: '', user_email: '', user_nip: '', peran: 'GURU MAPEL', mapel_ids: [], kelas_pengampu_ids: [], kelas_wali_id: '', gelar: '', username: '', whatsapp: '', mapel_search: '', kelas_search: '' };
                $dispatch('toast', { message: result.message, type: 'success' });
            } catch (e) {
                $dispatch('toast', { message: e.message, type: 'error' });
            }
        },
        openEdit(g) {
            this.editData = {
                id: g.id,
                name: g.nama || g.name,
                email: g.email,
                nip: g.nip,
                roles: g.roles,
                peran: (g.roles && g.roles.some(r => r.includes('WALI'))) ? 'GURU MAPEL & WALI KELAS' : 'GURU MAPEL',
                mapel_ids: g.mapel_ids || [],
                kelas_pengampu_ids: g.kelas_pengampu_ids || [],
                kelas_wali_id: g.kelas_wali_id || '',
                mapel_search: '',
                kelas_search: ''
            };
            this.showEdit = true;
        },
        async submitEdit() {
            const payload = {
                nama:                this.editData.name,
                email:               this.editData.email,
                peran:               this.editData.peran,
                kelas_wali_id:       this.editData.peran.includes('WALI KELAS') ? (this.editData.kelas_wali_id || null) : null,
                mapel_ids:           this.editData.mapel_ids,
                kelas_pengampu_ids:  this.editData.kelas_pengampu_ids,
            };

            try {
                const res = await fetch(`/guru-ajax/${this.editData.id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(payload)
                });
                const result = await res.json();
                if (!res.ok) throw new Error(result.message || 'Gagal memperbarui guru.');

                const idx = this.gurus.findIndex(g => String(g.id) === String(this.editData.id));
                if (idx !== -1) this.gurus.splice(idx, 1, result.guru);
                this.showEdit = false;
                $dispatch('toast', { message: result.message, type: 'success' });
            } catch (e) {
                $dispatch('toast', { message: e.message, type: 'error' });
            }
        },
        confirmDelete(g) {
            this.deleteTarget = g;
            this.showDelete = true;
        },
        async doDelete() {
            try {
                const res = await fetch(`/guru-ajax/${this.deleteTarget.id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const result = await res.json();
                if (!res.ok) throw new Error(result.message || 'Gagal menghapus guru.');

                this.gurus = this.gurus.filter(g => String(g.id) !== String(this.deleteTarget.id));
                this.showDelete = false;
                this.deleteTarget = null;
                $dispatch('toast', { message: result.message, type: 'error' });
            } catch (e) {
                $dispatch('toast', { message: e.message, type: 'error' });
            }
        },
        doDeleteAll() {
            this.gurus = this.gurus.filter(g => !g.selected);
            this.showDeleteAll = false; this.selectAll = false;
            this.$dispatch('toast', { message: 'Semua guru yang dipilih berhasil dihapus.', type: 'error' });
        },
        downloadTemplate() {
            let csv = 'Nama,Email,NIP_NUPTK,Peran,Mapel_Diampu,Kelas_Diampu,Kelas_Walikelas\n';
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'template_import_guru.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        },
        handleImport() {
            if (!this.importFile) {
                this.$dispatch('toast', { message: 'Silakan pilih file terlebih dahulu.', type: 'error' });
                return;
            }
            this.loadingImport = true;
            document.getElementById('formImportGuru').submit();
        },
    }));
});
</script>

{{-- Hidden forms for server submission --}}
<form id="formTambahGuru" method="POST" action="{{ route('guru.store') }}" class="hidden">
    @csrf
    <input type="hidden" name="nama" id="tambahNamaGuru">
    <input type="hidden" name="user_id" id="tambahUserIdGuru">
    <input type="hidden" name="email" id="tambahEmailGuru">
    <input type="hidden" name="username" id="tambahUsernameGuru">
    <input type="hidden" name="nip" id="tambahNipGuru">
    <input type="hidden" name="peran" id="tambahPeranGuru">
    <input type="hidden" name="kelas_wali_id" id="tambahKelasWaliGuru">
    <div id="tambahMapelGuruInputs"></div>
    <div id="tambahKelasPengampuGuruInputs"></div>
</form>
<form id="formEditGuru" method="POST" action="" class="hidden">
    @csrf @method('PUT')
    <input type="hidden" name="nama" id="editNamaGuru">
    <input type="hidden" name="email" id="editEmailGuru">
    <input type="hidden" name="peran" id="editPeranGuru">
    <input type="hidden" name="kelas_wali_id" id="editKelasWaliGuru">
    <div id="editMapelGuruInputs"></div>
    <div id="editKelasPengampuGuruInputs"></div>
</form>
<form id="formHapusGuru" method="POST" action="" class="hidden">
    @csrf @method('DELETE')
</form>
<form id="formImportGuru" method="POST" action="{{ route('guru.import') }}" enctype="multipart/form-data" class="hidden">
    @csrf
</form>

    <div x-data="guruTendik" class="space-y-6" style="font-family: 'Inter', sans-serif;">

        {{-- ─── HEADING ─── --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Database Kepegawaian</p>
                <h1 class="mt-1 text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Guru</h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('guru.export') }}" class="flex h-[42px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-5 text-[13px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    Export Data
                </a>
                <button @click="showImport = true" class="flex h-[42px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-5 text-[13px] font-bold text-[#475569] transition hover:bg-[#f8fafc]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    Import Data
                </button>
                <button @click="showAdd = true" class="flex h-[42px] items-center gap-2 rounded-[8px] bg-[#1d4ed8] px-5 text-[13px] font-bold text-white transition hover:bg-[#1e40af]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path>
                    </svg>
                    Tambah Data Baru
                </button>
            </div>
        </div>

        {{-- ─── STAT CARDS ─── --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Total Guru</p>
                <p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">{{ $gurus->total() }}</p>
            </div>
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Guru Mapel</p>
                <p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">{{ $gurus->filter(fn($g) => in_array('GURU MAPEL', $g->roles))->count() }}</p>
            </div>
        </div>

        {{-- ─── SEARCH & FILTER & ACTIONS ─── --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[240px]">
                    <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" stroke-width="2"></circle>
                        <path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"></path>
                    </svg>
                    <input x-model="search" class="h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-white pl-10 pr-4 text-[13px] placeholder-[#94a3b8] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Cari nama atau NIP...">
                </div>
                <select x-model="roleFilter" class="h-[42px] appearance-none rounded-[8px] border border-[#e2e8f0] bg-white px-4 pr-10 text-[13px] font-medium text-[#334155] outline-none focus:border-[#3b82f6]">
                    <option>Semua</option>
                    <option>Guru Mapel</option>
                    <option>Wali Kelas</option>
                </select>
            </div>
            <div class="flex items-center gap-3">
                <x-btn-delete-all />
            </div>
        </div>

        {{-- ─── TABLE ─── --}}
        <div class="overflow-hidden rounded-[14px] border border-[#e2e8f0] bg-white">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                        <th class="w-10 px-4 py-3"><x-checkbox @change="toggleAll()" x-bind:checked="selectAll" /></th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">No</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Identitas</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP / NUPTK</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Peran</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Mapel Diampu</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kelas Diampu</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kelas Walikelas</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(g, index) in filtered" :key="g.nip">
                        <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                            <td class="px-4 py-3.5"><x-checkbox x-model="g.selected" /></td>
                            <td class="px-6 py-4 font-semibold text-[#64748b]" x-text="{{ $gurus->firstItem() ?? 1 }} + index"></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 flex-none items-center justify-center rounded-full bg-[#e2e8f0] text-[11px] font-bold text-[#475569]" x-text="g.name.charAt(0).toUpperCase()"></div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-[#0f172a]" x-text="g.name"></p>
                                        <p class="text-[11px] text-[#64748b]" x-text="g.email"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4"><span class="rounded bg-[#fef3c7] px-2 py-0.5 font-mono text-[12px] font-semibold text-[#92400e]" x-text="g.nip"></span></td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap gap-1">
                                    <template x-for="role in g.roles"><span class="rounded bg-[#1d4ed8] px-2 py-0.5 text-[10px] font-bold text-white" x-text="role"></span></template>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-[#475569]" x-text="g.mapel || '-'"></td>
                            <td class="px-4 py-4 text-[#475569]" x-text="g.kelas_diampu || '-'"></td>
                            <td class="px-4 py-4 text-[#475569]" x-text="g.kelas_walikelas || '-'"></td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-1">
                                    <button @click="openEdit(g)" class="rounded-lg p-1.5 text-[#64748b] transition hover:bg-[#f1f5f9] hover:text-[#1d4ed8]">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"></path>
                                        </svg>
                                    </button>
                                    <button @click="confirmDelete(g)" class="rounded-lg p-1.5 text-[#64748b] transition hover:bg-[#fef2f2] hover:text-[#dc2626]">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filtered.length === 0">
                        <td colspan="9" class="py-12 text-center">
                            <p class="text-[14px] text-[#94a3b8]">Tidak ada data guru ditemukan.</p>
                            <button @click="search = ''; roleFilter = 'Semua'" class="mt-4 rounded-lg border border-[#e2e8f0] px-4 py-2 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Reset Pencarian</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <x-table-pagination :paginator="$gurus" />
        </div>

        {{-- ─── RIWAYAT STATUS GURU ─── --}}
        <div class="mt-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between mb-4">
                <div>
                    <h2 class="text-[20px] font-black tracking-[-0.04em] text-[#0f172a]">Riwayat Status Mengajar</h2>
                    <p class="text-[12px] text-[#64748b]">Data historis perubahan status guru.</p>
                </div>
            </div>
            
            <x-data-table :headers="['No', 'Tanggal', 'Guru', 'Status', 'Keterangan']">
                @forelse ($riwayatGuru as $item)
                    <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                        <td class="px-6 py-4 text-[#64748b] text-[13px] font-semibold">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 text-[#64748b] text-[13px] font-medium">
                            {{ \Carbon\Carbon::parse($item->tanggal_perubahan)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-[#0f172a]">{{ $item->guru->user->nama ?? '-' }}</p>
                            <p class="text-[11px] text-[#64748b]">NIP: {{ $item->guru->nip ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 rounded-md px-2 py-1 text-xs font-bold 
                                {{ $item->status == 'Aktif' ? 'bg-[#d1fae5] text-[#065f46]' : 'bg-[#f1f5f9] text-[#475569]' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-[#64748b] text-[13px]">
                            {{ $item->keterangan ?? '-' }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            -
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-[#94a3b8] text-[13px]">Belum ada riwayat status guru.</td>
                    </tr>
                @endforelse
            </x-data-table>
        </div>

    {{-- ═══ MODAL: Tambah Guru ═══ --}}
    <x-modal alpineShow="showAdd" title="Tambah Data Guru Baru" maxWidth="3xl">
        <div class="space-y-5">
            <div class="flex items-center gap-3 rounded-xl bg-[#eff6ff] p-4 ring-1 ring-[#dbeafe]">
                <div class="flex h-10 w-10 flex-none items-center justify-center rounded-lg bg-[#1d4ed8] text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-[13px] font-bold text-[#1e40af]">Petunjuk Pendaftaran</h4>
                    <p class="mt-0.5 text-[11px] leading-[1.6] text-[#64748b]">Pastikan data NIP/NUPTK sudah sesuai untuk sinkronisasi akun login.</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center gap-4 border-b border-[#e2e8f0] pb-2">
                    <button @click="manualMode = false; clearUser()" :class="!manualMode ? 'text-[#1d4ed8] border-b-2 border-[#1d4ed8]' : 'text-[#64748b]'" class="pb-2 text-[12px] font-bold transition">Hubungkan User</button>
                    <button @click="manualMode = true; clearUser()" :class="manualMode ? 'text-[#1d4ed8] border-b-2 border-[#1d4ed8]' : 'text-[#64748b]'" class="pb-2 text-[12px] font-bold transition">Input Manual</button>
                </div>

                <div x-show="!manualMode" class="space-y-4">
                    <div class="relative" x-data="{ justSelected: false }">
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Pilih akun user</label>
                        <div class="relative mt-1">
                            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"></circle><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"></path></svg>
                            <input
                                x-model="userSearch"
                                @focus="if (!justSelected) dropdownOpen = true"
                                @input="form.user_name = ''; form.user_email = ''; form.user_nip = ''; dropdownOpen = true"
                                @keydown.escape="dropdownOpen = false"
                                @blur="setTimeout(() => { if (!justSelected) dropdownOpen = false; justSelected = false; }, 150)"
                                class="h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] pl-10 pr-16 text-[14px] outline-none focus:border-[#3b82f6] transition"
                                :class="form.user_email ? 'border-[#059669] bg-[#f0fdf4]' : ''"
                                placeholder="Ketik nama atau email guru...">
                            <button x-show="userSearch" @mousedown.prevent @click="clearUser(); dropdownOpen = true" class="absolute right-9 top-1/2 -translate-y-1/2 text-[#94a3b8] hover:text-[#475569]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path></svg></button>
                            <button type="button" @mousedown.prevent @click="dropdownOpen = !dropdownOpen" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#94a3b8] hover:text-[#475569]">
                                <svg class="h-4 w-4 transition" :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            </button>
                        </div>

                        <div x-show="dropdownOpen" class="absolute left-0 top-full z-50 mt-1 w-full overflow-hidden rounded-[10px] border border-[#e2e8f0] bg-white shadow-lg" style="display:none">
                            <div class="max-h-60 overflow-y-auto">
                            <template x-for="u in eligibleUsers" :key="u.email">
                                <button @mousedown.prevent="justSelected = true" @click="selectUser(u); dropdownOpen = false" class="flex w-full items-center gap-3 px-4 py-3 text-left transition hover:bg-[#f8fafc]">
                                    <div class="flex h-8 w-8 flex-none items-center justify-center rounded-full bg-[#e2e8f0] text-[11px] font-bold text-[#475569]" x-text="u.name.charAt(0)"></div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[13px] font-bold text-[#0f172a]" x-text="u.name"></p>
                                        <p class="text-[11px] text-[#64748b]" x-text="u.email"></p>
                                    </div>
                                </button>
                            </template>
                            <div x-show="eligibleUsers.length === 0" class="px-4 py-6 text-center">
                                <p class="text-[13px] font-bold text-[#0f172a]">Akun tidak ditemukan</p>
                                <p class="mt-1 text-[12px] text-[#64748b]">Coba kata kunci lain atau gunakan Input Manual.</p>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email</label>
                        <input :value="form.user_email" readonly class="mt-1 h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f1f5f9] px-4 text-[14px] text-[#64748b] outline-none cursor-not-allowed transition" :class="form.user_email ? 'border-[#059669] text-[#065f46]' : ''" placeholder="Otomatis terisi setelah pilih user">
                    </div>
                </div>

                <div x-show="manualMode" class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP / NUPTK</label>
                        <input x-model="form.user_nip" @input="form.user_nip = form.user_nip.replace(/[^0-9]/g, '').slice(0, 18)" class="mt-1 h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]" placeholder="19XXXXXXXXXXXXXXX">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama lengkap & gelar</label>
                        <input x-model="form.user_name" class="mt-1 h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]" placeholder="Drs. Nama, S.Pd.">
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email</label>
                            <input x-model="form.user_email" class="mt-1 h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]" placeholder="email@guru.sch.id">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Username</label>
                            <input x-model="form.username" class="mt-1 h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]" placeholder="username123">
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Peran / jabatan</label>
                        <select x-model="form.peran" @change="if (form.peran !== 'GURU MAPEL & WALI KELAS') form.kelas_wali_id = ''" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                            <option>GURU MAPEL</option>
                            <option>GURU MAPEL & WALI KELAS</option>
                        </select>
                    </div>
                    <div x-show="form.peran === 'GURU MAPEL & WALI KELAS'" x-transition>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kelas walikelas</label>
                        <select x-model="form.kelas_wali_id" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                            <option value="">-- Pilih kelas kosong --</option>
                            <template x-for="k in availableKelasWali" :key="k.id"><option :value="k.id" x-text="k.nama"></option></template>
                        </select>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Mapel diampu</label>
                        <div class="mt-1 rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] p-3">
                            <input x-model="form.mapel_search" class="h-[36px] w-full rounded-[8px] border border-[#e2e8f0] bg-white px-3 text-[13px] outline-none focus:border-[#3b82f6]" placeholder="Cari mapel...">
                            <div class="mt-3 max-h-[170px] space-y-2 overflow-y-auto pr-1">
                                <template x-for="m in filteredMapelOptions" :key="m.id">
                                    <label class="flex cursor-pointer items-center gap-2 rounded-[8px] px-2 py-1.5 transition hover:bg-white">
                                        <input type="checkbox" :checked="isSelected(form.mapel_ids, m.id)" @change="form.mapel_ids = toggleSelection(form.mapel_ids, m.id)" class="h-4 w-4 rounded border-[#cbd5e1] text-[#1d4ed8] focus:ring-[#3b82f6]">
                                        <span class="text-[13px] font-medium text-[#334155]" x-text="m.nama"></span>
                                    </label>
                                </template>
                                <p x-show="filteredMapelOptions.length === 0" class="py-3 text-center text-[12px] text-[#94a3b8]">Mapel tidak ditemukan.</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kelas diampu</label>
                        <div class="mt-1 rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] p-3">
                            <input x-model="form.kelas_search" class="h-[36px] w-full rounded-[8px] border border-[#e2e8f0] bg-white px-3 text-[13px] outline-none focus:border-[#3b82f6]" placeholder="Cari kelas...">
                            <div class="mt-3 grid max-h-[170px] grid-cols-2 gap-2 overflow-y-auto pr-1">
                                <template x-for="k in filteredKelasOptions" :key="k.id">
                                    <label class="flex cursor-pointer items-center gap-2 rounded-[8px] px-2 py-1.5 transition hover:bg-white">
                                        <input type="checkbox" :checked="isSelected(form.kelas_pengampu_ids, k.id)" @change="form.kelas_pengampu_ids = toggleSelection(form.kelas_pengampu_ids, k.id)" class="h-4 w-4 rounded border-[#cbd5e1] text-[#1d4ed8] focus:ring-[#3b82f6]">
                                        <span class="text-[13px] font-medium text-[#334155]" x-text="k.nama"></span>
                                    </label>
                                </template>
                                <p x-show="filteredKelasOptions.length === 0" class="col-span-2 py-3 text-center text-[12px] text-[#94a3b8]">Kelas tidak ditemukan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-slot:footer>
            <button @click="showAdd = false; clearUser(); manualMode = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569] hover:bg-[#f1f5f9]">Batal</button>
            <button @click="submitAdd()" :disabled="!form.user_name || !form.user_nip || !form.mapel_ids.length || !form.kelas_pengampu_ids.length || (form.peran.includes('WALI KELAS') && !form.kelas_wali_id)" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white hover:bg-[#1e40af] disabled:opacity-40 disabled:cursor-not-allowed transition">Tambah Guru</button>
        </x-slot:footer>
    </x-modal>

    {{-- ═══ MODAL: Edit Guru ═══ --}}
    <x-modal alpineShow="showEdit" title="Edit Data Guru" maxWidth="3xl">
        <div class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama lengkap</label><input x-model="editData.name" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"></div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP / NUPTK</label><input x-model="editData.nip" readonly class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f1f5f9] px-4 text-[14px] outline-none cursor-not-allowed text-[#64748b]"></div>
            </div>
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email</label><input x-model="editData.email" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"></div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Peran / jabatan</label>
                    <select x-model="editData.peran" @change="if (editData.peran !== 'GURU MAPEL & WALI KELAS') editData.kelas_wali_id = ''" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                        <option>GURU MAPEL</option>
                        <option>GURU MAPEL & WALI KELAS</option>
                    </select>
                </div>
                <div x-show="editData.peran === 'GURU MAPEL & WALI KELAS'" x-transition>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kelas walikelas</label>
                    <select x-model="editData.kelas_wali_id" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                        <option value="">-- Pilih kelas kosong --</option>
                        <template x-for="k in kelasWaliOptionsFor(editData.kelas_wali_id)" :key="k.id"><option :value="k.id" x-text="k.nama"></option></template>
                    </select>
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Mapel diampu</label>
                    <div class="mt-1 rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] p-3">
                        <input x-model="editData.mapel_search" class="h-[36px] w-full rounded-[8px] border border-[#e2e8f0] bg-white px-3 text-[13px] outline-none focus:border-[#3b82f6]" placeholder="Cari mapel...">
                        <div class="mt-3 max-h-[170px] space-y-2 overflow-y-auto pr-1">
                            <template x-for="m in filteredEditMapelOptions" :key="m.id">
                                <label class="flex cursor-pointer items-center gap-2 rounded-[8px] px-2 py-1.5 transition hover:bg-white">
                                    <input type="checkbox" :checked="isSelected(editData.mapel_ids, m.id)" @change="editData.mapel_ids = toggleSelection(editData.mapel_ids, m.id)" class="h-4 w-4 rounded border-[#cbd5e1] text-[#1d4ed8] focus:ring-[#3b82f6]">
                                    <span class="text-[13px] font-medium text-[#334155]" x-text="m.nama"></span>
                                </label>
                            </template>
                            <p x-show="filteredEditMapelOptions.length === 0" class="py-3 text-center text-[12px] text-[#94a3b8]">Mapel tidak ditemukan.</p>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kelas diampu</label>
                    <div class="mt-1 rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] p-3">
                        <input x-model="editData.kelas_search" class="h-[36px] w-full rounded-[8px] border border-[#e2e8f0] bg-white px-3 text-[13px] outline-none focus:border-[#3b82f6]" placeholder="Cari kelas...">
                        <div class="mt-3 grid max-h-[170px] grid-cols-2 gap-2 overflow-y-auto pr-1">
                            <template x-for="k in filteredEditKelasOptions" :key="k.id">
                                <label class="flex cursor-pointer items-center gap-2 rounded-[8px] px-2 py-1.5 transition hover:bg-white">
                                    <input type="checkbox" :checked="isSelected(editData.kelas_pengampu_ids, k.id)" @change="editData.kelas_pengampu_ids = toggleSelection(editData.kelas_pengampu_ids, k.id)" class="h-4 w-4 rounded border-[#cbd5e1] text-[#1d4ed8] focus:ring-[#3b82f6]">
                                    <span class="text-[13px] font-medium text-[#334155]" x-text="k.nama"></span>
                                </label>
                            </template>
                            <p x-show="filteredEditKelasOptions.length === 0" class="col-span-2 py-3 text-center text-[12px] text-[#94a3b8]">Kelas tidak ditemukan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-slot:footer>
            <button @click="showEdit = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569] hover:bg-[#f1f5f9]">Batal</button>
            <button @click="submitEdit()" :disabled="!editData.name || !editData.email || !editData.mapel_ids.length || !editData.kelas_pengampu_ids.length || (editData.peran.includes('WALI KELAS') && !editData.kelas_wali_id)" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white hover:bg-[#1e40af] transition disabled:opacity-40 disabled:cursor-not-allowed">Simpan</button>
        </x-slot:footer>
    </x-modal>

    {{-- ─── MODAL IMPORT ─── --}}
    <x-modal alpineShow="showImport" title="Import Data Guru" maxWidth="sm">
        <div class="space-y-4">
            {{-- Dropzone Area --}}
            <div class="relative group">
                <input type="file" name="file" form="formImportGuru" @change="importFile = $event.target.files[0]" accept=".csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                <div class="border-2 border-dashed border-[#e2e8f0] rounded-2xl p-8 text-center transition group-hover:border-[#3b82f6] group-hover:bg-[#eff6ff]/50" :class="importFile ? 'border-[#3b82f6] bg-[#eff6ff]/50' : ''">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#eff6ff] text-[#3b82f6] mb-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </div>
                    <h4 class="text-[14px] font-bold text-[#0f172a]" x-text="importFile ? importFile.name : 'Upload File Excel'">Upload File Excel</h4>
                    <p class="mt-1 text-[12px] text-[#64748b]">Format: .csv</p>
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
            <button @click="handleImport()" :disabled="loadingImport || !importFile" class="flex-1 relative rounded-xl bg-[#1d4ed8] py-3 text-[13px] font-bold text-white transition hover:bg-[#1e40af] disabled:opacity-50">
                <span x-show="!loadingImport" class="flex items-center justify-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    Import
                </span>
                <span x-show="loadingImport" class="flex items-center justify-center">
                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </span>
            </button>
        </x-slot:footer>
    </x-modal>

    {{-- ═══ MODAL: Hapus Guru ═══ --}}
    <x-confirm-dialog
        alpineShow="showDelete"
        type="danger"
        title="Hapus Data Guru?"
        message="Data guru <strong x-text='deleteTarget?.name || deleteTarget?.nama'></strong> beserta akun user terkait akan dihapus secara permanen."
        confirmText="Ya, Hapus"
        confirmAction="doDelete()"
    />

    {{-- ═══ MODAL: Hapus Semua ═══ --}}
    <x-confirm-dialog
        alpineShow="showDeleteAll"
        type="danger"
        title="Hapus Semua Data Terpilih?"
        message="Sebanyak <strong x-text='selectedCount'></strong> data guru akan dihapus secara permanen. Anda yakin?"
        confirmText="Ya, Hapus Semua"
        confirmAction="doDeleteAll()"
    />

    </div>
@endsection
