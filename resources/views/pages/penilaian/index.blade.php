@extends('layouts.app')
@section('title', 'Penilaian')
@section('subtitle', 'Input dan kelola nilai')
@section('active', 'penilaian')

@section('content')

    @if($role === 'guru')
        {{-- Konten Guru: Penilaian Mata Pelajaran --}}
<style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
        .sticky-actions {
            box-shadow: 0 -4px 12px -4px rgba(0, 0, 0, 0.05);
        }
    </style>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('penilaianGuruData', () => ({
            searchQuery: '',
            filterKelas: '{{ $selectedKelasId }}',
            filterMapel: '{{ $selectedMapelId }}',
            filterTahunAjaran: '{{ $selectedTahunAjaranId }}',

            // Modal State
            saveDraftModalOpen: false,
            finalizeModalOpen: false,

            grades: @json($grades),

            kelasList: @json($kelasList),
            mapelList: @json($mapelList),
            tahunAjarans: @json($tahunAjarans),

            // Automatically calculate NA (Nilai Akhir)
            // Weight: UH 50%, UTS 25%, UAS 25%
            calculateNA(g) {
                const uh = Number(g.uh) || 0;
                const uts = Number(g.uts) || 0;
                const uas = Number(g.uas) || 0;

                if (uh === 0 && uts === 0 && uas === 0) return '-';

                return ((uh * 0.5) + (uts * 0.25) + (uas * 0.25)).toFixed(1);
            },

            get filteredGrades() {
                let result = this.grades;

                if (this.searchQuery) {
                    const q = this.searchQuery.toLowerCase();
                    result = result.filter(g => g.nama.toLowerCase().includes(q) || g.nis.includes(q));
                }
                return result;
            },

            get classAverage() {
                const gradesWithNA = this.filteredGrades.filter(g => this.calculateNA(g) !== '-');
                if (gradesWithNA.length === 0) return '0.0';

                const sum = gradesWithNA.reduce((acc, g) => acc + Number(this.calculateNA(g)), 0);
                return (sum / gradesWithNA.length).toFixed(1);
            },

            get draftCount() {
                return this.filteredGrades.filter(g => g.status !== 'final').length;
            },

            get finalCount() {
                return this.filteredGrades.filter(g => g.status === 'final').length;
            },

            // Navigate to reload with selected filters
            applyFilter() {
                const params = new URLSearchParams({
                    kelas_id: this.filterKelas,
                    mapel_id: this.filterMapel,
                    tahun_ajaran_id: this.filterTahunAjaran,
                });
                window.location.href = '{{ route("penilaian") }}?' + params.toString();
            },

            // Actions
            saveDraft() {
                this.saveDraftModalOpen = true;
            },

            confirmSaveDraft() {
                this.submitBatch('draft');
            },

            finalize() {
                this.finalizeModalOpen = true;
            },

            confirmFinalize() {
                this.submitBatch('final');
            },

            submitBatch(action) {
                const form = document.getElementById('batchForm');
                document.getElementById('batchAction').value = action;
                document.getElementById('batchKelasId').value = this.filterKelas;
                document.getElementById('batchMapelId').value = this.filterMapel;
                document.getElementById('batchTahunAjaranId').value = this.filterTahunAjaran;

                // Hapus input grades lama
                form.querySelectorAll('.grade-input').forEach(el => el.remove());

                // Masukkan grades dari Alpine state
                this.grades.forEach((g, i) => {
                    const addInput = (name, value) => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `grades[${i}][${name}]`;
                        input.value = value ?? '';
                        input.classList.add('grade-input');
                        form.appendChild(input);
                    };
                    addInput('siswa_id', g.siswa_id);
                    addInput('uh', g.uh);
                    addInput('uts', g.uts);
                    addInput('uas', g.uas);
                });

                form.submit();
            }
        }));
    });
</script>

{{-- Hidden batch form --}}
<form id="batchForm" method="POST" action="{{ route('nilai.store-batch') }}" class="hidden">
    @csrf
    <input type="hidden" name="action" id="batchAction">
    <input type="hidden" name="kelas_id" id="batchKelasId">
    <input type="hidden" name="mapel_id" id="batchMapelId">
    <input type="hidden" name="tahun_ajaran_id" id="batchTahunAjaranId">
