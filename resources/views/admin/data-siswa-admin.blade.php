<x-admin-shell :user="auth()->user()" active="data-siswa" title="Data Siswa" subtitle="Pengelolaan data induk siswa">
<div x-data="{
    kelasFilter: 'Semua',
    statusFilter: 'Semua',
    showAdd: false,
    showDelete: false,
    deleteTarget: null,
    selectAll: false,
    form: { nama: '', nisn: '', kelas: 'XII - MIPA 1', gender: 'Laki-laki', status: 'AKTIF' },
    siswa: [
        { id: 1, name: 'Ahmad Fauzi', nisn: '0061263844', kelas: 'XII - MIPA 1', gender: 'Laki-laki', status: 'AKTIF', selected: false },
        { id: 2, name: 'Siti Aminah', nisn: '0061263721', kelas: 'XII - MIPA 1', gender: 'Perempuan', status: 'AKTIF', selected: false },
        { id: 3, name: 'Budi Darmawan', nisn: '0051284118', kelas: 'XI - IIS 2', gender: 'Laki-laki', status: 'LEAVE', selected: false },
        { id: 4, name: 'Rina Maharani', nisn: '0061344532', kelas: 'X - MIPA 3', gender: 'Perempuan', status: 'AKTIF', selected: false },
        { id: 5, name: 'Dimas Prasetyo', nisn: '0061398812', kelas: 'XI - MIPA 2', gender: 'Laki-laki', status: 'AKTIF', selected: false },
    ],
    get filtered() {
        let r = this.siswa;
        if (this.kelasFilter !== 'Semua') r = r.filter(s => s.kelas.includes(this.kelasFilter));
        if (this.statusFilter !== 'Semua') r = r.filter(s => s.status === this.statusFilter);
        return r;
    },
    get selectedCount() { return this.siswa.filter(s => s.selected).length; },
    toggleAll() { this.selectAll = !this.selectAll; this.filtered.forEach(s => s.selected = this.selectAll); },
    submitAdd() { this.siswa.unshift({ id: Date.now(), name: this.form.nama, nisn: this.form.nisn, kelas: this.form.kelas, gender: this.form.gender, status: this.form.status, selected: false }); this.form = { nama:'',nisn:'',kelas:'XII - MIPA 1',gender:'Laki-laki',status:'AKTIF'}; this.showAdd = false; $dispatch('toast',{message:'Siswa berhasil ditambahkan!',type:'success'}); },
    confirmDelete(s) { this.deleteTarget = s; this.showDelete = true; },
    doDelete() { this.siswa = this.siswa.filter(s => s.id !== this.deleteTarget.id); this.showDelete = false; this.deleteTarget = null; $dispatch('toast',{message:'Data siswa berhasil dihapus.',type:'error'}); },
    openedMenu: null,
}" class="space-y-6">

    {{-- HEADING --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div><h1 class="text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Data Siswa</h1><p class="mt-2 max-w-[480px] text-[14px] leading-[1.8] text-[#475569]">Pengelolaan data induk siswa, verifikasi NISN, dan monitoring status.</p></div>
        <div class="flex items-center gap-2">
            <button @click="$dispatch('toast',{message:'File CSV berhasil diunduh! (data_siswa.csv)',type:'success'})" class="flex h-[42px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-5 text-[13px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>Export CSV</button>
            <button @click="showAdd = true" class="flex h-[42px] items-center gap-2 rounded-[8px] bg-[#1d4ed8] px-5 text-[13px] font-bold text-white transition hover:bg-[#1e40af]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>Tambah Siswa</button>
        </div>
    </div>

    {{-- STAT --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5"><p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Total Siswa</p><p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]" x-text="siswa.length"></p></div>
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5"><p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Siswa Aktif</p><p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]" x-text="siswa.filter(s=>s.status==='AKTIF').length"></p></div>
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5"><p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Status Cuti</p><p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]" x-text="siswa.filter(s=>s.status==='LEAVE').length"></p></div>
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5"><p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Dipilih</p><p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#1d4ed8]" x-text="selectedCount"></p></div>
    </div>

    {{-- FILTERS --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <select x-model="kelasFilter" class="h-[38px] appearance-none rounded-[8px] border border-[#e2e8f0] bg-white px-4 pr-10 text-[13px] font-medium outline-none focus:border-[#3b82f6]"><option>Semua</option><option>X</option><option>XI</option><option>XII</option></select>
            <select x-model="statusFilter" class="h-[38px] appearance-none rounded-[8px] border border-[#e2e8f0] bg-white px-4 pr-10 text-[13px] font-medium outline-none focus:border-[#3b82f6]"><option>Semua</option><option>AKTIF</option><option>LEAVE</option></select>
        </div>
        <p class="text-[12px] font-semibold text-[#64748b]">Menampilkan <span x-text="filtered.length"></span> siswa</p>
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
                        <td class="px-4 py-3.5"><div class="flex items-center gap-3"><div class="flex h-8 w-8 flex-none items-center justify-center rounded-full bg-[#e2e8f0] text-[11px] font-bold text-[#475569]" x-text="s.name.charAt(0)"></div><span class="font-bold text-[#0f172a]" x-text="s.name"></span></div></td>
                        <td class="px-4 py-3.5"><span class="rounded bg-[#fef3c7] px-2 py-0.5 font-mono text-[12px] font-semibold text-[#92400e]" x-text="s.nisn"></span></td>
                        <td class="px-4 py-3.5 font-semibold text-[#0f172a]" x-text="s.kelas"></td>
                        <td class="px-4 py-3.5 text-[#475569]" x-text="s.gender"></td>
                        <td class="px-4 py-3.5"><span class="inline-flex rounded-md border px-2 py-0.5 text-[10px] font-bold" :class="s.status === 'AKTIF' ? 'border-[#a7f3d0] bg-[#ecfdf5] text-[#059669]' : 'border-[#fed7aa] bg-[#fff7ed] text-[#ea580c]'" x-text="s.status"></span></td>
                        <td class="px-4 py-3.5 relative">
                            <button @click="openedMenu = openedMenu === s.id ? null : s.id" class="rounded-lg p-1.5 text-[#64748b] transition hover:bg-[#f1f5f9]"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1.5"></circle><circle cx="12" cy="12" r="1.5"></circle><circle cx="12" cy="19" r="1.5"></circle></svg></button>
                            <div x-show="openedMenu === s.id" @click.outside="openedMenu = null" class="absolute right-0 top-full mt-1 w-40 rounded-xl border border-[#e2e8f0] bg-white p-1.5 shadow-lg z-50" style="display:none" x-transition>
                                <button @click="openedMenu = null; $dispatch('toast',{message:'Detail siswa: '+s.name, type:'info'})" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-[12px] font-medium text-[#334155] hover:bg-[#f1f5f9]"><svg class="h-3.5 w-3.5 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2"></path></svg>Lihat Detail</button>
                                <button @click="openedMenu = null; $dispatch('toast',{message:'Mode edit untuk: '+s.name, type:'info'})" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-[12px] font-medium text-[#334155] hover:bg-[#f1f5f9]"><svg class="h-3.5 w-3.5 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"></path></svg>Edit Data</button>
                                <button @click="openedMenu = null; confirmDelete(s)" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-[12px] font-medium text-[#dc2626] hover:bg-[#fef2f2]"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>Hapus</button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- ═══ MODAL: Tambah Siswa ═══ --}}
    <div x-show="showAdd" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showAdd = false">
        <div class="w-[90%] max-w-lg rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4"><h3 class="text-[18px] font-black text-[#0f172a]">Tambah Data Siswa</h3><button @click="showAdd = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path></svg></button></div>
            <div class="space-y-4 px-6 py-5">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Lengkap</label><input x-model="form.nama" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Nama siswa"></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NISN</label><input x-model="form.nisn" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="00XXXXXXXX"></div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kelas</label><select x-model="form.kelas" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"><option>XII - MIPA 1</option><option>XI - MIPA 2</option><option>XI - IIS 2</option><option>X - MIPA 3</option></select></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Jenis Kelamin</label><div class="mt-2 flex gap-4"><label class="flex items-center gap-2 text-[14px] font-medium cursor-pointer"><input type="radio" value="Laki-laki" x-model="form.gender" class="accent-[#0f172a]"> Laki-laki</label><label class="flex items-center gap-2 text-[14px] font-medium cursor-pointer"><input type="radio" value="Perempuan" x-model="form.gender" class="accent-[#0f172a]"> Perempuan</label></div></div>
                </div>
            </div>
            <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                <button @click="showAdd = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
                <button @click="submitAdd()" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Tambah Siswa</button>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Hapus Siswa ═══ --}}
    <div x-show="showDelete" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showDelete = false">
        <div class="w-[90%] max-w-sm rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="p-6 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#fef2f2] text-[#dc2626] ring-4 ring-[#fee2e2]"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></div>
                <h3 class="mt-4 text-[18px] font-black text-[#0f172a]">Hapus Data Siswa?</h3>
                <p class="mt-2 text-[13px] text-[#64748b]">Data siswa <strong x-text="deleteTarget?.name"></strong> akan dihapus permanen.</p>
            </div>
            <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                <button @click="showDelete = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
                <button @click="doDelete()" class="flex-1 rounded-lg bg-[#dc2626] py-2.5 text-[12px] font-bold text-white">Ya, Hapus</button>
            </div>
        </div>
    </div>

</div>
</x-admin-shell>
