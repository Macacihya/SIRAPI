<x-admin-shell :user="auth()->user()" active="manajemen-user" title="Manajemen User" subtitle="Kelola pengguna sistem">

<link rel="stylesheet" href="https://fonts.fontaine.fyi/css?family=Inter&token=weHNunUCikrC3YnBmvrf">

<div x-data="{
    search: '',
    showAdd: false,
    showEdit: false,
    filterOpen: false,
    sortOpen: false,
    activeFilter: 'Semua',
    editData: {},
    fonnte_token: 'weHNunUCikrC3YnBmvrf',

    form: { nama: '', email: '', username: '', nip: '', roles: [], status: 'Aktif', whatsapp: '' },

    users: [
        { name: 'Budi Santoso',  email: 'budi.tu@sirapi.sch.id',  id: 'NIP: 19850312200501', roles: ['GURU MAPEL'],               status: 'Aktif' },
        { name: 'Siti Aminah',   email: 'siti.guru@sirapi.sch.id', id: 'NUPTK: 7831002814',   roles: ['GURU MAPEL', 'WALI KELAS'], status: 'Aktif' },
        { name: 'Andi Wijaya',   email: 'andi.std@sirapi.sch.id',  id: 'NIP: 0056123881',     roles: ['GURU MAPEL'],               status: 'Nonaktif' },
        { name: 'Dewi Kusuma',   email: 'dewi.op@sirapi.sch.id',   id: 'NIP: 19900715200801', roles: ['WALI KELAS'],               status: 'Aktif' },
        { name: 'Rizky Pratama', email: 'rizky.s@sirapi.sch.id',   id: 'NUPTK: 0062198411',   roles: ['GURU MAPEL'],               status: 'Aktif' },
    ],

    get filtered() {
        let r = this.users;
        if (this.search) {
            let s = this.search.toLowerCase();
            r = r.filter(u => u.name.toLowerCase().includes(s) || u.email.toLowerCase().includes(s) || u.id.toLowerCase().includes(s));
        }
        if (this.activeFilter !== 'Semua') {
            if (this.activeFilter === 'Guru Mapel') r = r.filter(u => u.roles.includes('GURU MAPEL'));
            if (this.activeFilter === 'Wali Kelas') r = r.filter(u => u.roles.includes('WALI KELAS'));
        }
        return r;
    },

    openEdit(u) { this.editData = JSON.parse(JSON.stringify(u)); this.showEdit = true; },

    async submitAdd() {
        this.users.unshift({
            name:   this.form.nama,
            email:  this.form.email,
            id:     'NIP: ' + this.form.nip,
            roles:  this.form.roles.length ? this.form.roles : ['GURU MAPEL'],
            status: this.form.status
        });

        if (this.form.whatsapp) {
            let jabatan = this.form.roles.join(', ') || 'GURU MAPEL';
            let pesan =
`Yth. ${this.form.nama},

Berikut adalah informasi akun Anda untuk mengakses sistem SIRAPI:

- Nama        : ${this.form.nama}
- NIP/NUPTK   : ${this.form.nip}
- Jabatan     : ${jabatan}
- Email       : ${this.form.email}
- Username    : ${this.form.username}
- Password    : ${this.form.nip}
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
                $dispatch('toast', { message: 'User ditambahkan & kredensial dikirim via WhatsApp!', type: 'success' });
            } catch (e) {
                $dispatch('toast', { message: 'User tersimpan, namun pengiriman WhatsApp gagal.', type: 'warning' });
            }
        } else {
            $dispatch('toast', { message: 'User baru berhasil ditambahkan!', type: 'success' });
        }

        this.form = { nama: '', email: '', username: '', nip: '', roles: [], status: 'Aktif', whatsapp: '' };
        this.showAdd = false;
    },

    submitEdit() {
        let idx = this.users.findIndex(u => u.id === this.editData.id);
        if (idx > -1) { this.users[idx] = JSON.parse(JSON.stringify(this.editData)); }
        this.showEdit = false;
        $dispatch('toast', { message: 'Data user berhasil diperbarui!', type: 'success' });
    },
}" class="space-y-6" style="font-family: 'Inter', sans-serif;">

    {{-- ─── HEADING ─── --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Manajemen User</h1>
            <p class="mt-2 max-w-[480px] text-[14px] leading-[1.8] text-[#475569]">Kelola data pengguna sistem, atur peran (roles), dan pantau aktivitas akses untuk menjaga keamanan data akademik.</p>
        </div>
        <button @click="showAdd = true" class="flex h-[44px] items-center gap-2 rounded-[8px] bg-[#1d4ed8] px-5 text-[13px] font-bold text-white transition hover:bg-[#1e40af]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke-width="2" stroke-linecap="round"></path><circle cx="8.5" cy="7" r="4" stroke-width="2"></circle><path d="M20 8v6m3-3h-6" stroke-width="2" stroke-linecap="round"></path></svg>
            Tambah User Baru
        </button>
    </div>

    {{-- ─── STAT CARDS ─── --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Total Pengguna</p>
            <div class="mt-3 flex items-end gap-2">
                <span class="text-[40px] font-black leading-none tracking-[-0.06em] text-[#0f172a]" x-text="users.length.toLocaleString()"></span>
            </div>
        </div>
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
            <div class="mb-3 flex h-9 w-9 items-center justify-center rounded-[6px] bg-[#f1f5f9] text-[#64748b]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </div>
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Guru</p>
            <p class="mt-1 text-[28px] font-black leading-none tracking-[-0.04em] text-[#0f172a]" x-text="users.filter(u => u.roles.some(r => r === 'GURU MAPEL' || r === 'WALI KELAS')).length"></p>
        </div>
    </div>

    {{-- ─── TOOLBAR ─── --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative">
            <button @click="filterOpen = !filterOpen; sortOpen = false" class="flex h-[38px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-4 text-[13px] font-semibold text-[#334155] transition hover:bg-[#f1f5f9]">
                <svg class="h-4 w-4 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 4h18M7 8h10M11 12h4" stroke-width="2" stroke-linecap="round"></path></svg>
                Filter Peran
                <span x-show="activeFilter !== 'Semua'" class="flex h-4 w-4 items-center justify-center rounded-full bg-[#1d4ed8] text-[9px] font-bold text-white" x-text="activeFilter === 'Guru Mapel' ? 'GM' : 'WK'"></span>
            </button>
            <div x-show="filterOpen" @click.outside="filterOpen = false" x-transition class="absolute left-0 top-full mt-1 w-48 rounded-xl border border-[#e2e8f0] bg-white p-2 shadow-lg z-50" style="display:none">
                <template x-for="f in ['Semua', 'Guru Mapel', 'Wali Kelas']">
                    <button @click="activeFilter = f; filterOpen = false" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-[13px] font-medium transition hover:bg-[#f1f5f9]" :class="activeFilter === f ? 'bg-[#eff6ff] text-[#1d4ed8] font-bold' : 'text-[#334155]'">
                        <span class="h-2 w-2 rounded-full" :class="activeFilter === f ? 'bg-[#1d4ed8]' : 'bg-[#e2e8f0]'"></span>
                        <span x-text="f"></span>
                    </button>
                </template>
            </div>
        </div>
        <div class="relative">
            <button @click="sortOpen = !sortOpen; filterOpen = false" class="flex h-[38px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-4 text-[13px] font-semibold text-[#334155] transition hover:bg-[#f1f5f9]">
                <svg class="h-4 w-4 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                Urutkan
            </button>
            <div x-show="sortOpen" @click.outside="sortOpen = false" x-transition class="absolute left-0 top-full mt-1 w-44 rounded-xl border border-[#e2e8f0] bg-white p-2 shadow-lg z-50" style="display:none">
                <template x-for="s in ['Nama A-Z', 'Nama Z-A', 'Terbaru', 'Terlama']">
                    <button @click="if(s==='Nama A-Z') users.sort((a,b)=>a.name.localeCompare(b.name)); else if(s==='Nama Z-A') users.sort((a,b)=>b.name.localeCompare(a.name)); sortOpen = false; $dispatch('toast',{message:'Diurutkan: '+s, type:'info'})" class="flex w-full items-center rounded-lg px-3 py-2 text-[13px] font-medium text-[#334155] transition hover:bg-[#f1f5f9]" x-text="s"></button>
                </template>
            </div>
        </div>
        <div class="relative ml-auto">
            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"></circle><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"></path></svg>
            <input x-model="search" class="h-[38px] w-[280px] rounded-[8px] border border-[#e2e8f0] bg-white pl-10 pr-4 text-[13px] text-[#334155] placeholder-[#94a3b8] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Cari nama, email, atau NIP/NUPTK..." type="text">
        </div>
    </div>

    {{-- ─── TABLE ─── --}}
    <div class="overflow-hidden rounded-[14px] border border-[#e2e8f0] bg-white">
        <table class="w-full text-[13px]">
            <thead>
                <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                    <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">User</th>
                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Identitas</th>
                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Role / Peran</th>
                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Status</th>
                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="u in filtered" :key="u.id">
                    <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 flex-none items-center justify-center rounded-full bg-[#e2e8f0] text-[11px] font-bold text-[#475569]" x-text="u.name.charAt(0).toUpperCase()"></div>
                                <div class="min-w-0">
                                    <p class="font-bold text-[#0f172a]" x-text="u.name"></p>
                                    <p class="text-[11px] text-[#64748b]" x-text="u.email"></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 font-mono text-[12px] text-[#64748b]" x-text="u.id"></td>
                        <td class="px-4 py-4">
                            <div class="flex flex-wrap gap-1">
                                <template x-for="role in u.roles">
                                    <span
                                        class="rounded px-2 py-0.5 text-[10px] font-bold"
                                        :class="{
                                            'bg-[#1d4ed8] text-white':   role === 'GURU MAPEL',
                                            'bg-[#7c3aed] text-white':   role === 'WALI KELAS',
                                            'bg-[#e2e8f0] text-[#475569]': role !== 'GURU MAPEL' && role !== 'WALI KELAS'
                                        }"
                                        x-text="role"
                                    ></span>
                                </template>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-1.5">
                                <span class="h-2 w-2 rounded-full" :class="u.status === 'Aktif' ? 'bg-[#059669]' : 'bg-[#94a3b8]'"></span>
                                <span class="text-[12px] font-medium" :class="u.status === 'Aktif' ? 'text-[#059669]' : 'text-[#94a3b8]'" x-text="u.status"></span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <button @click="openEdit(u)" class="rounded-lg p-1.5 text-[#64748b] transition hover:bg-[#f1f5f9] hover:text-[#1d4ed8]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"></path></svg>
                            </button>
                        </td>
                    </tr>
                </template>
                <tr x-show="filtered.length === 0">
                    <td colspan="5" class="py-12 text-center text-[14px] text-[#94a3b8]">Tidak ada user ditemukan.</td>
                </tr>
            </tbody>
        </table>
        <div class="flex items-center justify-between border-t border-[#e2e8f0] px-6 py-3">
            <p class="text-[12px] font-semibold text-[#64748b]">Menampilkan <span x-text="filtered.length"></span> dari <span x-text="users.length"></span> User</p>
        </div>
    </div>

    {{-- ═══ MODAL: Tambah User ═══ --}}
    <div x-show="showAdd" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showAdd = false">
        <div class="w-[90%] max-w-lg max-h-[90vh] overflow-y-auto rounded-2xl bg-white shadow-2xl" @click.stop>

            <div class="sticky top-0 z-10 flex items-center justify-between border-b border-[#e2e8f0] bg-white px-6 py-4 rounded-t-2xl">
                <h3 class="text-[18px] font-black text-[#0f172a]">Tambah User Baru</h3>
                <button @click="showAdd = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path></svg>
                </button>
            </div>

            <div class="space-y-4 px-6 py-5">

                {{-- Nama & Username --}}
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama lengkap</label>
                        <input x-model="form.nama" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Nama lengkap">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Username</label>
                        <input x-model="form.username" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="username">
                    </div>
                </div>

                {{-- Email & NIP --}}
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email</label>
                        <input x-model="form.email" type="email" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="email@sirapi.sch.id">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP / NUPTK</label>
                        <input x-model="form.nip" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="19XXXXXXXXXXXXXXX">
                    </div>
                </div>

                {{-- Password readonly dari NIP --}}
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Password</label>
                    <div class="relative mt-1">
                        <input
                            :value="form.nip || ''"
                            readonly
                            class="h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f1f5f9] px-4 pr-10 text-[14px] text-[#64748b] outline-none cursor-not-allowed"
                            placeholder="Otomatis dari NIP/NUPTK"
                        >
                        <div class="absolute right-3 top-1/2 -translate-y-1/2">
                            <svg class="h-4 w-4 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </div>
                    </div>
                    <p class="mt-1.5 text-[11px] text-[#64748b]">Default menggunakan NIP/NUPTK. User dapat menggantinya setelah login pertama.</p>
                </div>

                {{-- Role / Peran --}}
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Role / Peran</label>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <template x-for="r in ['GURU MAPEL', 'WALI KELAS']">
                            <label class="flex cursor-pointer items-center gap-1.5 rounded-lg border px-3 py-1.5 text-[12px] font-semibold transition" :class="form.roles.includes(r) ? 'bg-[#0f172a] text-white border-[#0f172a]' : 'border-[#e2e8f0] text-[#475569] hover:bg-[#f1f5f9]'">
                                <input type="checkbox" :value="r" x-model="form.roles" class="hidden"><span x-text="r"></span>
                            </label>
                        </template>
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Status</label>
                    <div class="mt-2 flex gap-4">
                        <label class="flex items-center gap-2 text-[14px] font-medium cursor-pointer"><input type="radio" value="Aktif" x-model="form.status" class="accent-[#0f172a]"> Aktif</label>
                        <label class="flex items-center gap-2 text-[14px] font-medium cursor-pointer"><input type="radio" value="Nonaktif" x-model="form.status" class="accent-[#0f172a]"> Nonaktif</label>
                    </div>
                </div>

                {{-- WhatsApp --}}
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nomor WhatsApp <span class="normal-case font-normal text-[#94a3b8]">(opsional)</span></label>
                    <div class="relative mt-1">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[13px] font-semibold text-[#64748b] select-none">+62</span>
                        <input x-model="form.whatsapp" type="tel" class="h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] pl-12 pr-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="8123456789">
                    </div>
                    <div class="mt-2 flex items-start gap-2 rounded-[6px] bg-[#eff6ff] px-3 py-2.5">
                        <svg class="mt-0.5 h-4 w-4 flex-none text-[#1d4ed8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round"></path></svg>
                        <p class="text-[11px] leading-[1.6] text-[#1e40af]">Jika diisi, data akun (username & password) akan dikirimkan ke nomor ini via WhatsApp. Kosongkan jika ingin menyampaikan kredensial secara langsung.</p>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                <button @click="showAdd = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569] hover:bg-[#f1f5f9]">Batal</button>
                <button
                    @click="submitAdd()"
                    :disabled="!form.nama || !form.nip || !form.username || !form.email || !form.roles.length"
                    class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white hover:bg-[#1e40af] disabled:opacity-40 disabled:cursor-not-allowed transition"
                >
                    Tambah User
                </button>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Edit User ═══ --}}
    <div x-show="showEdit" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showEdit = false">
        <div class="w-[90%] max-w-lg rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4">
                <h3 class="text-[18px] font-black text-[#0f172a]">Edit User</h3>
                <button @click="showEdit = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path></svg>
                </button>
            </div>
            <div class="space-y-4 px-6 py-5">

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama lengkap</label>
                        <input x-model="editData.name" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email</label>
                        <input x-model="editData.email" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                    </div>
                </div>

                {{-- Password baru --}}
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

                {{-- Role --}}
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Role / Peran</label>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <template x-for="r in ['GURU MAPEL', 'WALI KELAS']">
                            <label class="flex cursor-pointer items-center gap-1.5 rounded-lg border px-3 py-1.5 text-[12px] font-semibold transition" :class="editData.roles && editData.roles.includes(r) ? 'bg-[#0f172a] text-white border-[#0f172a]' : 'border-[#e2e8f0] text-[#475569] hover:bg-[#f1f5f9]'">
                                <input type="checkbox" :value="r" x-model="editData.roles" class="hidden"><span x-text="r"></span>
                            </label>
                        </template>
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Status</label>
                    <div class="mt-2 flex gap-4">
                        <label class="flex items-center gap-2 text-[14px] font-medium cursor-pointer"><input type="radio" value="Aktif" x-model="editData.status" class="accent-[#0f172a]"> Aktif</label>
                        <label class="flex items-center gap-2 text-[14px] font-medium cursor-pointer"><input type="radio" value="Nonaktif" x-model="editData.status" class="accent-[#0f172a]"> Nonaktif</label>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                <button @click="showEdit = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569] hover:bg-[#f1f5f9]">Batal</button>
                <button @click="submitEdit()" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white hover:bg-[#1e40af]">Simpan Perubahan</button>
            </div>
        </div>
    </div>

</div>
</x-admin-shell>