</form>

<div class="space-y-6 pb-24" x-data="penilaianGuruData">
        {{-- Title + Info --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a] sm:text-[36px]">Input Nilai Siswa</h1>
                <p class="mt-1 text-[14px] text-[#475569]">Mata Pelajaran: <strong>{{ $selectedMapel->nama_mapel ?? 'Belum dipilih' }}</strong></p>
            </div>
        </div>

        @if(session('success'))
        <div class="rounded-[10px] border border-[#a7f3d0] bg-[#ecfdf5] px-4 py-3 text-[13px] font-semibold text-[#059669]">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="rounded-[10px] border border-[#fecaca] bg-[#fef2f2] px-4 py-3 text-[13px] font-semibold text-[#dc2626]">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        {{-- Filters & Stats --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            {{-- Filter Kelas --}}
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Pilih Kelas</p>
                <div class="mt-4">
                    <select x-model="filterKelas" @change="applyFilter()" class="h-10 w-full rounded-lg border border-[#e2e8f0] bg-[#f8fafc] px-3 text-[14px] font-bold text-[#0f172a] outline-none transition focus:border-[#3b82f6] focus:bg-white focus:ring-2 focus:ring-[#3b82f6]/20">
                        <template x-for="k in kelasList" :key="k.id">
                            <option :value="k.id" :selected="k.id == filterKelas" x-text="k.nama_kelas"></option>
                        </template>
                    </select>
                </div>
            </div>

            {{-- Filter Mapel --}}
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Mata Pelajaran</p>
                <div class="mt-4">
                    <select x-model="filterMapel" @change="applyFilter()" class="h-10 w-full rounded-lg border border-[#e2e8f0] bg-[#f8fafc] px-3 text-[14px] font-bold text-[#0f172a] outline-none transition focus:border-[#3b82f6] focus:bg-white focus:ring-2 focus:ring-[#3b82f6]/20">
                        <template x-for="m in mapelList" :key="m.kode_mapel">
                            <option :value="m.kode_mapel" :selected="m.kode_mapel == filterMapel" x-text="m.nama_mapel"></option>
                        </template>
                    </select>
                </div>
            </div>

            {{-- Filter Tahun Ajaran --}}
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Tahun Ajaran</p>
                <div class="mt-4">
                    <select x-model="filterTahunAjaran" @change="applyFilter()" class="h-10 w-full rounded-lg border border-[#e2e8f0] bg-[#f8fafc] px-3 text-[14px] font-bold text-[#0f172a] outline-none transition focus:border-[#3b82f6] focus:bg-white focus:ring-2 focus:ring-[#3b82f6]/20">
                        <template x-for="ta in tahunAjarans" :key="ta.id">
                            <option :value="ta.id" :selected="ta.id == filterTahunAjaran" x-text="ta.tahun_mulai + '/' + ta.tahun_selesai + ' - ' + ta.semester"></option>
                        </template>
                    </select>
                </div>
            </div>

            {{-- Info Total Data --}}
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0] flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Siswa Ditampilkan</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#1d4ed8]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
                <div class="mt-2 flex items-end gap-2">
                    <span class="text-[36px] font-black leading-none text-[#0f172a]" x-text="filteredGrades.length"></span>
                    <span class="pb-1 text-[13px] text-[#64748b]">Peserta</span>
                </div>
            </div>

            {{-- Rata-rata Kelas --}}
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0] flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Rata-rata Nilai Akhir</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#3b82f6]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m3 17 6-6 4 4 7-8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    </div>
                </div>
                <div class="mt-2 flex items-end gap-2">
                    <span class="text-[36px] font-black leading-none" :class="Number(classAverage) >= 75 ? 'text-[#0f172a]' : 'text-[#dc2626]'" x-text="classAverage"></span>
                    <span class="pb-1 text-[13px] text-[#64748b]">Poin</span>
                </div>
            </div>
        </div>

        {{-- Empty state: guru belum ada pengampu --}}
        @if($kelasList->isEmpty())
        <div class="rounded-xl bg-white p-12 ring-1 ring-[#e2e8f0] text-center">
            <svg class="mx-auto h-12 w-12 text-[#cbd5e1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <p class="mt-4 text-[16px] font-bold text-[#475569]">Belum ada data pengampu</p>
            <p class="mt-1 text-[13px] text-[#94a3b8]">Anda belum ditugaskan mengampu mata pelajaran di kelas manapun. Hubungi Admin TU untuk konfigurasi guru pengampu.</p>
        </div>
        @else

        {{-- Search Input --}}
        <div class="no-print relative">
            <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"/><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"/></svg>
            <input type="text" x-model="searchQuery" placeholder="Cari nama siswa..." class="h-11 w-full max-w-md rounded-lg border border-[#e2e8f0] bg-white pl-11 pr-4 text-[13px] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
        </div>

        {{-- Input Table --}}
        <div class="overflow-x-auto rounded-xl bg-white ring-1 ring-[#e2e8f0]">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                        <th class="sticky left-0 z-20 bg-[#f8fafc] border-r border-[#e2e8f0] px-5 py-4 text-left text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">No</th>
                        <th class="sticky left-[72px] z-10 bg-[#f8fafc] border-r border-[#e2e8f0] px-5 py-4 text-left text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Siswa (NIS)</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Nilai UH (50%)</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Nilai UTS (25%)</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Nilai UAS (25%)</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#0f172a] border-l border-[#e2e8f0]">Nilai Akhir</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Status Data</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(g, index) in filteredGrades" :key="g.siswa_id">
                        <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                            <td class="sticky left-0 z-20 bg-white border-r border-[#f1f5f9] px-5 py-3 font-semibold text-[#64748b] group-hover:bg-[#f8fafc]" x-text="index + 1"></td>
                            <td class="sticky left-[72px] z-10 bg-white border-r border-[#f1f5f9] px-5 py-3 group-hover:bg-[#f8fafc]">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center flex-shrink-0 rounded-md bg-[#1e40af] text-[10px] font-bold text-white" x-text="g.init"></div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-[#0f172a]" x-text="g.nama"></span>
                                        <span class="text-[11px] font-semibold text-[#64748b]" x-text="g.nis"></span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-3 py-3 text-center min-w-[100px]">
                                <input type="number" min="0" max="100" x-model="g.uh" :disabled="g.status === 'final'" class="h-10 w-full rounded-md border border-[#e2e8f0] bg-white px-2 py-1 text-center font-semibold text-[#0f172a] outline-none transition focus:border-[#3b82f6] disabled:bg-[#f8fafc] disabled:text-[#94a3b8]" placeholder="0-100">
                            </td>

                            <td class="px-3 py-3 text-center min-w-[100px]">
                                <input type="number" min="0" max="100" x-model="g.uts" :disabled="g.status === 'final'" class="h-10 w-full rounded-md border border-[#e2e8f0] bg-white px-2 py-1 text-center font-semibold text-[#0f172a] outline-none transition focus:border-[#3b82f6] disabled:bg-[#f8fafc] disabled:text-[#94a3b8]" placeholder="0-100">
                            </td>

                            <td class="px-3 py-3 text-center min-w-[100px]">
                                <input type="number" min="0" max="100" x-model="g.uas" :disabled="g.status === 'final'" class="h-10 w-full rounded-md border border-[#e2e8f0] bg-white px-2 py-1 text-center font-semibold text-[#0f172a] outline-none transition focus:border-[#3b82f6] disabled:bg-[#f8fafc] disabled:text-[#94a3b8]" placeholder="0-100">
                            </td>

                            <td class="px-5 py-3 text-center font-black text-[15px] border-l border-[#f1f5f9]" :class="calculateNA(g) >= 75 ? 'text-[#16a34a]' : (calculateNA(g) === '-' ? 'text-[#94a3b8]' : 'text-[#dc2626]')" x-text="calculateNA(g)"></td>

                            <td class="px-5 py-3 text-center">
                                <span x-show="g.status === 'belum'" class="rounded px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider bg-[#f1f5f9] text-[#64748b]">Belum Diisi</span>
                                <span x-show="g.status === 'draft'" class="rounded px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider bg-[#fff7ed] text-[#ea580c] ring-1 ring-inset ring-[#fdba74]">Draft</span>
                                <span x-show="g.status === 'final'" class="rounded px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider bg-[#f0fdf4] text-[#16a34a] ring-1 ring-inset ring-[#86efac]">Terkunci</span>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredGrades.length === 0">
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-[#64748b]">
                                <svg class="mx-auto h-10 w-10 text-[#cbd5e1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <p class="mt-3 text-[14px] font-bold">Tidak ada siswa di kelas ini.</p>
                                <p class="mt-1 text-[12px] text-[#94a3b8]">Pastikan ada siswa yang terdaftar di kelas yang dipilih.</p>
                            </td>
                        </tr>
                    </template>
                </tbody>

            </table>
        </div>

        {{-- Sticky Action Bottom Bar --}}
        <div class="sticky-actions no-print fixed bottom-0 left-0 lg:left-[240px] right-0 z-40 flex items-center justify-between border-t border-[#e2e8f0] bg-white/95 px-4 py-4 sm:px-6 lg:px-8 backdrop-blur" x-show="filteredGrades.length > 0">
            <div class="hidden md:block">
                <p class="text-[12px] text-[#64748b]">Terdapat <strong class="text-[#0f172a]" x-text="draftCount"></strong> data siswa yang belum difinalisasi.</p>
            </div>
            <div class="flex w-full md:w-auto items-center gap-3">
                <button @click="saveDraft()" class="flex-1 md:flex-none flex items-center justify-center gap-2 rounded-lg border border-[#e2e8f0] bg-white px-5 py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9] hover:text-[#0f172a]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Simpan Draft
                </button>
                <button @click="finalize()" class="flex-1 md:flex-none flex items-center justify-center gap-2 rounded-lg bg-[#1d4ed8] px-5 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#1e40af] shadow-[0_4px_12px_rgba(29,78,216,0.3)]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Finalisasi Nilai
                </button>
            </div>
        </div>

        {{-- ═══════ MODAL SIMPAN DRAFT ═══════ --}}
        <x-confirm-dialog
            alpineShow="saveDraftModalOpen"
            type="warning"
            title="Simpan Draft Sementara?"
            message="Nilai yang Anda masukkan akan disimpan sebagai draft. Anda masih dapat mengubahnya nanti."
            confirmText="Ya, Simpan Draft"
            confirmAction="confirmSaveDraft()"
        />

        {{-- ═══════ MODAL FINALISASI ═══════ --}}
        <x-confirm-dialog
            alpineShow="finalizeModalOpen"
            type="danger"
            title="Kunci & Finalisasi?"
            message="Setelah difinalisasi, formulir akan <strong class='text-[#0f172a]'>terkunci</strong> dan nilai akan diteruskan otomatis ke panel Wali Kelas. Apakah Anda yakin?"
            confirmText="Ya, Finalisasi"
            confirmAction="confirmFinalize()"
        />

        @endif {{-- end kelasList not empty --}}

    </div>
    @elseif($role === 'walikelas')
        {{-- Konten Walikelas: Penilaian Kelas --}}
<style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
    </style>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('penilaianWalikelasData', () => ({
            searchQuery: '',
            selectedMapel: '',
            filterStatus: '',
            filterOpen: false,
            currentPage: 1,
            perPage: 8,

            grades: @json($grades),
            mapelList: @json($mapelList ?? []),
            kelasList: @json($kelasList ?? []),

            // Map mapel codes ke field names for display
            mapelFields: {},

            init() {
                // Build mapel field mapping from grades data
                // Walikelas sees all subjects per student
            },

            get filteredGrades() {
                let result = [...this.grades];
                if (this.searchQuery) {
                    const q = this.searchQuery.toLowerCase();
                    result = result.filter(g => g.nama.toLowerCase().includes(q) || (g.nis && g.nis.includes(q)));
                }
                if (this.filterStatus) result = result.filter(g => g.status === this.filterStatus);
                return result;
            },

            get totalPages() {
                return Math.ceil(this.filteredGrades.length / this.perPage) || 1;
            },

            get paginatedGrades() {
                const start = (this.currentPage - 1) * this.perPage;
                return this.filteredGrades.slice(start, start + this.perPage);
            },

            get pageNumbers() {
                const pages = [];
                for (let i = 1; i <= this.totalPages; i++) pages.push(i);
                return pages;
            },

            get tuntasCount() {
                return this.grades.filter(g => Number(g.nilai_akhir || 0) >= 75).length;
            },

            get tuntasPercent() {
                if (this.grades.length === 0) return 0;
                return Math.round(this.tuntasCount / this.grades.length * 100);
            },

            get classAverage() {
                const validGrades = this.grades.filter(g => g.nilai_akhir != null);
                if (validGrades.length === 0) return '0.0';
                const sum = validGrades.reduce((acc, g) => acc + Number(g.nilai_akhir), 0);
                return (sum / validGrades.length).toFixed(1);
            },

            goToPage(p) {
                if (p >= 1 && p <= this.totalPages) this.currentPage = p;
            },

            resetFilters() {
                this.searchQuery = '';
                this.selectedMapel = '';
                this.filterStatus = '';
                this.currentPage = 1;
                this.filterOpen = false;
            }
        }));
    });
</script>

<div class="space-y-6" x-data="penilaianWalikelasData">
        {{-- Title + Actions --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a] sm:text-[36px]">Penilaian Kelas</h1>
                <p class="mt-1 text-[14px] text-[#475569]">Rekapitulasi capaian akademik siswa.</p>
            </div>
            <div class="flex items-center gap-2 no-print">
                <button @click="window.print()" class="flex items-center gap-2 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[11px] font-extrabold uppercase tracking-wider text-[#475569] transition hover:bg-[#f1f5f9]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Export PDF
                </button>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Total Siswa</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#1d4ed8]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
                <p class="mt-2"><span class="text-[36px] font-black leading-none text-[#0f172a]" x-text="grades.length"></span> <span class="text-[13px] text-[#64748b]">Peserta</span></p>
            </div>
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Rata-rata Kelas</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#3b82f6]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m3 17 6-6 4 4 7-8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    </div>
                </div>
                <p class="mt-2"><span class="text-[36px] font-black leading-none text-[#0f172a]" x-text="classAverage"></span> <span class="text-[13px] text-[#64748b]">Poin</span></p>
            </div>
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#3b82f6]/30">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Ketuntasan</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#f0fdf4] text-[#16a34a]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
                <p class="mt-2"><span class="text-[36px] font-black leading-none text-[#0f172a]" x-text="tuntasPercent + '%'"></span> <span class="text-[13px] text-[#64748b]">Tuntas</span></p>
            </div>
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Data Terisi</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#60a5fa]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2" stroke-width="2"/><path d="M16 3v4M8 3v4M3 10h18" stroke-linecap="round" stroke-width="2"/></svg>
                    </div>
                </div>
                <p class="mt-2"><span class="text-[36px] font-black leading-none text-[#0f172a]" x-text="grades.filter(g => g.status !== 'belum').length + '/' + grades.length"></span></p>
            </div>
        </div>

        {{-- Search + Filter --}}
        <div class="no-print flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"/><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"/></svg>
                <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Cari nama atau NIS/NISN siswa..." class="h-11 w-full rounded-lg border border-[#e2e8f0] bg-white pl-11 pr-4 text-[13px] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
            </div>
        </div>

        {{-- Grade Table --}}
        <div class="overflow-x-auto rounded-xl bg-white ring-1 ring-[#e2e8f0]">
            <table class="w-full text-[13px] whitespace-nowrap">
                <thead>
                    <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                        <th class="sticky left-0 z-20 bg-[#f8fafc] border-r border-[#e2e8f0] px-5 py-4 text-left text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b] min-w-[72px]">No</th>
                        <th class="sticky left-[72px] z-10 bg-[#f8fafc] border-r border-[#e2e8f0] px-5 py-4 text-left text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b] min-w-[200px]">Nama Siswa</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">UH (50%)</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">UTS (25%)</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">UAS (25%)</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#0f172a] border-l border-[#e2e8f0] min-w-[100px]">Nilai Akhir</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b] min-w-[120px]">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(g, index) in paginatedGrades" :key="g.siswa_id">
                        <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                            <td class="sticky left-0 z-20 bg-white border-r border-[#f1f5f9] px-5 py-4 font-semibold text-[#64748b] group-hover:bg-[#f8fafc]" x-text="((currentPage - 1) * perPage) + index + 1"></td>
                            <td class="sticky left-[72px] z-10 bg-white border-r border-[#f1f5f9] px-5 py-4 group-hover:bg-[#f8fafc]">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center flex-shrink-0 rounded-md bg-[#1e40af] text-[10px] font-bold text-white" x-text="g.init"></div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-[#0f172a]" x-text="g.nama"></span>
                                        <span class="text-[11px] font-semibold text-[#64748b]" x-text="g.nis"></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center" :class="Number(g.uh || 0) < 75 && g.uh != null ? 'text-[#dc2626] font-bold' : 'text-[#475569]'" x-text="g.uh ?? '-'"></td>
                            <td class="px-5 py-4 text-center" :class="Number(g.uts || 0) < 75 && g.uts != null ? 'text-[#dc2626] font-bold' : 'text-[#475569]'" x-text="g.uts ?? '-'"></td>
                            <td class="px-5 py-4 text-center" :class="Number(g.uas || 0) < 75 && g.uas != null ? 'text-[#dc2626] font-bold' : 'text-[#475569]'" x-text="g.uas ?? '-'"></td>
                            <td class="px-4 py-4 text-center font-black border-l border-[#f1f5f9]" :class="Number(g.nilai_akhir || 0) < 75 ? 'text-[#dc2626]' : 'text-[#0f172a]'" x-text="g.nilai_akhir ? Number(g.nilai_akhir).toFixed(1) : '-'"></td>
                            <td class="px-5 py-4 text-center">
                                <span x-show="g.status === 'belum' || !g.status" class="rounded px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider bg-[#f1f5f9] text-[#64748b]">Belum</span>
                                <span x-show="g.status === 'draft'" class="rounded px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider bg-[#fff7ed] text-[#ea580c]">Draft</span>
                                <span x-show="g.status === 'final'" class="rounded px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider bg-[#f0fdf4] text-[#16a34a]">Final</span>
                            </td>
                        </tr>
                    </template>
                    <template x-if="paginatedGrades.length === 0">
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <svg class="mx-auto h-10 w-10 text-[#cbd5e1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round"/></svg>
                                <p class="mt-3 text-[14px] font-bold text-[#64748b]">Tidak ada data ditemukan</p>
                                <button @click="resetFilters()" class="mt-4 rounded-lg border border-[#e2e8f0] px-4 py-2 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Reset Filter</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="no-print flex flex-col items-center justify-between gap-3 sm:flex-row">
            <p class="text-[11px] font-bold uppercase tracking-[0.1em] text-[#64748b]">
                Menampilkan <span x-text="paginatedGrades.length"></span> dari <span x-text="filteredGrades.length"></span> siswa
            </p>
            <div class="flex items-center gap-1" x-show="totalPages > 1">
                <button @click="goToPage(currentPage - 1)" :disabled="currentPage <= 1" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] transition hover:bg-[#f1f5f9] disabled:opacity-30 disabled:cursor-not-allowed">‹</button>
                <template x-for="p in pageNumbers" :key="p">
                    <button @click="goToPage(p)" :class="currentPage === p ? 'bg-[#1d4ed8] text-white' : 'text-[#64748b] hover:bg-[#f1f5f9]'" class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-bold transition" x-text="p"></button>
                </template>
                <button @click="goToPage(currentPage + 1)" :disabled="currentPage >= totalPages" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#64748b] transition hover:bg-[#f1f5f9] disabled:opacity-30 disabled:cursor-not-allowed">›</button>
            </div>
        </div>
    </div>
    @endif
@endsection
