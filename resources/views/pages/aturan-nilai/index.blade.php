@extends('layouts.app')
@section('title', 'Aturan Nilai')
@section('subtitle', 'Pengaturan sistem penilaian')
@section('active', 'aturan-nilai')

@section('content')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('aturanNilaiData', () => ({
            showTambah: false,
            showEdit: false,
            showHapus: false,
            showHapusSemua: false,
            showRiwayat: false,
            showHapusSikap: false,
            showHapusEskul: false,
            hapusTarget: null,
            hapusSikapTarget: null,
            hapusEskulTarget: null,
            kkm: {{ $defaultKkm ?? 70 }},

            komponen: @json($komponen),
            mapels: @json($mapels),

            formAdd: { nama_komponen: '', bobot: 0, mapel_id: '' },
            editData: { id: '', nama: '', bobot: 0, mapel_id: '' },

            nilaiSikap: @json($sikaps->map(fn ($item) => ['id' => $item->id, 'nama' => $item->nama_sikap])),
            eskul: @json($ekstrakurikulers->map(fn ($item) => ['id' => $item->id, 'nama' => $item->nama_eskul])),

            async persist() {
                // Master disimpan ke database agar langsung dipakai oleh seluruh wali kelas.
                const response = await fetch(@js(route('aturan-nilai.master-rapor')), {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @js(csrf_token()),
                    },
                    body: JSON.stringify({ sikaps: this.nilaiSikap, ekskuls: this.eskul }),
                });

                if (!response.ok) {
                    this.$dispatch('toast', { message: 'Nama harus diisi dan tidak boleh sama.', type: 'error' });
                    return;
                }

                const result = await response.json();
                this.nilaiSikap = result.sikaps;
                this.eskul = result.ekskuls;
                this.$dispatch('toast', { message: result.message, type: 'success' });
            },

            get groupedKomponen() {
                let groups = {};
                this.komponen.forEach(k => {
                    if (!groups[k.mapel]) groups[k.mapel] = { mapel: k.mapel, items: [], totalBobot: 0 };
                    groups[k.mapel].items.push(k);
                    groups[k.mapel].totalBobot += Number(k.bobot);
                });
                return Object.values(groups);
            },
            get isValid() { 
                if (this.komponen.length === 0) return true;
                return this.groupedKomponen.every(g => g.totalBobot === 100); 
            },
            get previewNilai() {
                if (this.groupedKomponen.length === 0) return 0;
                let firstGroup = this.groupedKomponen[0];
                if (firstGroup.totalBobot !== 100) return '-';
                let raw = firstGroup.items.reduce((s, k) => s + 80 * (Number(k.bobot)/100), 0);
                return Math.round(raw);
            },

            // ── Komponen CRUD (server-persisted) ──
            submitAdd() {
                // Cek batas 3 komponen per mapel
                const mapelCount = this.komponen.filter(k => k.mapel_id === this.formAdd.mapel_id).length;
                if (mapelCount >= 3) {
                    this.showTambah = false;
                    this.$dispatch('toast', {message: 'Maksimal 3 komponen per mata pelajaran.', type: 'error'});
                    return;
                }
                document.getElementById('addNamaKomponen').value = this.formAdd.nama_komponen;
                document.getElementById('addBobot').value = this.formAdd.bobot;
                document.getElementById('addMapelId').value = this.formAdd.mapel_id;
                document.getElementById('formTambahKomponen').submit();
            },
            openEdit(k) {
                this.editData = { id: k.id, nama: k.nama, bobot: k.bobot, mapel_id: k.mapel_id };
                this.showEdit = true;
            },
            submitEdit() {
                document.getElementById('formEditKomponen').action = "{{ url('aturan-nilai') }}/" + this.editData.id;
                document.getElementById('editNamaKomponen').value = this.editData.nama;
                document.getElementById('editBobot').value = this.editData.bobot;
                document.getElementById('editMapelId').value = this.editData.mapel_id;
                document.getElementById('formEditKomponen').submit();
            },
            confirmHapus(k) { this.hapusTarget = k; this.showHapus = true; },
            doHapus() {
                document.getElementById('formHapusKomponen').action = "{{ url('aturan-nilai') }}/" + this.hapusTarget.id;
                document.getElementById('formHapusKomponen').submit();
            },
            doHapusSemua() {
                document.getElementById('formHapusSemua').submit();
            },

            addNilaiSikap() { this.nilaiSikap.push({ id: null, nama: 'Nilai Sikap Baru' }); this.persist(); },
            confirmHapusSikap(a) { this.hapusSikapTarget = a; this.showHapusSikap = true; },
            doHapusSikap() { this.nilaiSikap = this.nilaiSikap.filter(a => a.id !== this.hapusSikapTarget.id); this.showHapusSikap = false; this.persist(); this.$dispatch('toast',{message:'Nilai sikap dihapus',type:'error'}); },

            addEskul() { this.eskul.push({ id: null, nama: 'Kegiatan Baru' }); this.persist(); },
            confirmHapusEskul(e) { this.hapusEskulTarget = e; this.showHapusEskul = true; },
            doHapusEskul() { this.eskul = this.eskul.filter(e => e.id !== this.hapusEskulTarget.id); this.showHapusEskul = false; this.persist(); this.$dispatch('toast',{message:'Ekstrakurikuler dihapus',type:'error'}); },
        }));
    });
