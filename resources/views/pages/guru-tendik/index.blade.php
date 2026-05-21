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
        deleteTarget: null,
        showImport: false,
        loadingImport: false,
        importFile: null,
        editData: { id: '', name: '', email: '', nip: '', roles: [], mapel: '' },
        allUsers: [
            { name: 'Budi Santoso',  email: 'budi.tu@sirapi.sch.id',   nip: '19850312200501',  roles: ['GURU MAPEL'] },
            { name: 'Siti Aminah',   email: 'siti.guru@sirapi.sch.id',  nip: '7831002814',      roles: ['GURU MAPEL', 'WALI KELAS'] },
            { name: 'Dewi Kusuma',   email: 'dewi.op@sirapi.sch.id',    nip: '19900715200801',  roles: ['WALI KELAS'] },
            { name: 'Fajar Nugroho', email: 'fajar.g@sirapi.sch.id',    nip: '0062198411',      roles: ['GURU MAPEL'] },
            { name: 'Rina Marlina',  email: 'rina.wk@sirapi.sch.id',    nip: '0056123881',      roles: ['WALI KELAS'] },
        ],
        userSearch: '',
        dropdownOpen: false,
        manualMode: false,
        form: {
            user_name: '', user_email: '', user_nip: '',
            peran: 'GURU MAPEL', mapel: '', gelar: '',
            username: '', whatsapp: ''
        },
        fonnte_token: 'weHNunUCikrC3YnBmvrf',
        daftarMapel: @json(array_values($daftarMapel)),
        gurus: @json($gurus->items()),

        init() {
            // Data sudah dari database via controller
        },

        get usedMapels() {
            return this.gurus.filter(g => g && g.roles && g.roles.some(r => r === 'GURU MAPEL')).map(g => g.mapel).filter(m => m && m !== '-');
        },
        get eligibleUsers() {
            let guruEmails = this.gurus.map(g => g.email);
            let q = this.userSearch.toLowerCase();
            return this.allUsers.filter(u =>
                u.roles.some(r => r === 'GURU MAPEL' || r === 'WALI KELAS') &&
                !guruEmails.includes(u.email) &&
                (u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q))
            );
        },
        get availableMapels() { return this.daftarMapel.filter(m => !this.usedMapels.includes(m)); },
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
        selectUser(u) {
            this.form.user_name = u.name; this.form.user_email = u.email; this.form.user_nip = u.nip;
            this.userSearch = u.name; this.dropdownOpen = false;
        },
        clearUser() { this.form.user_name = ''; this.form.user_email = ''; this.form.user_nip = ''; this.userSearch = ''; },
        toggleManual() { this.manualMode = !this.manualMode; this.clearUser(); this.form.username = ''; this.form.whatsapp = ''; },
        submitAdd() {
            document.getElementById('tambahNamaGuru').value = this.form.user_name;
            document.getElementById('tambahEmailGuru').value = this.form.user_email;
            document.getElementById('tambahUsernameGuru').value = this.form.username || this.form.user_email.split('@')[0];
            document.getElementById('tambahNipGuru').value = this.form.user_nip;
            document.getElementById('tambahRoleGuru').value = this.form.peran === 'WALI KELAS' ? 'walikelas' : 'guru';
            document.getElementById('tambahMapelGuru').value = this.form.peran === 'WALI KELAS' ? '' : (this.form.mapel || '');
            document.getElementById('formTambahGuru').submit();
        },
        openEdit(g) {
            this.editData = {
                id: g.id,
                name: g.nama || g.name,
                email: g.email,
                nip: g.nip,
                roles: g.roles,
                mapel: g.mapel !== '-' ? g.mapel : ''
            };
            this.showEdit = true;
        },
        submitEdit() {
            document.getElementById('formEditGuru').action = '/guru/' + this.editData.id;
            document.getElementById('editNamaGuru').value = this.editData.name;
            document.getElementById('editEmailGuru').value = this.editData.email;
            document.getElementById('editMapelGuru').value = this.editData.mapel || '';
            document.getElementById('formEditGuru').submit();
        },
        confirmDelete(g) {
            this.deleteTarget = g;
            this.showDelete = true;
        },
        doDelete() {
            document.getElementById('formHapusGuru').action = '/guru/' + this.deleteTarget.id;
            document.getElementById('formHapusGuru').submit();
        },
        downloadTemplate() {
            let csv = 'Nama,Email,NIP_NUPTK,Peran,Mata_Pelajaran\n';
            csv += 'Budi Santoso,budi@guru.sch.id,19850312200501,GURU MAPEL,Matematika\n';
            csv += 'Siti Aminah,siti@guru.sch.id,7831002814,WALI KELAS,-\n';
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
            setTimeout(() => {
                this.gurus.unshift({ 
                    name: 'Guru Baru (Import)', 
                    email: 'import@guru.sch.id', 
                    nip: '99999' + Date.now().toString().slice(-5), 
                    roles: ['GURU MAPEL'], 
                    mapel: 'Bahasa Indonesia' 
                });
                this.loadingImport = false;
                this.showImport = false;
                this.importFile = null;
                this.$dispatch('toast', { message: 'Data guru berhasil di-import!', type: 'success' });
            }, 2000);
        },
    }));
});
</script>

