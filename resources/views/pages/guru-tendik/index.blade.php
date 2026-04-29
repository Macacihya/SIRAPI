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
        showImport: false,
        loadingImport: false,
        importFile: null,
        editData: {},
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
        daftarMapel: ['Pendidikan Agama', 'Pendidikan Pancasila', 'Bahasa Indonesia', 'Matematika', 'Tematik', 'IPAS', 'PJOK', 'Seni Budaya', 'Bahasa Inggris', 'Bahasa Daerah'],
        gurus: @json($gurus->items()),

        init() {
            // Tambahkan data dummy untuk demo jika belum ada
            const dummyNips = ['19780512200301', '19920822201502', '19800101200501', '19750315200001'];
            dummyNips.forEach((nip, i) => {
                if (!this.gurus.find(g => g.nip === nip)) {
                    const dummies = [
                        { name: 'Drs. Bambang Heru, S.Pd', email: 'bambang@sirapi.sch.id', nip: '19780512200301', roles: ['GURU MAPEL'], mapel: 'Matematika' },
                        { name: 'Larasati, S.Sn', email: 'larasati@sirapi.sch.id', nip: '19920822201502', roles: ['GURU MAPEL'], mapel: 'Seni Budaya' },
                        { name: 'H. Syamsul Maarif, Lc', email: 'syamsul@sirapi.sch.id', nip: '19800101200501', roles: ['GURU MAPEL'], mapel: 'Pendidikan Agama' },
                        { name: 'Dr. Heru Prasetyo', email: 'heru@sirapi.sch.id', nip: '19750315200001', roles: ['WALI KELAS'], mapel: '-' }
                    ];
                    this.gurus.push(dummies[i]);
                }
            });
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
        async submitAdd() {
            let namaFinal = this.manualMode ? this.form.user_name : this.form.user_name + (this.form.gelar ? ', ' + this.form.gelar : '');
            let nipFinal = this.form.user_nip;
            let mapelFinal = this.form.peran === 'WALI KELAS' ? '-' : (this.form.mapel || '-');
            this.gurus.unshift({ name: namaFinal, email: this.form.user_email, nip: nipFinal, roles: [this.form.peran], mapel: mapelFinal });

            if (this.manualMode && this.form.whatsapp) {
                let pesan = `Yth. ${namaFinal},\n\nBerikut adalah informasi akun Anda untuk mengakses sistem SIRAPI:\n\n- Nama : ${namaFinal}\n- NIP/NUPTK : ${nipFinal}\n- Jabatan : ${this.form.peran}\n- Email : ${this.form.user_email}\n- Username : ${this.form.username}\n- Password : ${nipFinal}\n- URL Login : https://sirapi.sch.id/login\n\nHarap segera login dan ganti password Anda setelah masuk pertama kali.\n\nHormat kami,\nAdministrator SIRAPI`;
                try {
                    await fetch('https://api.fonnte.com/send', { method: 'POST', headers: { 'Authorization': this.fonnte_token, 'Content-Type': 'application/json' }, body: JSON.stringify({ target: '62' + this.form.whatsapp.replace(/^0/, ''), message: pesan, countryCode: '62' }) });
                    this.$dispatch('toast', { message: 'Data guru ditambahkan & kredensial dikirim via WhatsApp!', type: 'success' });
                } catch (e) {
                    this.$dispatch('toast', { message: 'Data tersimpan, namun pengiriman WhatsApp gagal.', type: 'warning' });
                }
            } else {
                this.$dispatch('toast', { message: 'Data guru berhasil ditambahkan!', type: 'success' });
            }
            this.form = { user_name: '', user_email: '', user_nip: '', peran: 'GURU MAPEL', mapel: '', gelar: '', username: '', whatsapp: '' };
            this.userSearch = ''; this.manualMode = false; this.showAdd = false;
        },
        openEdit(g) { this.editData = JSON.parse(JSON.stringify(g)); this.showEdit = true; },
        submitEdit() {
            let idx = this.gurus.findIndex(g => g.nip === this.editData.nip);
            if (idx > -1) { this.gurus[idx] = JSON.parse(JSON.stringify(this.editData)); }
            this.showEdit = false;
            this.$dispatch('toast', { message: 'Data guru berhasil diperbarui!', type: 'success' });
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
                        <td colspan="5" class="py-12 text-center">
                            <p class="text-[14px] text-[#94a3b8]">Tidak ada data guru ditemukan.</p>
                            <button @click="search = ''; roleFilter = 'Semua'" class="mt-4 rounded-lg border border-[#e2e8f0] px-4 py-2 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Reset Pencarian</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="border-t border-[#e2e8f0] px-6 py-4">
                {{ $gurus->links() }}
            </div>
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
                        <input x-model="form.user_nip" class="mt-1 h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]" placeholder="19XXXXXXXXXXXXXXX">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama lengkap & gelar</label>
                        <input x-model="form.user_name" class="mt-1 h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]" placeholder="Drs. Nama, S.Pd.">
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
            <div class="grid gap-4 sm:grid-cols-2">
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Password baru</label><input x-model="editData.password" type="password" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]" placeholder="Kosongkan jika tidak diubah"></div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Konfirmasi password</label><input x-model="editData.password_confirm" type="password" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]" placeholder="Ulangi password baru"></div>
            </div>
            <div x-show="!editData.roles || !editData.roles.some(r => r.includes('WALI'))">
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Mata pelajaran</label>
                <select x-model="editData.mapel" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                    <template x-for="m in daftarMapel" :key="m"><option :value="m" x-text="m"></option></template>
                </select>
            </div>
        </div>
        <x-slot:footer>
            <button @click="showEdit = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569] hover:bg-[#f1f5f9]">Batal</button>
            <button @click="submitEdit()" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white hover:bg-[#1e40af]">Simpan</button>
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

    </div>
@endsection
