@extends('layouts.app')
@section('title', 'Aturan Nilai')
@section('subtitle', 'Pengaturan sistem penilaian')
@section('active', 'aturan-nilai')

@section('content')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('aturanNilaiData', () => ({
            showHapus: false,
            showRiwayat: false,
            showHapusSikap: false,
            showHapusEskul: false,
            hapusTarget: null,
            hapusSikapTarget: null,
            hapusEskulTarget: null,
            pembulatan: 'Terdekat',

            komponen: [
                { id: 2, nama: 'Ulangan Harian (UH)', bobot: 50, kode: 'UH' },
                { id: 3, nama: 'Ujian Tengah Semester', bobot: 25, kode: 'UTS' },
                { id: 4, nama: 'Ujian Akhir Semester', bobot: 25, kode: 'UAS' },
            ],

<<<<<<< HEAD
            aspekSikap: [
                { id: 1, nama: 'Sikap Spiritual', predikat: ['A','B','C','D'], deskripsi: 'Ketaatan beribadah, berperilaku syukur, dan berdoa' },
                { id: 2, nama: 'Sikap Sosial', predikat: ['A','B','C','D'], deskripsi: 'Jujur, disiplin, tanggung jawab, dan kerja sama' },
            ],
=======
    nilaiSikap: [
        { id: 1, nama: 'Sikap Spiritual', predikat: ['A','B','C','D'], deskripsi: 'Ketaatan beribadah, berperilaku syukur, dan berdoa' },
        { id: 2, nama: 'Sikap Sosial', predikat: ['A','B','C','D'], deskripsi: 'Jujur, disiplin, tanggung jawab, dan kerja sama' },
    ],
>>>>>>> 05cfb8e46ad37c4063010a87a97b58a7498ae4c3

            eskul: [
                { id: 1, nama: 'Pramuka', wajib: true },
                { id: 2, nama: 'Seni Tari', wajib: false },
                { id: 3, nama: 'Karate', wajib: false },
            ],

            get totalBobot() { return this.komponen.reduce((s, k) => s + Number(k.bobot), 0); },
            get isValid() { return this.totalBobot === 100; },
            get previewNilai() {
                let sample = {TH: 85, UH: 78, PTS: 80, PAS: 90};
                let raw = this.komponen.reduce((s, k) => s + (sample[k.kode]||80) * (Number(k.bobot)/100), 0);
                if (this.pembulatan === 'Ke Atas') return Math.ceil(raw);
                if (this.pembulatan === 'Ke Bawah') return Math.floor(raw);
                return Math.round(raw);
            },

            addKomponen() { this.komponen.push({id:Date.now(), nama:'Komponen Baru', bobot:0, kode:'KB'}); },
            confirmHapus(k) { this.hapusTarget = k; this.showHapus = true; },
            doHapus() { this.komponen = this.komponen.filter(k => k.id !== this.hapusTarget.id); this.showHapus = false; this.$dispatch('toast',{message:'Komponen berhasil dihapus',type:'error'}); },

<<<<<<< HEAD
            addAspekSikap() { this.aspekSikap.push({ id: Date.now(), nama: 'Aspek Sikap Baru', predikat: ['A','B','C','D'], deskripsi: '' }); },
            confirmHapusSikap(a) { this.hapusSikapTarget = a; this.showHapusSikap = true; },
            doHapusSikap() { this.aspekSikap = this.aspekSikap.filter(a => a.id !== this.hapusSikapTarget.id); this.showHapusSikap = false; this.$dispatch('toast',{message:'Aspek sikap dihapus',type:'error'}); },
=======
    addNilaiSikap() { this.nilaiSikap.push({ id: Date.now(), nama: 'Nilai Sikap Baru', predikat: ['A','B','C','D'], deskripsi: '' }); },
    confirmHapusNilaiSikap(a) { this.hapusSikapTarget = a; this.showHapusSikap = true; },
    doHapusNilaiSikap() { this.nilaiSikap = this.nilaiSikap.filter(a => a.id !== this.hapusSikapTarget.id); this.showHapusSikap = false; $dispatch('toast',{message:'Nilai sikap dihapus',type:'error'}); },
>>>>>>> 05cfb8e46ad37c4063010a87a97b58a7498ae4c3

            addEskul() { this.eskul.push({ id: Date.now(), nama: 'Kegiatan Baru', wajib: false }); },
            confirmHapusEskul(e) { this.hapusEskulTarget = e; this.showHapusEskul = true; },
            doHapusEskul() { this.eskul = this.eskul.filter(e => e.id !== this.hapusEskulTarget.id); this.showHapusEskul = false; this.$dispatch('toast',{message:'Ekstrakurikuler dihapus',type:'error'}); },
        }));
    });
