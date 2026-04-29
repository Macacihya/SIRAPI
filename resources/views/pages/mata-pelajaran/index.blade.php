@extends('layouts.app')
@section('title', 'Mata Pelajaran')
@section('subtitle', 'Data mata pelajaran')
@section('active', 'mata-pelajaran')

@section('content')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('mapelData', () => ({
            search: '',
            showAdd: false,
            showEdit: false,
            showDelete: false,    
            editData: {},
            deleteData: {},
            form: { kode: '', nama: '', kelompok: 'Wajib' },
            mapel: [
                { kode: 'MP01', nama: 'Pendidikan Agama', kelompok: 'Wajib' },
                { kode: 'MP02', nama: 'Pendidikan Pancasila', kelompok: 'Wajib' },
                { kode: 'MP03', nama: 'Bahasa Indonesia', kelompok: 'Wajib' },
                { kode: 'MP04', nama: 'Matematika', kelompok: 'Wajib' },
                { kode: 'MP05', nama: 'IPAS', kelompok: 'Wajib' },
                { kode: 'MP06', nama: 'PJOK', kelompok: 'Wajib' },
                { kode: 'MP07', nama: 'Seni Budaya', kelompok: 'Pilihan' },
            ],
            get filtered() {
                if (!this.search) return this.mapel;
                let s = this.search.toLowerCase();
                return this.mapel.filter(m => m.nama.toLowerCase().includes(s) || m.kode.toLowerCase().includes(s));
            },
            submitAdd() { 
                this.mapel.push({ kode: this.form.kode, nama: this.form.nama, kelompok: this.form.kelompok }); 
                this.form = { kode: '', nama: '', kelompok: 'Wajib' }; 
                this.showAdd = false; 
                this.$dispatch('toast', { message: 'Mata pelajaran berhasil ditambahkan!', type: 'success' }); 
            },
            openEdit(m) { 
                this.editData = JSON.parse(JSON.stringify(m)); 
                this.showEdit = true; 
            },
            submitEdit() { 
                let idx = this.mapel.findIndex(m => m.kode === this.editData.kode); 
                if (idx > -1) { 
                    this.mapel[idx] = JSON.parse(JSON.stringify(this.editData)); 
                } 
                this.showEdit = false; 
                this.$dispatch('toast', { message: 'Mata pelajaran berhasil diperbarui!', type: 'success' }); 
            },
            openDelete(m) {
                this.deleteData = m;
                this.showDelete = true;
            },
            confirmDelete() {
                this.mapel = this.mapel.filter(m => m.kode !== this.deleteData.kode);
                this.showDelete = false;
                this.$dispatch('toast', { message: 'Mata pelajaran berhasil dihapus!', type: 'success' }); 
            }
        }));
    });
</script>

<div x-data="mapelData" class="space-y-6">

    {{-- HEADING --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div><h1 class="text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Mata Pelajaran</h1><p class="mt-2 text-[14px] leading-[1.8] text-[#475569]">Kelola daftar mata pelajaran yang diajarkan.</p></div>
        <div class="flex items-center gap-2">
            <button @click="showAdd = true" class="flex h-[42px] items-center gap-2 rounded-[8px] bg-[#1d4ed8] px-5 text-[13px] font-bold text-white transition hover:bg-[#1e40af]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>Tambah Mapel</button>
        </div>
    </div>

    {{-- SEARCH --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[240px]"><svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"></circle><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"></path></svg><input x-model="search" class="h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-white pl-10 pr-4 text-[13px] placeholder-[#94a3b8] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Cari kode atau nama mapel..."></div>
    </div>

    {{-- TABLE --}}
    <div class="overflow-hidden rounded-[14px] border border-[#e2e8f0] bg-white">
        <table class="w-full text-[13px]">
            <thead><tr class="border-b border-[#e2e8f0] bg-[#f8fafc]"><th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kode Mapel</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Mata Pelajaran</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kelompok</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Aksi</th></tr></thead>
            <tbody>
                <template x-for="m in filtered" :key="m.kode">
                    <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                        <td class="px-6 py-4"><span class="rounded bg-[#f1f5f9] px-2 py-1 font-mono text-[11px] font-semibold text-[#0f172a]" x-text="m.kode"></span></td>
                        <td class="px-4 py-4"><p class="font-bold text-[#0f172a]" x-text="m.nama"></p></td>
                        <td class="px-4 py-4"><span class="rounded bg-[#eff6ff] px-2.5 py-1 text-[11px] font-bold text-[#1d4ed8]" x-text="m.kelompok"></span></td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="openEdit(m)" class="rounded-lg p-1.5 text-[#64748b] transition hover:bg-[#eff6ff] hover:text-[#1d4ed8]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"></path></svg></button>
                                <button @click="openDelete(m)" class="rounded-lg p-1.5 text-[#64748b] transition hover:bg-[#fef2f2] hover:text-[#dc2626]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                            </div>
                        </td>
                    </tr>
                </template>
                <tr x-show="filtered.length === 0"><td colspan="4" class="py-12 text-center text-[14px] text-[#94a3b8]">Tidak ada data mapel ditemukan.</td></tr>
            </tbody>
        </table>
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
                        <span class="font-bold text-[#0f172a]" x-text="filtered.length"></span> 
                        dari 
                        <span class="font-bold text-[#0f172a]" x-text="mapel.length"></span>
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

    {{-- ═══ MODAL: Tambah Mapel ═══ --}}
    {{-- ═══ MODAL: Tambah Mapel ═══ --}}
    <x-modal alpineShow="showAdd" title="Tambah Mata Pelajaran" maxWidth="sm">
        <div class="space-y-4">
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kode Mapel</label><input x-model="form.kode" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]" placeholder="Contoh: MP08"></div>
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Mata Pelajaran</label><input x-model="form.nama" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]" placeholder="Contoh: Seni Rupa"></div>
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kelompok</label><select x-model="form.kelompok" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"><option>Wajib</option><option>Pilihan</option><option>Muatan Lokal</option></select></div>
        </div>
        <x-slot:footer>
            <button @click="showAdd = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
            <button @click="submitAdd()" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Tambah</button>
        </x-slot:footer>
    </x-modal>

    {{-- ═══ MODAL: Edit Mapel ═══ --}}
    <x-modal alpineShow="showEdit" title="Edit Mata Pelajaran" maxWidth="sm">
        <div class="space-y-4">
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kode Mapel (readonly)</label><input x-model="editData.kode" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#e2e8f0] px-4 text-[14px] text-[#475569] outline-none" readonly></div>
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Mata Pelajaran</label><input x-model="editData.nama" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"></div>
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kelompok</label><select x-model="editData.kelompok" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"><option>Wajib</option><option>Pilihan</option><option>Muatan Lokal</option></select></div>
        </div>
        <x-slot:footer>
            <button @click="showEdit = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
            <button @click="submitEdit()" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Simpan</button>
        </x-slot:footer>
    </x-modal>

    {{-- ═══ MODAL: Hapus Mapel ═══ --}}
    <x-confirm-dialog
        alpineShow="showDelete"
        type="danger"
        title="Hapus Mata Pelajaran?"
        message="Apakah Anda yakin ingin menghapus <strong x-text='deleteData.nama'></strong> dari daftar sistem?"
        confirmText="Ya, Hapus"
        confirmAction="confirmDelete()"
    />

</div>
@endsection
