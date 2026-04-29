@extends('layouts.app')
@section('title', 'Data Kelas')
@section('subtitle', 'Manajemen data kelas & rombongan belajar')
@section('active', 'data-kelas')

@section('content')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dataKelasData', () => ({
            showTambah: false,
            showEdit: false,
            showDelete: false,
            deleteTarget: null,
            editData: {},
            form: { nama: '', kapasitas: 32, wali: '' },
            daftarGuru: [
                'Drs. Ahmad Subagja, M.Pd.',
                'Siti Rahmawati, S.Pd.',
                'Bambang Wijaya',
                'Rina Permata, M.Si.',
                'Dr. Agus Salim',
                'Ir. Hendra Gunawan',
                'Budi Setiawan',
                'Dewi Kusuma, S.Sos',
                'Rina Sari, S.E',
                'Siti Aminah, M.Pd',
            ],
            kelas: [
                { id:1, nama:'Kelas 1-A', kapasitas:32, terisi:32, wali:'Drs. Ahmad Subagja, M.Pd.' },
                { id:2, nama:'Kelas 1-B', kapasitas:32, terisi:30, wali:'Siti Rahmawati, S.Pd.' },
                { id:3, nama:'Kelas 2-A', kapasitas:32, terisi:28, wali:'Bambang Wijaya' },
                { id:4, nama:'Kelas 3-A', kapasitas:32, terisi:32, wali:'Rina Permata, M.Si.' },
                { id:5, nama:'Kelas 4-A', kapasitas:32, terisi:30, wali:'Dr. Agus Salim' },
                { id:6, nama:'Kelas 5-A', kapasitas:32, terisi:31, wali:'Ir. Hendra Gunawan' },
                { id:7, nama:'Kelas 6-A', kapasitas:32, terisi:29, wali:'Budi Setiawan' },
            ],
            get totalSiswa() { return this.kelas.reduce((a,k) => a + k.terisi, 0); },
            get totalKapasitas() { return this.kelas.reduce((a,k) => a + k.kapasitas, 0); },
            pct(k) { return Math.round((k.terisi / k.kapasitas) * 100); },
            addKelas() {
                this.kelas.push({ id: Date.now(), nama: this.form.nama, kapasitas: parseInt(this.form.kapasitas), terisi: 0, wali: this.form.wali });
                this.form = { nama:'', kapasitas:32, wali:'' };
                this.showTambah = false;
                this.$dispatch('toast',{message:'Kelas baru berhasil ditambahkan!',type:'success'});
            },
            openEdit(k) { this.editData = JSON.parse(JSON.stringify(k)); this.showEdit = true; },
            submitEdit() {
                let idx = this.kelas.findIndex(k => k.id === this.editData.id);
                if (idx > -1) this.kelas[idx] = JSON.parse(JSON.stringify(this.editData));
                this.showEdit = false;
                this.$dispatch('toast',{message:'Data kelas berhasil diperbarui!',type:'success'});
            },
            confirmDelete(k) { this.deleteTarget = k; this.showDelete = true; },
            doDelete() {
                this.kelas = this.kelas.filter(k => k.id !== this.deleteTarget.id);
                this.showDelete = false; this.deleteTarget = null;
                this.$dispatch('toast',{message:'Kelas berhasil dihapus.',type:'error'});
            },
        }));
    });
</script>

