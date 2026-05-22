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
            form: { nama_kelas: '', tahun_ajaran_id: '' },
            tahunAjarans: @json($tahunAjarans),
            kelas: @json($kelas),
            currentPage: 1,
            perPage: 10,
            get totalSiswa() { return this.kelas.reduce((a,k) => a + k.terisi, 0); },
            get totalPages() { return Math.ceil(this.kelas.length / this.perPage) || 1; },
            get paginatedKelas() {
                const start = (this.currentPage - 1) * this.perPage;
                return this.kelas.slice(start, start + Number(this.perPage));
            },
            get pageNumbers() {
                const total = this.totalPages;
                const current = Math.min(this.currentPage, total);
                const start = Math.max(1, Math.min(current - 2, total - 4));
                const end = Math.min(total, start + 4);
                return Array.from({ length: end - start + 1 }, (_, i) => start + i);
            },
            goToPage(page) { if (page >= 1 && page <= this.totalPages) this.currentPage = page; },
            resetPage() { this.currentPage = 1; },
            addKelas() {
                // Client-side preview (submit via form untuk persist ke DB)
                let ta = this.tahunAjarans.find(t => t.id == this.form.tahun_ajaran_id);
                this.kelas.push({
                    id: Date.now(),
                    nama: this.form.nama_kelas,
                    tahun_ajaran_id: this.form.tahun_ajaran_id,
                    tahun_ajaran: ta ? ta.tahun_mulai+'/'+ta.tahun_selesai+' - '+ta.semester : '-',
                    terisi: 0,
                });
                // Submit form ke server
                document.getElementById('formTambahKelas').submit();
            },
            openEdit(k) { this.editData = JSON.parse(JSON.stringify(k)); this.showEdit = true; },
            submitEdit() {
                document.getElementById('formEditKelas').action = '/kelas/' + this.editData.id;
                document.getElementById('editNamaKelas').value = this.editData.nama;
                document.getElementById('editTahunAjaranId').value = this.editData.tahun_ajaran_id;
                document.getElementById('formEditKelas').submit();
            },
            confirmDelete(k) { this.deleteTarget = k; this.showDelete = true; },
            doDelete() {
                document.getElementById('formHapusKelas').action = '/kelas/' + this.deleteTarget.id;
                document.getElementById('formHapusKelas').submit();
            },
        }));
    });
</script>

{{-- Hidden forms for server submission --}}
<form id="formTambahKelas" method="POST" action="{{ route('kelas.store') }}" class="hidden">
    @csrf
    <input type="hidden" name="nama_kelas" x-bind:value="form.nama_kelas">
    <input type="hidden" name="tahun_ajaran_id" x-bind:value="form.tahun_ajaran_id">
</form>
<form id="formEditKelas" method="POST" action="" class="hidden">
    @csrf @method('PUT')
    <input type="hidden" name="nama_kelas" id="editNamaKelas">
    <input type="hidden" name="tahun_ajaran_id" id="editTahunAjaranId">
</form>
<form id="formHapusKelas" method="POST" action="" class="hidden">
    @csrf @method('DELETE')
