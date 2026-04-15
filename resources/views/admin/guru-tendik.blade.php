<x-admin-shell :user="auth()->user()" active="guru" title="Guru" subtitle="Database Kepegawaian">
<div x-data="{
    search: '',
    roleFilter: 'Semua',
    statusFilter: 'Aktif',
    showAdd: false,
    showEdit: false,
    editData: {},
    form: { nama: '', nip: '', email: '', peran: 'GURU MAPEL', mapel: '' },
    gurus: [
        { name: 'Drs. Ahmad Subagja, M.Pd.', email: 'ahmad.subagja@school.id', nip: '197503122005011880', roles: ['WALI KELAS XI IPA 1'], mapel: 'Matematika Wajib' },
        { name: 'Siti Rahmawati, S.Pd.', email: 'siti.rahma@school.id', nip: '198011852014012001', roles: ['GURU MAPEL'], mapel: 'Bahasa Inggris' },
        { name: 'Bambang Wijaya', email: 'b.wijaya@school.id', nip: '6-7822981112', roles: ['GURU MAPEL'], mapel: 'Fisika' },
        { name: 'Rina Permata, M.Si.', email: 'rina.p@school.id', nip: '198205122018012005', roles: ['WALI KELAS XI IPS 2'], mapel: 'Sosiologi / Sejarah' },
    ],
    get filtered() {
        let r = this.gurus;
        if (this.search) { let s = this.search.toLowerCase(); r = r.filter(g => g.name.toLowerCase().includes(s) || g.nip.includes(s)); }
        if (this.roleFilter !== 'Semua') { r = r.filter(g => g.roles.some(role => role.includes(this.roleFilter === 'Wali Kelas' ? 'WALI' : 'GURU MAPEL'))); }
        return r;
    },
    submitAdd() { this.gurus.unshift({ name: this.form.nama, email: this.form.email, nip: this.form.nip, roles: [this.form.peran], mapel: this.form.mapel || 'N/A' }); this.form = { nama: '', nip: '', email: '', peran: 'GURU MAPEL', mapel: '' }; this.showAdd = false; $dispatch('toast', { message: 'Data guru berhasil ditambahkan!', type: 'success' }); },
    openEdit(g) { this.editData = JSON.parse(JSON.stringify(g)); this.showEdit = true; },
    submitEdit() { let idx = this.gurus.findIndex(g => g.nip === this.editData.nip); if (idx > -1) { this.gurus[idx] = JSON.parse(JSON.stringify(this.editData)); } this.showEdit = false; $dispatch('toast', { message: 'Data guru berhasil diperbarui!', type: 'success' }); },
}" class="space-y-6">

    {{-- HEADING --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div><p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Database Kepegawaian</p><h1 class="mt-1 text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Guru</h1></div>
        <div class="flex items-center gap-2">
            <button @click="$dispatch('toast', {message: 'Data berhasil diexport! (guru_data.xlsx)', type: 'success'})" class="flex h-[42px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-5 text-[13px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>Export Data</button>
            <button @click="showAdd = true" class="flex h-[42px] items-center gap-2 rounded-[8px] bg-[#1d4ed8] px-5 text-[13px] font-bold text-white transition hover:bg-[#1e40af]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>Tambah Data Baru</button>
        </div>
    </div>

    {{-- STAT + SEARCH --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5"><p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Total Guru</p><p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]" x-text="gurus.length"></p></div>
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5"><p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Wali Kelas</p><p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]" x-text="gurus.filter(g => g.roles.some(r => r.includes('WALI'))).length"></p></div>
    </div>
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[240px]"><svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"></circle><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"></path></svg><input x-model="search" class="h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-white pl-10 pr-4 text-[13px] placeholder-[#94a3b8] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Cari nama atau NIP..."></div>
        <select x-model="roleFilter" class="h-[42px] appearance-none rounded-[8px] border border-[#e2e8f0] bg-white px-4 pr-10 text-[13px] font-medium text-[#334155] outline-none focus:border-[#3b82f6]"><option>Semua</option><option>Guru Mapel</option><option>Wali Kelas</option></select>
    </div>

    {{-- TABLE --}}
    <div class="overflow-hidden rounded-[14px] border border-[#e2e8f0] bg-white">
        <table class="w-full text-[13px]">
            <thead><tr class="border-b border-[#e2e8f0] bg-[#f8fafc]"><th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Identitas</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP / NUPTK</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Peran</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Mapel</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Aksi</th></tr></thead>
            <tbody>
                <template x-for="g in filtered" :key="g.nip">
                    <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                        <td class="px-6 py-4"><div class="flex items-center gap-3"><div class="flex h-9 w-9 flex-none items-center justify-center rounded-full bg-[#e2e8f0] text-[11px] font-bold text-[#475569]" x-text="g.name.charAt(0).toUpperCase()"></div><div class="min-w-0"><p class="font-bold text-[#0f172a]" x-text="g.name"></p><p class="text-[11px] text-[#64748b]" x-text="g.email"></p></div></div></td>
                        <td class="px-4 py-4"><span class="rounded bg-[#fef3c7] px-2 py-0.5 font-mono text-[12px] font-semibold text-[#92400e]" x-text="g.nip"></span></td>
                        <td class="px-4 py-4"><div class="flex flex-wrap gap-1"><template x-for="role in g.roles"><span class="rounded bg-[#1d4ed8] px-2 py-0.5 text-[10px] font-bold text-white" x-text="role"></span></template></div></td>
                        <td class="px-4 py-4 text-[#475569]" x-text="g.mapel"></td>
                        <td class="px-4 py-4"><button @click="openEdit(g)" class="rounded-lg p-1.5 text-[#64748b] transition hover:bg-[#f1f5f9] hover:text-[#1d4ed8]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"></path></svg></button></td>
                    </tr>
                </template>
                <tr x-show="filtered.length === 0"><td colspan="5" class="py-12 text-center text-[14px] text-[#94a3b8]">Tidak ada data guru ditemukan.</td></tr>
            </tbody>
        </table>
        <div class="flex items-center justify-between border-t border-[#e2e8f0] px-6 py-3"><p class="text-[12px] font-semibold text-[#64748b]">Menampilkan <span x-text="filtered.length"></span> dari <span x-text="gurus.length"></span> entri</p></div>
    </div>

    {{-- ═══ MODAL: Tambah Guru ═══ --}}
    <div x-show="showAdd" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showAdd = false">
        <div class="w-[90%] max-w-lg rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4"><h3 class="text-[18px] font-black text-[#0f172a]">Tambah Data Guru</h3><button @click="showAdd = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path></svg></button></div>
            <div class="space-y-4 px-6 py-5">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Lengkap & Gelar</label><input x-model="form.nama" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Drs. Nama, S.Pd."></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP / NUPTK</label><input x-model="form.nip" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="19XXXXXXXXXXXXXXX"></div>
                </div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email</label><input x-model="form.email" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="email@school.id" type="email"></div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Peran / Jabatan</label><select x-model="form.peran" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"><option>GURU MAPEL</option><option>WALI KELAS</option></select></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Mata Pelajaran</label><input x-model="form.mapel" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Matematika Wajib"></div>
                </div>
            </div>
            <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                <button @click="showAdd = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
                <button @click="submitAdd()" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Tambah Guru</button>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Edit Guru ═══ --}}
    <div x-show="showEdit" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showEdit = false">
        <div class="w-[90%] max-w-lg rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4"><h3 class="text-[18px] font-black text-[#0f172a]">Edit Data Guru</h3><button @click="showEdit = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path></svg></button></div>
            <div class="space-y-4 px-6 py-5">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Lengkap</label><input x-model="editData.name" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP / NUPTK</label><input x-model="editData.nip" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none" readonly></div>
                </div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email</label><input x-model="editData.email" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"></div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Mata Pelajaran</label><input x-model="editData.mapel" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"></div>
            </div>
            <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                <button @click="showEdit = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
                <button @click="submitEdit()" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Simpan</button>
            </div>
        </div>
    </div>

</div>
</x-admin-shell>