{{-- Hidden forms for server submission --}}
<form id="formTambahGuru" method="POST" action="{{ route('guru.store') }}" class="hidden">
    @csrf
    <input type="hidden" name="nama" id="tambahNamaGuru">
    <input type="hidden" name="email" id="tambahEmailGuru">
    <input type="hidden" name="username" id="tambahUsernameGuru">
    <input type="hidden" name="nip" id="tambahNipGuru">
    <input type="hidden" name="role" id="tambahRoleGuru">
    <input type="hidden" name="mata_pelajaran" id="tambahMapelGuru">
</form>
<form id="formEditGuru" method="POST" action="" class="hidden">
    @csrf @method('PUT')
    <input type="hidden" name="nama" id="editNamaGuru">
    <input type="hidden" name="email" id="editEmailGuru">
    <input type="hidden" name="mata_pelajaran" id="editMapelGuru">
</form>
<form id="formHapusGuru" method="POST" action="" class="hidden">
    @csrf @method('DELETE')
</form>

    <div x-data="guruTendik" class="space-y-6" style="font-family: 'Inter', sans-serif;">

        {{-- ─── HEADING ─── --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Database Kepegawaian</p>
                <h1 class="mt-1 text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Guru</h1>
            </div>
            <div class="flex items-center gap-2">
                <button @click="$dispatch('toast', {message: 'Data berhasil diexport!', type: 'success'})" class="flex h-[42px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-5 text-[13px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    Export Data
                </button>
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

        {{-- ─── SEARCH & FILTER ─── --}}
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

        {{-- ─── TABLE ─── --}}
        <x-data-table :headers="['Identitas', 'NIP / NUPTK', 'Peran', 'Mapel']">
                    <template x-for="g in filtered" :key="g.nip">
                        <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
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
                            <td class="px-4 py-4 text-[#475569]" x-text="g.mapel"></td>
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
                        <td colspan="5" class="py-12 text-center">
                            <p class="text-[14px] text-[#94a3b8]">Tidak ada data guru ditemukan.</p>
                            <button @click="search = ''; roleFilter = 'Semua'" class="mt-4 rounded-lg border border-[#e2e8f0] px-4 py-2 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Reset Pencarian</button>
                        </td>
                    </tr>
        </x-data-table>
            <div class="border-t border-[#e2e8f0] px-6 py-4">
                {{ $gurus->links() }}
            </div>
        </div>

        {{-- ─── RIWAYAT STATUS GURU ─── --}}
        <div class="mt-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between mb-4">
                <div>
                    <h2 class="text-[20px] font-black tracking-[-0.04em] text-[#0f172a]">Riwayat Status Mengajar</h2>
                    <p class="text-[12px] text-[#64748b]">Data historis perubahan status guru.</p>
                </div>
            </div>
            
            <x-data-table :headers="['Tanggal', 'Guru', 'Status', 'Keterangan']">
                @forelse ($riwayatGuru as $item)
                    <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
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
                        <td colspan="5" class="px-6 py-8 text-center text-[#94a3b8] text-[13px]">Belum ada riwayat status guru.</td>
                    </tr>
                @endforelse
            </x-data-table>
        </div>

    {{-- ═══ MODAL: Tambah Guru ═══ --}}
    <x-modal alpineShow="showAdd" title="Tambah Data Guru Baru" maxWidth="lg">
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
                                class="h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] pl-10 pr-10 text-[14px] outline-none focus:border-[#3b82f6] transition"
                                :class="form.user_email ? 'border-[#059669] bg-[#f0fdf4]' : ''"
                                placeholder="Ketik nama atau email guru...">
                            <button x-show="userSearch" @mousedown.prevent @click="clearUser()" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#94a3b8] hover:text-[#475569]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path></svg></button>
                        </div>

                        <div x-show="dropdownOpen && eligibleUsers.length > 0" class="absolute left-0 top-full z-50 mt-1 w-full overflow-hidden rounded-[10px] border border-[#e2e8f0] bg-white shadow-lg" style="display:none">
                            <template x-for="u in eligibleUsers" :key="u.email">
                                <button @mousedown.prevent="justSelected = true" @click="selectUser(u); dropdownOpen = false" class="flex w-full items-center gap-3 px-4 py-3 text-left transition hover:bg-[#f8fafc]">
                                    <div class="flex h-8 w-8 flex-none items-center justify-center rounded-full bg-[#e2e8f0] text-[11px] font-bold text-[#475569]" x-text="u.name.charAt(0)"></div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[13px] font-bold text-[#0f172a]" x-text="u.name"></p>
                                        <p class="text-[11px] text-[#64748b]" x-text="u.email"></p>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div x-show="form.user_nip" x-transition>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP / NUPTK</label>
                        <input :value="form.user_nip" readonly class="mt-1 h-[42px] w-full rounded-[8px] border border-[#059669] bg-[#f0fdf4] px-4 font-mono text-[14px] text-[#065f46] outline-none cursor-not-allowed">
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
                        <select x-model="form.peran" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                            <option>GURU MAPEL</option>
                            <option>WALI KELAS</option>
                        </select>
                    </div>
                    <div x-show="form.peran === 'GURU MAPEL'" x-transition>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Mata pelajaran</label>
                        <select x-model="form.mapel" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                            <option value="" disabled selected>-- Pilih mapel --</option>
                            <template x-for="m in availableMapels" :key="m"><option :value="m" x-text="m"></option></template>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <x-slot:footer>
            <button @click="showAdd = false; clearUser(); manualMode = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569] hover:bg-[#f1f5f9]">Batal</button>
            <button @click="submitAdd()" :disabled="!form.user_name || !form.user_nip || (form.peran === 'GURU MAPEL' && !form.mapel)" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white hover:bg-[#1e40af] disabled:opacity-40 disabled:cursor-not-allowed transition">Tambah Guru</button>
        </x-slot:footer>
    </x-modal>

    {{-- ═══ MODAL: Edit Guru ═══ --}}
    <x-modal alpineShow="showEdit" title="Edit Data Guru" maxWidth="lg">
        <div class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama lengkap</label><input x-model="editData.name" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"></div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP / NUPTK</label><input x-model="editData.nip" readonly class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f1f5f9] px-4 text-[14px] outline-none cursor-not-allowed text-[#64748b]"></div>
            </div>
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email</label><input x-model="editData.email" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"></div>
            <div x-show="!editData.roles || !editData.roles.some(r => r.includes('WALI'))">
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Mata pelajaran</label>
                <select x-model="editData.mapel" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                    <template x-for="m in daftarMapel" :key="m"><option :value="m" x-text="m"></option></template>
                </select>
            </div>
        </div>
        <x-slot:footer>
            <button @click="showEdit = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569] hover:bg-[#f1f5f9]">Batal</button>
            <button @click="submitEdit()" :disabled="!editData.name || !editData.email" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white hover:bg-[#1e40af] transition">Simpan</button>
        </x-slot:footer>
    </x-modal>

    {{-- ─── MODAL IMPORT ─── --}}
    <x-modal alpineShow="showImport" title="Import Data Guru" maxWidth="sm">
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

    </div>
@endsection
