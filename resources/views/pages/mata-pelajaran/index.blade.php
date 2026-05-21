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
            form: { kode_mapel: '', nama_mapel: '' },
            mapel: @json($mapels),
            get filtered() {
                if (!this.search) return this.mapel;
                let s = this.search.toLowerCase();
                return this.mapel.filter(m => m.nama_mapel.toLowerCase().includes(s) || m.kode_mapel.toLowerCase().includes(s));
            },
            submitAdd() { 
                document.getElementById('formTambahMapel').submit();
            },
            openEdit(m) { 
                this.editData = JSON.parse(JSON.stringify(m)); 
                this.showEdit = true; 
            },
            submitEdit() { 
                document.getElementById('formEditMapel').action = '/mata-pelajaran/' + this.editData.kode_mapel;
                document.getElementById('editNamaMapel').value = this.editData.nama_mapel;
                document.getElementById('formEditMapel').submit();
            },
            openDelete(m) {
                this.deleteData = m;
                this.showDelete = true;
            },
            confirmDelete() {
                document.getElementById('formHapusMapel').action = '/mata-pelajaran/' + this.deleteData.kode_mapel;
                document.getElementById('formHapusMapel').submit();
            }
        }));
    });
</script>

{{-- Hidden forms for server submission --}}
<form id="formTambahMapel" method="POST" action="{{ route('mata-pelajaran.store') }}" class="hidden" x-data>
    @csrf
    <input type="hidden" name="kode_mapel" x-bind:value="$store?.mapelForm?.kode ?? document.querySelector('[x-model=\'form.kode_mapel\']')?.value">
    <input type="hidden" name="nama_mapel" x-bind:value="$store?.mapelForm?.nama ?? document.querySelector('[x-model=\'form.nama_mapel\']')?.value">
</form>
<form id="formEditMapel" method="POST" action="" class="hidden">
    @csrf @method('PUT')
    <input type="hidden" name="nama_mapel" id="editNamaMapel">
</form>
<form id="formHapusMapel" method="POST" action="" class="hidden">
    @csrf @method('DELETE')
</form>

<div x-data="mapelData" class="space-y-6">

    {{-- HEADING --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div><h1 class="text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Mata Pelajaran</h1><p class="mt-2 text-[14px] leading-[1.8] text-[#475569]">Kelola daftar mata pelajaran yang diajarkan.</p></div>
        <div class="flex items-center gap-2">
            <button @click="showAdd = true" class="flex h-[42px] items-center gap-2 rounded-[8px] bg-[#1d4ed8] px-5 text-[13px] font-bold text-white transition hover:bg-[#1e40af]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>Tambah Mapel</button>
        </div>
    </div>

    @if(session('success'))
    <div class="rounded-[10px] border border-[#a7f3d0] bg-[#ecfdf5] px-4 py-3 text-[13px] font-semibold text-[#059669]">
        {{ session('success') }}
    </div>
    @endif

    {{-- SEARCH --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[240px]"><svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"></circle><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"></path></svg><input x-model="search" class="h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-white pl-10 pr-4 text-[13px] placeholder-[#94a3b8] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Cari kode atau nama mapel..."></div>
    </div>

    {{-- TABLE --}}
    <div class="overflow-hidden rounded-[14px] border border-[#e2e8f0] bg-white">
        <table class="w-full text-[13px]">
            <thead><tr class="border-b border-[#e2e8f0] bg-[#f8fafc]"><th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kode Mapel</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Mata Pelajaran</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Aksi</th></tr></thead>
            <tbody>
                <template x-for="m in filtered" :key="m.kode_mapel">
                    <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                        <td class="px-6 py-4"><span class="rounded bg-[#f1f5f9] px-2 py-1 font-mono text-[11px] font-semibold text-[#0f172a]" x-text="m.kode_mapel"></span></td>
                        <td class="px-4 py-4"><p class="font-bold text-[#0f172a]" x-text="m.nama_mapel"></p></td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="openEdit(m)" class="rounded-lg p-1.5 text-[#64748b] transition hover:bg-[#eff6ff] hover:text-[#1d4ed8]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"></path></svg></button>
                                <button @click="openDelete(m)" class="rounded-lg p-1.5 text-[#64748b] transition hover:bg-[#fef2f2] hover:text-[#dc2626]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                            </div>
                        </td>
                    </tr>
                </template>
                <tr x-show="filtered.length === 0"><td colspan="3" class="py-12 text-center text-[14px] text-[#94a3b8]">Tidak ada data mapel ditemukan.</td></tr>
            </tbody>
        </table>
        <div class="flex items-center justify-between border-t border-[#e2e8f0] px-6 py-3">
            <span class="text-[12px] font-bold text-[#64748b]">Total Mata Pelajaran</span>
            <span class="text-[18px] font-black text-[#0f172a]" x-text="mapel.length"></span>
        </div>
    </div>

    {{-- ═══ MODAL: Tambah Mapel ═══ --}}
    <x-modal alpineShow="showAdd" title="Tambah Mata Pelajaran" maxWidth="sm">
        <form method="POST" action="{{ route('mata-pelajaran.store') }}">
            @csrf
            <div class="space-y-4">
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kode Mapel</label><input name="kode_mapel" x-model="form.kode_mapel" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]" placeholder="Contoh: BIN"></div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Mata Pelajaran</label><input name="nama_mapel" x-model="form.nama_mapel" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]" placeholder="Contoh: Bahasa Indonesia"></div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="button" @click="showAdd = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
                <button type="submit" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Tambah</button>
            </div>
        </form>
    </x-modal>

    {{-- ═══ MODAL: Edit Mapel ═══ --}}
    <x-modal alpineShow="showEdit" title="Edit Mata Pelajaran" maxWidth="sm">
        <div class="space-y-4">
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kode Mapel (readonly)</label><input x-model="editData.kode_mapel" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#e2e8f0] px-4 text-[14px] text-[#475569] outline-none" readonly></div>
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Mata Pelajaran</label><input x-model="editData.nama_mapel" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"></div>
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
        message="Apakah Anda yakin ingin menghapus <strong x-text='deleteData.nama_mapel'></strong> dari daftar sistem?"
        confirmText="Ya, Hapus"
        confirmAction="confirmDelete()"
    />

</div>
@endsection