<div x-data="dataKelasData" class="space-y-6">

    {{-- HEADING --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Manajemen Kelas</p>
            <h1 class="mt-1 text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Data Kelas</h1>
        </div>
        <button @click="showTambah = true" class="flex h-[42px] items-center gap-2 rounded-[8px] bg-[#1d4ed8] px-5 text-[13px] font-bold text-white transition hover:bg-[#1e40af]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>
            Tambah Kelas Baru
        </button>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Total Kelas</p>
            <p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]" x-text="kelas.length"></p>
        </div>
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Total Siswa</p>
            <p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]" x-text="totalSiswa"></p>
        </div>
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Total Kapasitas</p>
            <p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]" x-text="totalKapasitas"></p>
        </div>
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Kelas Penuh</p>
            <p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#dc2626]" x-text="kelas.filter(k => k.terisi >= k.kapasitas).length"></p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="overflow-hidden rounded-[14px] border border-[#e2e8f0] bg-white">
        <table class="w-full text-[13px]">
            <thead>
                <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                    <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Kelas</th>
                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Wali Kelas</th>
                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kapasitas</th>
                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="k in kelas" :key="k.id">
                    <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                        <td class="px-6 py-4">
                            <p class="font-black text-[15px] tracking-[-0.02em] text-[#0f172a]" x-text="k.nama"></p>
                        </td>
                        <td class="px-4 py-4 font-semibold text-[#334155]" x-text="k.wali || '-'"></td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-24 h-1.5 overflow-hidden rounded-full bg-[#e2e8f0]">
                                    <div class="h-full rounded-full transition-all" :class="pct(k) >= 100 ? 'bg-[#dc2626]' : 'bg-[#1d4ed8]'" :style="'width:'+Math.min(pct(k),100)+'%'"></div>
                                </div>
                                <span class="text-[11px] font-bold text-[#0f172a]" x-text="k.terisi + ' / ' + k.kapasitas"></span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-1">
                                <button @click="openEdit(k)" class="rounded-lg p-1.5 text-[#64748b] transition hover:bg-[#f1f5f9] hover:text-[#1d4ed8]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"></path></svg>
                                </button>
                                <button @click="confirmDelete(k)" class="rounded-lg p-1.5 text-[#64748b] transition hover:bg-[#fef2f2] hover:text-[#dc2626]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        
        {{-- PAGINATION --}}
        <div class="border-t border-[#e2e8f0] px-6 py-4">
            <nav class="flex items-center justify-between">
                {{-- Left Side: Info --}}
                <div class="flex items-center gap-4 text-[13px] text-[#64748b]">
                    <div class="flex items-center gap-2">
                        <span>Tampilkan</span>
                        <select class="h-9 rounded-[10px] border border-[#e2e8f0] bg-white px-3 font-bold text-[#0f172a] outline-none transition focus:border-[#3b82f6]">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                        <span>data</span>
                    </div>
                    <span class="text-[#cbd5e1]">•</span>
                    <div>
                        Menampilkan 
                        <span class="font-bold text-[#0f172a]">1-7</span> 
                        dari 
                        <span class="font-bold text-[#0f172a]" x-text="kelas.length"></span>
                    </div>
                </div>

                {{-- Right Side: Navigation Buttons --}}
                <div class="flex items-center gap-1">
                    <button class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#cbd5e1] cursor-not-allowed">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m11 17-5-5 5-5m7 10-5-5 5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </button>
                    <button class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#cbd5e1] cursor-not-allowed">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </button>
                    <span class="flex h-9 w-9 items-center justify-center rounded-[10px] bg-[#3b82f6] text-[13px] font-black text-white shadow-lg shadow-blue-500/30">1</span>
                    <button class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#cbd5e1] cursor-not-allowed">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </button>
                    <button class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#cbd5e1] cursor-not-allowed">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m13 17 5-5-5-5M6 17l5-5-5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </button>
                </div>
            </nav>
        </div>
    </div>

    {{-- ═══ MODAL: Tambah Kelas ═══ --}}
    <x-modal alpineShow="showTambah" title="Tambah Kelas Baru" maxWidth="md">
        <div class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Kelas</label><input x-model="form.nama" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="XII IPA 2"></div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kapasitas</label><input x-model="form.kapasitas" type="number" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"></div>
            </div>
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Wali Kelas</label><select x-model="form.wali" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"><option value="" disabled selected>-- Pilih Guru --</option><template x-for="g in daftarGuru" :key="g"><option :value="g" x-text="g"></option></template></select></div>
        </div>
        <x-slot:footer>
            <button @click="showTambah = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
            <button @click="addKelas()" :disabled="!form.nama" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white disabled:opacity-40">Tambah Kelas</button>
        </x-slot:footer>
    </x-modal>

    {{-- ═══ MODAL: Edit Kelas ═══ --}}
    <x-modal alpineShow="showEdit" title="Edit Data Kelas" maxWidth="md">
        <div class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Kelas</label><input x-model="editData.nama" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"></div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kapasitas</label><input x-model="editData.kapasitas" type="number" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"></div>
            </div>
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Wali Kelas</label><select x-model="editData.wali" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"><option value="">-- Pilih Guru --</option><template x-for="g in daftarGuru" :key="g"><option :value="g" x-text="g"></option></template></select></div>
        </div>
        <x-slot:footer>
            <button @click="showEdit = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
            <button @click="submitEdit()" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Simpan</button>
        </x-slot:footer>
    </x-modal>

    {{-- ═══ MODAL: Hapus Kelas ═══ --}}
    <x-confirm-dialog
        alpineShow="showDelete"
        type="danger"
        title="Hapus Kelas?"
        message="Kelas <strong x-text='deleteTarget?.nama'></strong> akan dihapus permanen beserta semua data terkait."
        confirmText="Ya, Hapus"
        confirmAction="doDelete()"
    />

</div>
@endsection