</script>

<div x-data="aturanNilaiData" class="space-y-6">

    {{-- HEADING --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div><h1 class="text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Aturan Nilai</h1><p class="mt-2 max-w-[480px] text-[14px] leading-[1.8] text-[#475569]">Konfigurasi bobot penilaian, aturan pembulatan, dan KKM untuk setiap mata pelajaran.</p></div>
        <button @click="$dispatch('toast',{message:'Konfigurasi bobot berhasil disimpan!',type:'success'})" :disabled="!isValid" class="flex h-[44px] items-center gap-2 rounded-[8px] px-5 text-[13px] font-bold text-white transition" :class="isValid ? 'bg-[#1d4ed8] hover:bg-[#1e40af]' : 'bg-[#94a3b8] cursor-not-allowed'">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            Simpan Konfigurasi
        </button>
    </div>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
        {{-- LEFT: Komponen Bobot --}}
        <div class="space-y-6">
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white overflow-hidden">
                <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4">
                    <h3 class="text-[16px] font-bold text-[#0f172a]">Komponen Penilaian & Bobot</h3>
                    <button @click="addKomponen()" class="flex h-[34px] items-center gap-1.5 rounded-[6px] bg-[#1d4ed8] px-3 text-[11px] font-bold text-white hover:bg-[#1e40af]"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>Tambah</button>
                </div>
                <table class="w-full text-[13px]">
                    <thead><tr class="bg-[#f8fafc]"><th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Komponen</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b] w-[120px]">Bobot (%)</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Bar</th><th class="px-4 py-3 w-12"></th></tr></thead>
                    <tbody>
                        <template x-for="k in komponen" :key="k.id">
                            <tr class="border-t border-[#f1f5f9]">
                                <td class="px-6 py-3"><input x-model="k.nama" class="w-full bg-transparent text-[14px] font-bold text-[#0f172a] outline-none border-b border-transparent focus:border-[#3b82f6] pb-0.5"></td>
                                <td class="px-4 py-3"><input x-model.number="k.bobot" type="number" min="0" max="100" class="h-[36px] w-[80px] rounded-[6px] border border-[#e2e8f0] bg-[#f8fafc] px-3 text-center text-[14px] font-bold outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"></td>
                                <td class="px-4 py-3"><div class="h-[6px] w-full overflow-hidden rounded-full bg-[#e2e8f0]"><div class="h-full rounded-full transition-all duration-300" :class="totalBobot > 100 ? 'bg-[#dc2626]' : 'bg-[#1d4ed8]'" :style="'width:'+Math.min(k.bobot,100)+'%'"></div></div></td>
                                <td class="px-4 py-3"><button @click="confirmHapus(k)" class="rounded-lg p-1.5 text-[#94a3b8] transition hover:bg-[#fef2f2] hover:text-[#dc2626]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <div class="flex items-center justify-between border-t border-[#e2e8f0] px-6 py-3">
                    <span class="text-[12px] font-bold text-[#64748b]">Total Bobot</span>
                    <span class="text-[20px] font-black" :class="isValid ? 'text-[#059669]' : 'text-[#dc2626]'" x-text="totalBobot + '%'"></span>
                </div>
                <div x-show="!isValid" class="bg-[#fef2f2] border-t border-[#fecaca] px-6 py-2.5">
                    <p class="text-[12px] font-bold text-[#dc2626]" x-text="totalBobot > 100 ? 'Total bobot melebihi 100%! Kurangi bobot.' : 'Total bobot kurang dari 100%! Tambahkan bobot.'"></p>
                </div>
            </div>

            {{-- PEMBULATAN --}}
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <h3 class="text-[16px] font-bold text-[#0f172a]">Aturan Pembulatan</h3>
                <div class="mt-4 flex flex-wrap gap-3">
                    <template x-for="opt in ['Terdekat', 'Ke Atas', 'Ke Bawah']">
                        <label class="flex cursor-pointer items-center gap-2 rounded-[8px] border px-4 py-2.5 text-[13px] font-semibold transition" :class="pembulatan === opt ? 'bg-[#0f172a] text-white border-[#0f172a]' : 'border-[#e2e8f0] text-[#475569] hover:bg-[#f1f5f9]'">
                            <input type="radio" :value="opt" x-model="pembulatan" class="hidden"><span x-text="opt"></span>
                        </label>
                    </template>
                </div>
            </div>

            {{-- KKM --}}
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <h3 class="text-[16px] font-bold text-[#0f172a]">Kriteria Ketuntasan Minimal (KKM)</h3>
                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">KKM Pengetahuan</label><input class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-bold text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="75"></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">KKM Keterampilan</label><input class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-bold text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="75"></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Predikat Minimum</label><input class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-bold text-[#0f172a] outline-none" value="C" readonly></div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Preview + Riwayat --}}
        <div class="space-y-4">
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Preview Nilai Akhir</p>
                <p class="mt-3 text-center text-[64px] font-black tracking-[-0.06em]" :class="previewNilai >= 75 ? 'text-[#059669]' : 'text-[#dc2626]'" x-text="previewNilai"></p>
                <p class="text-center text-[12px] font-semibold text-[#64748b]">Pembulatan: <span class="text-[#0f172a]" x-text="pembulatan"></span></p>
                <div class="mt-4 space-y-2">
                    <template x-for="k in komponen" :key="k.id">
                        <div class="flex items-center justify-between text-[12px]"><span class="text-[#64748b]" x-text="k.nama"></span><span class="font-bold text-[#0f172a]" x-text="k.bobot + '%'"></span></div>
                    </template>
                </div>
                <div class="mt-4 flex items-center justify-center gap-2">
                    <span class="inline-flex rounded-md border px-2.5 py-1 text-[11px] font-bold" :class="previewNilai >= 75 ? 'border-[#a7f3d0] bg-[#ecfdf5] text-[#059669]' : 'border-[#fecaca] bg-[#fef2f2] text-[#dc2626]'" x-text="previewNilai >= 75 ? 'TUNTAS' : 'BELUM TUNTAS'"></span>
                </div>
            </div>
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Riwayat Perubahan</p>
                <div class="mt-4 space-y-3 divide-y divide-[#f1f5f9]">
                    @foreach ([['date'=>'12 Jan 2024','desc'=>'Bobot PAS diubah 35% → 30%','by'=>'Admin TU'],['date'=>'05 Des 2023','desc'=>'Tambah komponen Tugas Harian','by'=>'Admin TU']] as $r)
                        <div class="pt-3 first:pt-0"><p class="text-[12px] font-bold text-[#0f172a]">{{ $r['desc'] }}</p><p class="mt-0.5 text-[10px] text-[#94a3b8]">{{ $r['date'] }} · {{ $r['by'] }}</p></div>
                    @endforeach
                </div>
                <button @click="showRiwayat = true" class="mt-4 text-[11px] font-bold uppercase tracking-[0.08em] text-[#1d4ed8] hover:text-[#1e40af]">Lihat Riwayat Lengkap</button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- SECTION: Nilai Sikap                                 --}}
    {{-- Sesuai format rapor: Sikap Spiritual, Sikap Sosial, dll    --}}
    {{-- Masing-masing punya predikat (A/B/C/D) + deskripsi narasi --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="rounded-[14px] border border-[#e2e8f0] bg-white overflow-hidden">
        <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4">
            <div>
                <h3 class="text-[16px] font-bold text-[#0f172a]">Nilai Sikap</h3>
                <p class="mt-0.5 text-[12px] text-[#64748b]">Daftar kategori penilaian sikap di rapor. Wali Kelas akan mengisi predikat & deskripsi narasi per siswa.</p>
            </div>
            <button @click="addNilaiSikap()" class="flex h-[34px] items-center gap-1.5 rounded-[6px] bg-[#1d4ed8] px-3 text-[11px] font-bold text-white hover:bg-[#1e40af]">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>
                Tambah Nilai Sikap
            </button>
        </div>

        {{-- Empty state --}}
        <template x-if="nilaiSikap.length === 0">
            <div class="flex flex-col items-center justify-center py-10 text-[#94a3b8]">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="mt-2 text-[13px] font-semibold">Belum ada nilai sikap. Klik "Tambah Nilai Sikap" untuk menambahkan.</p>
            </div>
        </template>

        <div class="divide-y divide-[#f1f5f9]">
            <template x-for="(a, idx) in nilaiSikap" :key="a.id">
                <div class="flex flex-col gap-3 px-6 py-4 transition hover:bg-[#fafbfc]">
                    <div class="flex items-center gap-4">
                        {{-- Nomor urut --}}
                        <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-[#f1f5f9] text-[11px] font-black text-[#475569]" x-text="idx + 1"></span>
                        {{-- Input nama nilai sikap --}}
                        <input
                            x-model="a.nama"
                            class="flex-1 rounded-[6px] border border-transparent bg-transparent px-2 py-1.5 text-[14px] font-bold text-[#0f172a] outline-none transition hover:border-[#e2e8f0] focus:border-[#3b82f6] focus:bg-[#f8fafc] focus:ring-2 focus:ring-[#3b82f6]/20"
                            placeholder="Nama nilai sikap..."
                        >
                        {{-- Badge predikat --}}
                        <div class="hidden sm:flex items-center gap-1.5">
                            <template x-for="p in a.predikat" :key="p">
                                <span class="flex h-7 w-7 items-center justify-center rounded-md border border-[#e2e8f0] bg-[#f8fafc] text-[11px] font-black text-[#475569]" x-text="p"></span>
                            </template>
                        </div>
                        {{-- Hapus --}}
                        <button @click="confirmHapusSikap(a)" class="rounded-lg p-1.5 text-[#94a3b8] transition hover:bg-[#fef2f2] hover:text-[#dc2626]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </button>
                    </div>
                    {{-- Deskripsi nilai --}}
                    <div class="ml-11">
                        <input
                            x-model="a.deskripsi"
                            class="w-full rounded-[6px] border border-[#e2e8f0] bg-[#f8fafc] px-3 py-2 text-[12px] text-[#64748b] outline-none transition focus:border-[#3b82f6] focus:bg-white focus:ring-2 focus:ring-[#3b82f6]/20 focus:text-[#0f172a]"
                            placeholder="Deskripsi nilai (contoh: Ketaatan beribadah, berperilaku syukur...)" 
                        >
                        <p class="mt-1.5 text-[10px] text-[#94a3b8] italic">Wali Kelas akan mengisi predikat (A/B/C/D) + narasi deskriptif per siswa di rapor.</p>
                    </div>
                </div>
            </template>
        </div>

        <div class="flex items-center justify-between border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-3">
            <span class="text-[12px] font-bold text-[#64748b]">Total Nilai Sikap</span>
            <span class="text-[18px] font-black text-[#0f172a]" x-text="nilaiSikap.length + ' Nilai'"></span>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- SECTION: Kegiatan Ekstrakurikuler                          --}}
    {{-- Sesuai format rapor: Nama kegiatan + Keterangan (narasi)   --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="rounded-[14px] border border-[#e2e8f0] bg-white overflow-hidden">
        <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4">
            <div>
                <h3 class="text-[16px] font-bold text-[#0f172a]">Kegiatan Ekstrakurikuler</h3>
                <p class="mt-0.5 text-[12px] text-[#64748b]">Daftar kegiatan eskul yang tersedia di sekolah. Wali Kelas akan mengisi keterangan per siswa di rapor.</p>
            </div>
            <button @click="addEskul()" class="flex h-[34px] items-center gap-1.5 rounded-[6px] bg-[#1d4ed8] px-3 text-[11px] font-bold text-white hover:bg-[#1e40af]">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>
                Tambah Eskul
            </button>
        </div>

        {{-- Header kolom --}}
        <div class="grid grid-cols-[auto_1fr_auto_auto] items-center gap-4 border-b border-[#f1f5f9] bg-[#f8fafc] px-6 py-2.5">
            <span class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">#</span>
            <span class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Kegiatan</span>
            <span class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Jenis</span>
            <span></span>
        </div>

        {{-- Empty state --}}
        <template x-if="eskul.length === 0">
            <div class="flex flex-col items-center justify-center py-10 text-[#94a3b8]">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <p class="mt-2 text-[13px] font-semibold">Belum ada ekstrakurikuler. Klik "Tambah Eskul" untuk menambahkan.</p>
            </div>
        </template>

        <div class="divide-y divide-[#f1f5f9]">
            <template x-for="(e, idx) in eskul" :key="e.id">
                <div class="grid grid-cols-[auto_1fr_auto_auto] items-center gap-4 px-6 py-3">
                    {{-- Nomor urut --}}
                    <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-[#f1f5f9] text-[11px] font-black text-[#475569]" x-text="idx + 1"></span>
                    {{-- Input nama eskul --}}
                    <input
                        x-model="e.nama"
                        class="rounded-[6px] border border-transparent bg-transparent px-2 py-1.5 text-[14px] font-semibold text-[#0f172a] outline-none transition hover:border-[#e2e8f0] focus:border-[#3b82f6] focus:bg-[#f8fafc] focus:ring-2 focus:ring-[#3b82f6]/20"
                        placeholder="Nama kegiatan eskul..."
                    >
                    {{-- Toggle wajib/pilihan --}}
                    <label class="flex cursor-pointer items-center gap-2">
                        <div
                            @click="e.wajib = !e.wajib"
                            class="relative h-5 w-9 rounded-full transition-colors duration-200"
                            :class="e.wajib ? 'bg-[#1d4ed8]' : 'bg-[#cbd5e1]'"
                        >
                            <div
                                class="absolute top-0.5 h-4 w-4 rounded-full bg-white shadow transition-transform duration-200"
                                :class="e.wajib ? 'translate-x-4' : 'translate-x-0.5'"
                            ></div>
                        </div>
                        <span class="text-[11px] font-bold" :class="e.wajib ? 'text-[#1d4ed8]' : 'text-[#94a3b8]'" x-text="e.wajib ? 'Wajib' : 'Pilihan'"></span>
                    </label>
                    {{-- Hapus --}}
                    <button @click="confirmHapusEskul(e)" class="rounded-lg p-1.5 text-[#94a3b8] transition hover:bg-[#fef2f2] hover:text-[#dc2626]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </button>
                </div>
            </template>
        </div>

        <div class="flex items-center justify-between border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-3">
            <div>
                <span class="text-[12px] font-bold text-[#64748b]">Total Eskul</span>
                <span class="ml-2 text-[10px] text-[#94a3b8] italic">Wali Kelas mengisi kolom "Keterangan" per siswa di rapor</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-[11px] font-semibold text-[#64748b]" x-text="eskul.filter(e => e.wajib).length + ' Wajib'"></span>
                <span class="text-[#e2e8f0]">·</span>
                <span class="text-[11px] font-semibold text-[#64748b]" x-text="eskul.filter(e => !e.wajib).length + ' Pilihan'"></span>
                <span class="text-[18px] font-black text-[#0f172a]" x-text="eskul.length + ' Total'"></span>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Hapus Komponen ═══ --}}
    <x-confirm-dialog
        alpineShow="showHapus"
        type="danger"
        title="Hapus Komponen?"
        message="Komponen '<strong x-text='hapusTarget?.nama'></strong>' akan dihapus."
        confirmText="Ya, Hapus"
        confirmAction="doHapus()"
    />

    {{-- ═══ MODAL: Hapus Aspek Sikap ═══ --}}
    <x-confirm-dialog
        alpineShow="showHapusSikap"
        type="danger"
        title="Hapus Aspek Sikap?"
        message="Aspek '<strong x-text='hapusSikapTarget?.nama'></strong>' akan dihapus dari konfigurasi rapor."
        confirmText="Ya, Hapus"
        confirmAction="doHapusSikap()"
    />

    {{-- ═══ MODAL: Hapus Eskul ═══ --}}
    <x-confirm-dialog
        alpineShow="showHapusEskul"
        type="danger"
        title="Hapus Ekstrakurikuler?"
        message="'<strong x-text='hapusEskulTarget?.nama'></strong>' akan dihapus dari daftar eskul."
        confirmText="Ya, Hapus"
        confirmAction="doHapusEskul()"
    />

    {{-- ═══ MODAL: Riwayat Lengkap ═══ --}}
    <x-modal alpineShow="showRiwayat" title="Riwayat Perubahan Lengkap" maxWidth="lg">
        <div class="overflow-y-auto px-6 py-4 divide-y divide-[#f1f5f9] -mx-6 -my-5">
            @foreach ([['date'=>'12 Jan 2024','desc'=>'Bobot PAS diubah 35% → 30%','by'=>'Admin TU'],['date'=>'05 Des 2023','desc'=>'Tambah komponen Tugas Harian (20%)','by'=>'Admin TU'],['date'=>'10 Nov 2023','desc'=>'KKM diubah 70 → 75','by'=>'Kepsek'],['date'=>'01 Sep 2023','desc'=>'Pembulatan diubah ke Terdekat','by'=>'Admin TU'],['date'=>'15 Jul 2023','desc'=>'Konfigurasi awal semester ganjil','by'=>'System']] as $r)
                <div class="py-3 first:pt-0 last:pb-0"><p class="text-[13px] font-bold text-[#0f172a]">{{ $r['desc'] }}</p><p class="mt-0.5 text-[11px] text-[#94a3b8]">{{ $r['date'] }} · {{ $r['by'] }}</p></div>
            @endforeach
        </div>
    </x-modal>

</div>
@endsection
