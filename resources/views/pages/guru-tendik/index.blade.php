@extends('layouts.app')
@section('title', 'Guru & Tenaga Kependidikan')
@section('subtitle', 'Data guru dan tenaga kependidikan')
@section('active', 'guru')

@section('content')
<link rel="stylesheet" href="https://fonts.fontaine.fyi/css?family=Inter&token=weHNunUCikrC3YnBmvrf">

    <div x-data="{
    search: '',
    roleFilter: 'Semua',
    showAdd: false,
    showEdit: false,
    editData: {},

    {{-- ─── allUsers = data dari halaman Manajemen User ─── --}}
    {{-- Di produksi, inject via @json($users) atau shared state --}}
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

    daftarMapel: ['Pendidikan Agama', 'Pendidikan Pancasila', 'Bahasa Indonesia', 'Matematika', 'IPAS', 'PJOK', 'Seni Budaya', 'Fisika', 'Kimia', 'Biologi', 'Sosiologi', 'Sejarah', 'Bahasa Inggris'],

    gurus: [
        { name: 'Drs. Ahmad Subagja, M.Pd.', email: 'ahmad.subagja@school.id', nip: '197503122005011880', roles: ['WALI KELAS XI IPA 1'], mapel: '-' },
        { name: 'Siti Rahmawati, S.Pd.',      email: 'siti.rahma@school.id',    nip: '198011852014012001', roles: ['GURU MAPEL'],          mapel: 'Bahasa Inggris' },
        { name: 'Bambang Wijaya',             email: 'b.wijaya@school.id',      nip: '6-7822981112',      roles: ['GURU MAPEL'],          mapel: 'Fisika' },
        { name: 'Rina Permata, M.Si.',        email: 'rina.p@school.id',        nip: '198205122018012005', roles: ['WALI KELAS XI IPS 2'], mapel: '-' },
    ],

    {{-- ─── [FIX 3] eligibleUsers: user belum jadi guru, dan kalau GURU MAPEL
             hanya tampil kalau mapelnya belum semua terpakai ─── --}}
    get usedMapels() {
        return this.gurus
            .filter(g => g.roles.some(r => r === 'GURU MAPEL'))
            .map(g => g.mapel)
            .filter(m => m && m !== '-');
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

    get availableMapels() {
        return this.daftarMapel.filter(m => !this.usedMapels.includes(m));
    },

    get filtered() {
        let r = this.gurus;
        if (this.search) {
            let s = this.search.toLowerCase();
            r = r.filter(g => g.name.toLowerCase().includes(s) || g.nip.includes(s));
        }
        if (this.roleFilter !== 'Semua') {
            r = r.filter(g => g.roles.some(role => role.includes(this.roleFilter === 'Wali Kelas' ? 'WALI' : 'GURU MAPEL')));
        }
        return r;
    },

    {{-- ─── [FIX 1] selectUser: NIP otomatis diambil dari data allUsers ─── --}}
    selectUser(u) {
        this.form.user_name  = u.name;
        this.form.user_email = u.email;
        this.form.user_nip   = u.nip;
        this.userSearch      = u.name;
        this.dropdownOpen    = false;
    },

    clearUser() {
        this.form.user_name  = '';
        this.form.user_email = '';
        this.form.user_nip   = '';
        this.userSearch      = '';
    },

    toggleManual() {
        this.manualMode = !this.manualMode;
        this.clearUser();
        this.form.username = '';
        this.form.whatsapp = '';
    },

    async submitAdd() {
        {{-- Nama final: mode manual pakai input langsung, mode pilih user + gelar opsional --}}
        let namaFinal = this.manualMode
            ? this.form.user_name
            : this.form.user_name + (this.form.gelar ? ', ' + this.form.gelar : '');

        let nipFinal   = this.manualMode ? this.form.user_nip : this.form.user_nip;
        let mapelFinal = this.form.peran === 'WALI KELAS' ? '-' : (this.form.mapel || '-');

        this.gurus.unshift({
            name:  namaFinal,
            email: this.form.user_email,
            nip:   nipFinal,
            roles: [this.form.peran],
            mapel: mapelFinal
        });

        if (this.manualMode && this.form.whatsapp) {
            let pesan =
`Yth. ${namaFinal},

Berikut adalah informasi akun Anda untuk mengakses sistem SIRAPI:

- Nama        : ${namaFinal}
- NIP/NUPTK   : ${nipFinal}
- Jabatan     : ${this.form.peran}
- Email       : ${this.form.user_email}
- Username    : ${this.form.username}
- Password    : ${nipFinal}
- URL Login   : https://sirapi.sch.id/login

Harap segera login dan ganti password Anda setelah masuk pertama kali.

Apabila mengalami kendala, silakan hubungi Admin TU.

Hormat kami,
Administrator SIRAPI`;

            try {
                await fetch('https://api.fonnte.com/send', {
                    method: 'POST',
                    headers: {
                        'Authorization': this.fonnte_token,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        target: '62' + this.form.whatsapp.replace(/^0/, ''),
                        message: pesan,
                        countryCode: '62'
                    })
                });
                $dispatch('toast', { message: 'Data guru ditambahkan & kredensial dikirim via WhatsApp!', type: 'success' });
            } catch (e) {
                $dispatch('toast', { message: 'Data tersimpan, namun pengiriman WhatsApp gagal.', type: 'warning' });
            }
        } else {
            $dispatch('toast', { message: 'Data guru berhasil ditambahkan!', type: 'success' });
        }

        this.form = {
            user_name: '', user_email: '', user_nip: '',
            peran: 'GURU MAPEL', mapel: '', gelar: '',
            username: '', whatsapp: ''
        };
        this.userSearch = '';
        this.manualMode = false;
        this.showAdd    = false;
    },

    openEdit(g) { this.editData = JSON.parse(JSON.stringify(g)); this.showEdit = true; },

    submitEdit() {
        let idx = this.gurus.findIndex(g => g.nip === this.editData.nip);
        if (idx > -1) { this.gurus[idx] = JSON.parse(JSON.stringify(this.editData)); }
        this.showEdit = false;
        $dispatch('toast', { message: 'Data guru berhasil diperbarui!', type: 'success' });
    },
}" class="space-y-6" style="font-family: 'Inter', sans-serif;">

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
                <p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]" x-text="gurus.length"></p>
            </div>
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Wali Kelas</p>
                <p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]" x-text="gurus.filter(g => g.roles.some(r => r.includes('WALI'))).length"></p>
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
        <div class="overflow-hidden rounded-[14px] border border-[#e2e8f0] bg-white">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Identitas</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP / NUPTK</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Peran</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Mapel</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
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
                                <button @click="openEdit(g)" class="rounded-lg p-1.5 text-[#64748b] transition hover:bg-[#f1f5f9] hover:text-[#1d4ed8]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filtered.length === 0">
                        <td colspan="5" class="py-12 text-center text-[14px] text-[#94a3b8]">Tidak ada data guru ditemukan.</td>
                    </tr>
                </tbody>
            </table>
            <div class="flex items-center justify-between border-t border-[#e2e8f0] px-6 py-3">
                <p class="text-[12px] font-semibold text-[#64748b]">Menampilkan <span x-text="filtered.length"></span> dari <span x-text="gurus.length"></span> entri</p>
            </div>
        </div>

        {{-- ═══ MODAL: Tambah Guru ═══ --}}
        {{-- [FIX 4] Background: fixed inset-0 penuh, tidak nanggung --}}
        <div
            x-show="showAdd"
            x-transition
            style="display:none"
            class="fixed inset-0 z-[100] bg-[#0f172a]/60 backdrop-blur-sm"
            @click.self="showAdd = false; clearUser(); manualMode = false">

            {{-- Wrapper scroll agar modal tidak overflow di layar kecil --}}
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-2xl bg-white shadow-2xl" @click.stop>

                    {{-- Header --}}
                    <div class="sticky top-0 z-10 flex items-center justify-between border-b border-[#e2e8f0] bg-white px-6 py-4 rounded-t-2xl">
                        <h3 class="text-[18px] font-black text-[#0f172a]">Tambah Data Guru</h3>
                        <button @click="showAdd = false; clearUser(); manualMode = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4 px-6 py-5">

                        {{-- ─── Toggle: Guru belum punya akun? ─── --}}
                        <div class="flex items-center justify-between rounded-[8px] bg-[#f8fafc] px-4 py-3 border border-[#e2e8f0]">
                            <div>
                                <p class="text-[12px] font-bold text-[#0f172a]">Guru belum punya akun?</p>
                                <p class="text-[11px] text-[#64748b]">Buat akun baru sekaligus di sini</p>
                            </div>
                            <button
                                @click="toggleManual()"
                                class="relative h-6 w-11 flex-none rounded-full transition-colors duration-200"
                                :class="manualMode ? 'bg-[#1d4ed8]' : 'bg-[#e2e8f0]'">
                                <span class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform duration-200" :class="manualMode ? 'translate-x-5' : 'translate-x-0'"></span>
                            </button>
                        </div>

                        {{-- ── [FIX 2] MODE MANUAL: hanya form akun baru, tanpa duplikasi field peran/mapel di bawah ── --}}
                        <div x-show="manualMode" x-transition class="space-y-4">

                            {{-- Info banner --}}
                            <div class="flex items-start gap-2 rounded-[8px] bg-[#eff6ff] border border-[#bfdbfe] px-4 py-3">
                                <svg class="mt-0.5 h-4 w-4 flex-none text-[#1d4ed8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round"></path>
                                </svg>
                                <p class="text-[11px] leading-[1.7] text-[#1e40af]">Isi form di bawah untuk membuat akun baru. NIP/NUPTK, peran, dan mata pelajaran diisi di bagian selanjutnya.</p>
                            </div>

                            {{-- Nama & Email --}}
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama lengkap & gelar</label>
                                    <input x-model="form.user_name" class="mt-1 h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Drs. Nama, S.Pd.">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email</label>
                                    <input x-model="form.user_email" type="email" class="mt-1 h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="email@school.id">
                                </div>
                            </div>

                            {{-- Username --}}
                            <div>
                                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Username</label>
                                <input x-model="form.username" class="mt-1 h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="username">
                            </div>

                            {{-- WhatsApp --}}
                            <div>
                                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nomor WhatsApp <span class="normal-case font-normal text-[#94a3b8]">(opsional)</span></label>
                                <div class="relative mt-1">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[13px] font-semibold text-[#64748b] select-none">+62</span>
                                    <input x-model="form.whatsapp" type="tel" class="h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] pl-12 pr-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="8123456789">
                                </div>
                                <div class="mt-2 flex items-start gap-2 rounded-[6px] bg-[#eff6ff] px-3 py-2.5">
                                    <svg class="mt-0.5 h-4 w-4 flex-none text-[#1d4ed8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round"></path>
                                    </svg>
                                    <p class="text-[11px] leading-[1.6] text-[#1e40af]">Jika diisi, data akun (username & password) akan dikirimkan ke nomor ini via WhatsApp. Kosongkan jika ingin menyampaikan kredensial secara langsung.</p>
                                </div>
                            </div>

                            {{-- Divider --}}
                            <div class="flex items-center gap-3 py-1">
                                <div class="h-px flex-1 bg-[#e2e8f0]"></div>
                                <span class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#94a3b8]">Data Kepegawaian</span>
                                <div class="h-px flex-1 bg-[#e2e8f0]"></div>
                            </div>
                        </div>

                        {{-- ── MODE: Pilih dari akun yang sudah ada ── --}}
                        <div x-show="!manualMode" class="space-y-4">
                            <div class="relative" x-data="{ justSelected: false }">
                                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Pilih akun user</label>
                                <div class="relative mt-1">
                                    <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="11" cy="11" r="8" stroke-width="2"></circle>
                                        <path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"></path>
                                    </svg>
                                    <input
                                        x-model="userSearch"
                                        @focus="if (!justSelected) dropdownOpen = true"
                                        @input="form.user_name = ''; form.user_email = ''; form.user_nip = ''; dropdownOpen = true"
                                        @keydown.escape="dropdownOpen = false"
                                        @blur="setTimeout(() => { if (!justSelected) dropdownOpen = false; justSelected = false; }, 150)"
                                        class="h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] pl-10 pr-10 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20 transition"
                                        :class="form.user_email ? 'border-[#059669] bg-[#f0fdf4]' : ''"
                                        placeholder="Ketik nama atau email guru...">
                                    <button x-show="userSearch" @mousedown.prevent @click="clearUser()" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#94a3b8] hover:text-[#475569]">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path>
                                        </svg>
                                    </button>
                                </div>

                                {{-- Dropdown hasil pencarian --}}
                                <div x-show="dropdownOpen && eligibleUsers.length > 0" class="absolute left-0 top-full z-50 mt-1 w-full overflow-hidden rounded-[10px] border border-[#e2e8f0] bg-white shadow-lg" style="display:none">
                                    <template x-for="u in eligibleUsers" :key="u.email">
                                        <button
                                            @mousedown.prevent="justSelected = true"
                                            @click="selectUser(u); dropdownOpen = false"
                                            class="flex w-full items-center gap-3 px-4 py-3 text-left transition hover:bg-[#f8fafc]">
                                            <div class="flex h-8 w-8 flex-none items-center justify-center rounded-full bg-[#e2e8f0] text-[11px] font-bold text-[#475569]" x-text="u.name.charAt(0)"></div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-[13px] font-bold text-[#0f172a]" x-text="u.name"></p>
                                                <p class="text-[11px] text-[#64748b]" x-text="u.email"></p>
                                            </div>
                                            <div class="flex flex-col items-end gap-1">
                                                <div class="flex flex-wrap justify-end gap-1">
                                                    <template x-for="r in u.roles"><span class="rounded bg-[#1d4ed8] px-1.5 py-0.5 text-[9px] font-bold text-white" x-text="r"></span></template>
                                                </div>
                                                {{-- [FIX 1] Tampilkan NIP dari data user --}}
                                                <span class="font-mono text-[10px] text-[#94a3b8]" x-text="u.nip"></span>
                                            </div>
                                        </button>
                                    </template>
                                </div>

                                <div x-show="dropdownOpen && eligibleUsers.length === 0 && userSearch.length > 0" class="absolute left-0 top-full z-50 mt-1 w-full rounded-[10px] border border-[#e2e8f0] bg-white px-4 py-3 shadow-lg" style="display:none">
                                    <p class="text-[13px] text-[#94a3b8]">Tidak ada user ditemukan. Aktifkan mode manual di atas.</p>
                                </div>
                            </div>

                            {{-- [FIX 1] NIP otomatis tampil dari user yang dipilih --}}
                            <div x-show="form.user_nip" x-transition>
                                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP / NUPTK</label>
                                <div class="relative mt-1">
                                    <input :value="form.user_nip" readonly class="h-[42px] w-full rounded-[8px] border border-[#059669] bg-[#f0fdf4] px-4 font-mono text-[14px] text-[#065f46] outline-none cursor-not-allowed">
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                        <svg class="h-4 w-4 text-[#059669]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-1.5 text-[11px] text-[#64748b]">Diambil otomatis dari data Manajemen User.</p>
                            </div>

                            {{-- Email readonly --}}
                            <div>
                                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email</label>
                                <div class="relative mt-1">
                                    <input :value="form.user_email" readonly class="h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f1f5f9] px-4 text-[14px] text-[#64748b] outline-none cursor-not-allowed transition" :class="form.user_email ? 'border-[#059669] text-[#065f46]' : ''" placeholder="Otomatis terisi setelah pilih user">
                                    <div x-show="form.user_email" class="absolute right-3 top-1/2 -translate-y-1/2">
                                        <svg class="h-4 w-4 text-[#059669]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Gelar opsional --}}
                            <div x-show="form.user_name" x-transition>
                                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Gelar <span class="normal-case font-normal text-[#94a3b8]">(opsional)</span></label>
                                <input x-model="form.gelar" class="mt-1 h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="S.Pd., M.Si., dll">
                            </div>
                        </div>

                        {{-- ── [FIX 2] NIP manual: hanya tampil di mode manual ── --}}
                        <div x-show="manualMode">
                            <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP / NUPTK</label>
                            <input x-model="form.user_nip" class="mt-1 h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="19XXXXXXXXXXXXXXX">
                            <p class="mt-1.5 text-[11px] text-[#64748b]">Password default akun baru akan menggunakan NIP/NUPTK ini.</p>
                        </div>

                        {{-- ── Peran & Mapel (selalu tampil, bersama untuk kedua mode) ── --}}
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Peran / jabatan</label>
                                <select x-model="form.peran" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                                    <option>GURU MAPEL</option>
                                    <option>WALI KELAS</option>
                                </select>
                            </div>

                            {{-- [FIX 3] Dropdown mapel hanya tampilkan yang belum ada gurunya --}}
                            <div x-show="form.peran === 'GURU MAPEL'" x-transition>
                                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Mata pelajaran</label>
                                <select x-model="form.mapel" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                                    <option value="" disabled selected>-- Pilih mapel --</option>
                                    <template x-for="m in availableMapels" :key="m">
                                        <option :value="m" x-text="m"></option>
                                    </template>
                                    <template x-if="availableMapels.length === 0">
                                        <option disabled>Semua mapel sudah memiliki guru</option>
                                    </template>
                                </select>
                                <p class="mt-1.5 text-[11px] text-[#64748b]">Hanya menampilkan mapel yang belum memiliki guru.</p>
                            </div>

                            <div x-show="form.peran === 'WALI KELAS'" x-transition class="flex items-end pb-1">
                                <div class="flex w-full items-center gap-2 rounded-[6px] bg-[#f0fdf4] px-3 py-2.5 border border-[#bbf7d0]">
                                    <svg class="h-4 w-4 flex-none text-[#059669]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round"></path>
                                    </svg>
                                    <p class="text-[11px] text-[#065f46]">Kelas yang diampu diatur di halaman Data Kelas.</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                        <button @click="showAdd = false; clearUser(); manualMode = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569] hover:bg-[#f1f5f9]">Batal</button>
                        <button
                            @click="submitAdd()"
                            :disabled="!form.user_name || !form.user_nip || (form.peran === 'GURU MAPEL' && !form.mapel)"
                            class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white hover:bg-[#1e40af] disabled:opacity-40 disabled:cursor-not-allowed transition">
                            Tambah Guru
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- ═══ MODAL: Edit Guru ═══ --}}
        {{-- [FIX 4] Background penuh dengan pola yang sama --}}
        <div
            x-show="showEdit"
            x-transition
            style="display:none"
            class="fixed inset-0 z-[100] bg-[#0f172a]/60 backdrop-blur-sm"
            @click.self="showEdit = false">

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl" @click.stop>

                    <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4">
                        <h3 class="text-[18px] font-black text-[#0f172a]">Edit Data Guru</h3>
                        <button @click="showEdit = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4 px-6 py-5">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama lengkap</label>
                                <input x-model="editData.name" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP / NUPTK</label>
                                <input x-model="editData.nip" readonly class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f1f5f9] px-4 text-[14px] outline-none cursor-not-allowed text-[#64748b]">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email</label>
                            <input x-model="editData.email" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Password baru</label>
                                <input x-model="editData.password" type="password" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Kosongkan jika tidak diubah">
                                <p class="mt-1.5 text-[11px] text-[#64748b]">Kosongkan jika tidak ingin mengubah password.</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Konfirmasi password</label>
                                <input x-model="editData.password_confirm" type="password" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Ulangi password baru">
                            </div>
                        </div>
                        <div x-show="!editData.roles || !editData.roles.some(r => r.includes('WALI'))">
                            <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Mata pelajaran</label>
                            <select x-model="editData.mapel" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                                <option value="" disabled>-- Pilih mapel --</option>
                                <template x-for="m in daftarMapel" :key="m">
                                    <option :value="m" x-text="m"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                        <button @click="showEdit = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569] hover:bg-[#f1f5f9]">Batal</button>
                        <button @click="submitEdit()" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white hover:bg-[#1e40af]">Simpan</button>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