</form>

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
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-3">
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Total Kelas</p>
            <p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]" x-text="kelas.length"></p>
        </div>
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Total Siswa</p>
            <p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#0f172a]" x-text="totalSiswa"></p>
        </div>
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Kelas Kosong</p>
            <p class="mt-2 text-[36px] font-black leading-none tracking-[-0.06em] text-[#dc2626]" x-text="kelas.filter(k => k.terisi === 0).length"></p>
        </div>
    </div>

    @if(session('success'))
    <div class="rounded-[10px] border border-[#a7f3d0] bg-[#ecfdf5] px-4 py-3 text-[13px] font-semibold text-[#059669]">
        {{ session('success') }}
    </div>
    @endif

    {{-- TABLE --}}
    <div class="overflow-hidden rounded-[14px] border border-[#e2e8f0] bg-white">
        <table class="w-full text-[13px]">
            <thead>
                <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                    <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">No</th>
                    <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Kelas</th>
                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Tahun Ajaran</th>
                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Jumlah Siswa</th>
                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(k, index) in paginatedKelas" :key="k.id">
                    <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                        <td class="px-6 py-4 font-semibold text-[#64748b]" x-text="((currentPage - 1) * perPage) + index + 1"></td>
                        <td class="px-6 py-4">
                            <p class="font-black text-[15px] tracking-[-0.02em] text-[#0f172a]" x-text="k.nama"></p>
                        </td>
                        <td class="px-4 py-4">
                            <span class="rounded bg-[#eff6ff] px-2.5 py-1 text-[11px] font-bold text-[#1d4ed8]" x-text="k.tahun_ajaran"></span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-[14px] font-bold text-[#0f172a]" x-text="k.terisi + ' siswa'"></span>
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
                <tr x-show="kelas.length === 0">
                    <td colspan="5" class="py-12 text-center text-[14px] text-[#94a3b8]">Belum ada data kelas.</td>
                </tr>
            </tbody>
        </table>
        <div class="border-t border-[#e2e8f0] px-6 py-4">
            <nav class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-wrap items-center gap-4 text-[13px] text-[#64748b]">
                    <div class="flex items-center gap-2">
                        <span>Tampilkan</span>
                        <select x-model.number="perPage" @change="resetPage()" class="h-9 rounded-[10px] border border-[#e2e8f0] bg-white px-3 font-bold text-[#0f172a] outline-none transition focus:border-[#3b82f6]">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span>data</span>
                    </div>
                    <span class="hidden text-[#cbd5e1] sm:inline">&bull;</span>
                    <div>Menampilkan <span class="font-bold text-[#0f172a]" x-text="kelas.length ? (((currentPage - 1) * perPage) + 1) + '-' + (((currentPage - 1) * perPage) + paginatedKelas.length) : '0'"></span> dari <span class="font-bold text-[#0f172a]" x-text="kelas.length"></span></div>
                </div>
                <div class="flex items-center gap-1">
                    <button @click="goToPage(1)" :disabled="currentPage <= 1" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#64748b] transition hover:bg-[#f1f5f9] disabled:cursor-not-allowed disabled:text-[#cbd5e1] disabled:hover:bg-transparent"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m11 17-5-5 5-5m7 10-5-5 5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                    <button @click="goToPage(currentPage - 1)" :disabled="currentPage <= 1" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#64748b] transition hover:bg-[#f1f5f9] disabled:cursor-not-allowed disabled:text-[#cbd5e1] disabled:hover:bg-transparent"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                    <template x-for="p in pageNumbers" :key="p"><button @click="goToPage(p)" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[13px] font-bold transition" :class="currentPage === p ? 'bg-[#3b82f6] text-white shadow-lg shadow-blue-500/30' : 'text-[#64748b] hover:bg-[#f1f5f9]'" x-text="p"></button></template>
                    <button @click="goToPage(currentPage + 1)" :disabled="currentPage >= totalPages" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#64748b] transition hover:bg-[#f1f5f9] disabled:cursor-not-allowed disabled:text-[#cbd5e1] disabled:hover:bg-transparent"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                    <button @click="goToPage(totalPages)" :disabled="currentPage >= totalPages" class="flex h-9 w-9 items-center justify-center rounded-[10px] text-[#64748b] transition hover:bg-[#f1f5f9] disabled:cursor-not-allowed disabled:text-[#cbd5e1] disabled:hover:bg-transparent"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m13 17 5-5-5-5M6 17l5-5-5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                </div>
            </nav>
        </div>
    </div>

    {{-- ═══ MODAL: Tambah Kelas ═══ --}}
    <x-modal alpineShow="showTambah" title="Tambah Kelas Baru" maxWidth="md">
        <div class="space-y-4">
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Kelas</label>
                <input x-model="form.nama_kelas" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Contoh: 6-A">
            </div>
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Tahun Ajaran</label>
                <select x-model="form.tahun_ajaran_id" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                    <option value="" disabled selected>-- Pilih Tahun Ajaran --</option>
                    <template x-for="ta in tahunAjarans" :key="ta.id">
                        <option :value="ta.id" x-text="ta.tahun_mulai+'/'+ta.tahun_selesai+' - '+ta.semester"></option>
                    </template>
                </select>
            </div>
        </div>
        <x-slot:footer>
            <button @click="showTambah = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
            <button @click="addKelas()" :disabled="!form.nama_kelas || !form.tahun_ajaran_id" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white disabled:opacity-40">Tambah Kelas</button>
        </x-slot:footer>
    </x-modal>

    {{-- ═══ MODAL: Edit Kelas ═══ --}}
    <x-modal alpineShow="showEdit" title="Edit Data Kelas" maxWidth="md">
        <div class="space-y-4">
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Kelas</label>
                <input x-model="editData.nama" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
            </div>
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Tahun Ajaran</label>
                <select x-model="editData.tahun_ajaran_id" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                    <template x-for="ta in tahunAjarans" :key="ta.id">
                        <option :value="ta.id" x-text="ta.tahun_mulai+'/'+ta.tahun_selesai+' - '+ta.semester"></option>
                    </template>
                </select>
            </div>
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