</script>

{{-- Hidden forms for server submission --}}
<form id="formTambahKomponen" method="POST" action="{{ route('aturan-nilai.store') }}" class="hidden">
    @csrf
    <input type="hidden" name="nama_komponen" id="addNamaKomponen">
    <input type="hidden" name="bobot" id="addBobot">
    <input type="hidden" name="mapel_id" id="addMapelId">
</form>
<form id="formEditKomponen" method="POST" action="" class="hidden">
    @csrf @method('PUT')
    <input type="hidden" name="nama_komponen" id="editNamaKomponen">
    <input type="hidden" name="bobot" id="editBobot">
    <input type="hidden" name="mapel_id" id="editMapelId">
</form>
<form id="formHapusKomponen" method="POST" action="" class="hidden">
    @csrf @method('DELETE')
</form>
<form id="formHapusSemua" method="POST" action="{{ route('aturan-nilai.destroy-all') }}" class="hidden">
    @csrf @method('DELETE')
</form>

<div x-data="aturanNilaiData" class="space-y-6">

    {{-- HEADING --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div><h1 class="text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Aturan Nilai</h1><p class="mt-2 max-w-[480px] text-[14px] leading-[1.8] text-[#475569]">Konfigurasi bobot penilaian dan KKM untuk setiap mata pelajaran.</p></div>

    </div>

    @if(session('success'))
    <div class="rounded-[10px] border border-[#a7f3d0] bg-[#ecfdf5] px-4 py-3 text-[13px] font-semibold text-[#059669]">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="rounded-[10px] border border-[#fecaca] bg-[#fef2f2] px-4 py-3 text-[13px] font-semibold text-[#dc2626]">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
        {{-- LEFT: Komponen Bobot --}}
        <div class="space-y-6">
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white overflow-hidden">
                <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4">
                    <h3 class="text-[16px] font-bold text-[#0f172a]">Komponen Penilaian & Bobot</h3>
                    <div class="flex items-center gap-2">
                        <button @click="showHapusSemua = true" class="flex h-[34px] items-center gap-1.5 rounded-[6px] border border-[#e2e8f0] bg-white px-3 text-[11px] font-bold text-[#dc2626] transition hover:border-[#fecaca] hover:bg-[#fef2f2]"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>Hapus Semua</button>
                        <button @click="showTambah = true" class="flex h-[34px] items-center gap-1.5 rounded-[6px] bg-[#1d4ed8] px-3 text-[11px] font-bold text-white hover:bg-[#1e40af]"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>Tambah</button>
                    </div>
                </div>
                <table class="w-full text-[13px]">
                    <thead><tr class="bg-[#f8fafc]">
                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Komponen</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b] w-[100px]">Bobot (%)</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Bar</th>
                        <th class="px-4 py-3 w-20"></th>
                    </tr></thead>
                    <template x-for="group in groupedKomponen" :key="group.mapel">
                        <tbody class="border-t-[4px] border-[#f1f5f9]">
                            <tr class="bg-[#f8fafc]">
                                <td colspan="4" class="px-6 py-2.5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[12px] font-bold uppercase tracking-[0.12em] text-[#1d4ed8]" x-text="group.mapel"></span>
                                        <span class="text-[11px] font-black" :class="group.totalBobot === 100 ? 'text-[#059669]' : 'text-[#dc2626]'" x-text="'Total Bobot: ' + group.totalBobot + '%'"></span>
                                    </div>
                                </td>
                            </tr>
                            <template x-for="k in group.items" :key="k.id">
                                <tr class="border-t border-[#f1f5f9]">
                                    <td class="px-6 py-3 pl-8"><span class="text-[14px] font-bold text-[#0f172a]" x-text="k.nama"></span></td>
                                    <td class="px-4 py-3"><span class="text-[14px] font-black text-[#0f172a]" x-text="k.bobot + '%'"></span></td>
                                    <td class="px-4 py-3"><div class="h-[6px] w-full overflow-hidden rounded-full bg-[#e2e8f0]"><div class="h-full rounded-full transition-all duration-300" :class="group.totalBobot > 100 ? 'bg-[#dc2626]' : 'bg-[#1d4ed8]'" :style="'width:'+Math.min(k.bobot,100)+'%'"></div></div></td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-1">
                                            <button @click="openEdit(k)" class="rounded-lg p-1.5 text-[#94a3b8] transition hover:bg-[#eff6ff] hover:text-[#1d4ed8]" title="Edit"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"></path></svg></button>
                                            <button @click="confirmHapus(k)" class="rounded-lg p-1.5 text-[#94a3b8] transition hover:bg-[#fef2f2] hover:text-[#dc2626]" title="Hapus"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </template>
                    <tbody x-show="komponen.length === 0">
                        <tr>
                            <td colspan="4" class="py-12 text-center text-[14px] text-[#94a3b8]">Belum ada komponen penilaian.</td>
                        </tr>
                    </tbody>
                </table>
                <div x-show="!isValid && komponen.length > 0" class="bg-[#fef2f2] border-t border-[#fecaca] px-6 py-2.5">
                    <p class="text-[12px] font-bold text-[#dc2626]">Ada mapel dengan total bobot tidak sama dengan 100%. Silakan periksa kembali!</p>
                </div>
            </div>



        </div>

        {{-- RIGHT: Preview + Riwayat --}}
        <div class="space-y-4">
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Preview Nilai Akhir</p>
                <p class="mt-3 text-center text-[64px] font-black tracking-[-0.06em]" :class="previewNilai === '-' ? 'text-[#94a3b8]' : (previewNilai >= kkm ? 'text-[#059669]' : 'text-[#dc2626]')" x-text="previewNilai"></p>
                <div class="mt-4 space-y-2">
                    <template x-if="groupedKomponen.length > 0">
                        <template x-for="k in groupedKomponen[0].items" :key="k.id">
                            <div class="flex items-center justify-between text-[12px]"><span class="text-[#64748b]" x-text="k.nama"></span><span class="font-bold text-[#0f172a]" x-text="k.bobot + '%'"></span></div>
                        </template>
                    </template>
                </div>
                <div class="mt-4 flex items-center justify-center gap-2">
                    <span class="inline-flex rounded-md border px-2.5 py-1 text-[11px] font-bold" :class="previewNilai === '-' ? 'border-[#e2e8f0] bg-[#f8fafc] text-[#94a3b8]' : (previewNilai >= kkm ? 'border-[#a7f3d0] bg-[#ecfdf5] text-[#059669]' : 'border-[#fecaca] bg-[#fef2f2] text-[#dc2626]')" x-text="previewNilai === '-' ? 'BOBOT ≠ 100%' : (previewNilai >= kkm ? 'TUNTAS' : 'BELUM TUNTAS')"></span>
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
    {{-- SECTION: Nilai Sikap                                      --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- Master sikap dan ekskul ditampilkan berdampingan pada layar lebar. --}}
    <div class="grid grid-cols-1 items-start gap-6 xl:grid-cols-2">
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
                        <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-[#f1f5f9] text-[11px] font-black text-[#475569]" x-text="idx + 1"></span>
                        <input x-model="a.nama" @change="persist()" class="flex-1 rounded-[6px] border border-transparent bg-transparent px-2 py-1.5 text-[14px] font-bold text-[#0f172a] outline-none transition hover:border-[#e2e8f0] focus:border-[#3b82f6] focus:bg-[#f8fafc] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Nama nilai sikap...">
                        <button @click="confirmHapusSikap(a)" class="rounded-lg p-1.5 text-[#94a3b8] transition hover:bg-[#fef2f2] hover:text-[#dc2626]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </button>
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

        <div class="grid grid-cols-[auto_1fr_auto] items-center gap-4 border-b border-[#f1f5f9] bg-[#f8fafc] px-6 py-2.5">
            <span class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">#</span>
            <span class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Kegiatan</span>
            <span></span>
        </div>

        <template x-if="eskul.length === 0">
            <div class="flex flex-col items-center justify-center py-10 text-[#94a3b8]">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <p class="mt-2 text-[13px] font-semibold">Belum ada ekstrakurikuler. Klik "Tambah Eskul" untuk menambahkan.</p>
            </div>
        </template>

        <div class="divide-y divide-[#f1f5f9]">
            <template x-for="(e, idx) in eskul" :key="e.id">
                <div class="grid grid-cols-[auto_1fr_auto] items-center gap-4 px-6 py-3">
                    <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-[#f1f5f9] text-[11px] font-black text-[#475569]" x-text="idx + 1"></span>
                    <input x-model="e.nama" @change="persist()" class="rounded-[6px] border border-transparent bg-transparent px-2 py-1.5 text-[14px] font-semibold text-[#0f172a] outline-none transition hover:border-[#e2e8f0] focus:border-[#3b82f6] focus:bg-[#f8fafc] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Nama kegiatan eskul...">
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
                <span class="text-[#e2e8f0]">·</span>
                <span class="text-[18px] font-black text-[#0f172a]" x-text="eskul.length + ' Total'"></span>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Tambah Komponen ═══ --}}
    </div>

    <x-modal alpineShow="showTambah" title="Tambah Komponen Penilaian" maxWidth="md">
        <div class="space-y-4">
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Komponen</label>
                <input x-model="formAdd.nama_komponen" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Contoh: Ulangan Harian">
            </div>
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Bobot (%)</label>
                <input x-model.number="formAdd.bobot" type="number" min="0" max="100" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="0">
            </div>
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Mata Pelajaran</label>
                <select x-model="formAdd.mapel_id" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                    <option value="" disabled selected>-- Pilih Mapel --</option>
                    <template x-for="m in mapels" :key="m.kode_mapel">
                        <option :value="m.kode_mapel" x-text="m.kode_mapel + ' - ' + m.nama_mapel"></option>
                    </template>
                </select>
            </div>
        </div>
        <x-slot:footer>
            <button @click="showTambah = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
            <button @click="submitAdd()" :disabled="!formAdd.nama_komponen || !formAdd.mapel_id" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white disabled:opacity-40">Tambah</button>
        </x-slot:footer>
    </x-modal>

    {{-- ═══ MODAL: Edit Komponen ═══ --}}
    <x-modal alpineShow="showEdit" title="Edit Komponen Penilaian" maxWidth="md">
        <div class="space-y-4">
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Komponen</label>
                <input x-model="editData.nama" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
            </div>
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Bobot (%)</label>
                <input x-model.number="editData.bobot" type="number" min="0" max="100" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
            </div>
            <div>
                <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Mata Pelajaran</label>
                <select x-model="editData.mapel_id" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                    <template x-for="m in mapels" :key="m.kode_mapel">
                        <option :value="m.kode_mapel" x-text="m.kode_mapel + ' - ' + m.nama_mapel"></option>
                    </template>
                </select>
            </div>
        </div>
        <x-slot:footer>
            <button @click="showEdit = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
            <button @click="submitEdit()" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Simpan</button>
        </x-slot:footer>
    </x-modal>

    {{-- ═══ MODAL: Hapus Komponen ═══ --}}
    <x-confirm-dialog
        alpineShow="showHapus"
        type="danger"
        title="Hapus Komponen?"
        message="Komponen '<strong x-text='hapusTarget?.nama'></strong>' akan dihapus."
        confirmText="Ya, Hapus"
        confirmAction="doHapus()"
    />

    {{-- ═══ MODAL: Hapus Semua ═══ --}}
    <x-confirm-dialog
        alpineShow="showHapusSemua"
        type="danger"
        title="Hapus Semua Komponen?"
        message="Semua data komponen penilaian akan dihapus secara permanen. Anda yakin?"
        confirmText="Ya, Hapus Semua"
        confirmAction="doHapusSemua()"
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
            @foreach ([['date'=>'12 Jan 2024','desc'=>'Bobot PAS diubah 35% → 30%','by'=>'Admin TU'],['date'=>'05 Des 2023','desc'=>'Tambah komponen Tugas Harian (20%)','by'=>'Admin TU'],['date'=>'10 Nov 2023','desc'=>'KKM diubah 70 → 75','by'=>'Kepsek'],['date'=>'15 Jul 2023','desc'=>'Konfigurasi awal semester ganjil','by'=>'System']] as $r)
                <div class="py-3 first:pt-0 last:pb-0"><p class="text-[13px] font-bold text-[#0f172a]">{{ $r['desc'] }}</p><p class="mt-0.5 text-[11px] text-[#94a3b8]">{{ $r['date'] }} · {{ $r['by'] }}</p></div>
            @endforeach
        </div>
    </x-modal>

</div>
@endsection
