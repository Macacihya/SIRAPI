<x-guru-shell
    :user="auth()->user()"
    active="penilaian"
    title="Penilaian Siswa"
    subtitle="Panel Guru Mata Pelajaran"
>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
        
    </style>

    <div class="space-y-6 pb-12" x-data="{
        searchQuery: '',
        filterKelas: 'VI-A',
        filterSemester: 'Ganjil',
        
        // Modal State
        saveDraftModalOpen: false,
        finalizeModalOpen: false,

        grades: [
            { id: 1, kelas: 'VI-A', nis: '12001', init: 'AA', nama: 'Achmad Albar', uh: 85, uts: 88, uas: 90, status: 'Draft' },
            { id: 2, kelas: 'VI-A', nis: '12002', init: 'BM', nama: 'Bella Monica', uh: 90, uts: 92, uas: 88, status: 'Final' },
            { id: 3, kelas: 'VI-A', nis: '12003', init: 'DP', nama: 'Dandi Pratama', uh: 70, uts: 75, uas: 72, status: 'Draft' },
            { id: 4, kelas: 'VI-A', nis: '12004', init: 'EK', nama: 'Endah Kartika', uh: null, uts: null, uas: null, status: 'Belum' },
            { id: 5, kelas: 'I-A', nis: '01011', init: 'AB', nama: 'Andi Budiman', uh: 80, uts: 82, uas: 85, status: 'Final' },
            { id: 6, kelas: 'I-A', nis: '01012', init: 'SA', nama: 'Siti Aminah', uh: 95, uts: 90, uas: 92, status: 'Draft' },
        ],

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
            
            if (this.filterKelas) {
                result = result.filter(g => g.kelas === this.filterKelas);
            }
            
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
        
        get hasUnsavedChanges() {
            // Simplified check based on status
            return this.filteredGrades.some(g => g.status === 'Belum');
        },

        // Actions
        saveDraft() {
            this.saveDraftModalOpen = true;
        },
        
        confirmSaveDraft() {
            this.filteredGrades.forEach(g => {
                if(g.status === 'Belum') g.status = 'Draft';
            });
            this.saveDraftModalOpen = false;
        },

        finalize() {
            this.finalizeModalOpen = true;
        },
        
        confirmFinalize() {
            this.filteredGrades.forEach(g => {
                g.status = 'Final';
            });
            this.finalizeModalOpen = false;
        }
    }">
        {{-- Title + Info --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a] sm:text-[36px]">Input Nilai Siswa</h1>
                <p class="mt-1 text-[14px] text-[#475569]">Mata Pelajaran: <strong>Bahasa Indonesia</strong></p>
            </div>
        </div>

        {{-- Filters & Stats --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Filter Kelas --}}
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Pilih Kelas</p>
                <div class="mt-4">
                    <select x-model="filterKelas" class="h-10 w-full rounded-lg border border-[#e2e8f0] bg-[#f8fafc] px-3 text-[14px] font-bold text-[#0f172a] outline-none transition focus:border-[#3b82f6] focus:bg-white focus:ring-2 focus:ring-[#3b82f6]/20">
                        <option value="I-A">Kelas I-A</option>
                        <option value="VI-A">Kelas VI-A</option>
                        <option value="VI-B">Kelas VI-B</option>
                    </select>
                </div>
            </div>

            {{-- Filter Semester --}}
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Pilih Semester</p>
                <div class="mt-4">
                    <select x-model="filterSemester" class="h-10 w-full rounded-lg border border-[#e2e8f0] bg-[#f8fafc] px-3 text-[14px] font-bold text-[#0f172a] outline-none transition focus:border-[#3b82f6] focus:bg-white focus:ring-2 focus:ring-[#3b82f6]/20">
                        <option value="Ganjil">Ganjil (2026/2027)</option>
                        <option value="Genap">Genap (2026/2027)</option>
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
                        <th class="sticky left-0 z-10 bg-[#f8fafc] border-r border-[#e2e8f0] px-5 py-4 text-left text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Siswa (NIS)</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Nilai UH (50%)</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Nilai UTS (25%)</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Nilai UAS (25%)</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#0f172a] border-l border-[#e2e8f0]">Nilai Akhir</th>
                        <th class="px-5 py-4 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Status Data</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="g in filteredGrades" :key="g.id">
                        <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                            <td class="sticky left-0 z-10 bg-white border-r border-[#f1f5f9] px-5 py-3 group-hover:bg-[#f8fafc]">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center flex-shrink-0 rounded-md bg-[#1e40af] text-[10px] font-bold text-white" x-text="g.init"></div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-[#0f172a]" x-text="g.nama"></span>
                                        <span class="text-[11px] font-semibold text-[#64748b]" x-text="g.nis"></span>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-3 py-3 text-center min-w-[100px]">
                                <input type="number" min="0" max="100" x-model="g.uh" :disabled="g.status === 'Final'" class="h-10 w-full rounded-md border border-[#e2e8f0] bg-white px-2 py-1 text-center font-semibold text-[#0f172a] outline-none transition focus:border-[#3b82f6] disabled:bg-[#f8fafc] disabled:text-[#94a3b8]" placeholder="0-100">
                            </td>
                            
                            <td class="px-3 py-3 text-center min-w-[100px]">
                                <input type="number" min="0" max="100" x-model="g.uts" :disabled="g.status === 'Final'" class="h-10 w-full rounded-md border border-[#e2e8f0] bg-white px-2 py-1 text-center font-semibold text-[#0f172a] outline-none transition focus:border-[#3b82f6] disabled:bg-[#f8fafc] disabled:text-[#94a3b8]" placeholder="0-100">
                            </td>
                            
                            <td class="px-3 py-3 text-center min-w-[100px]">
                                <input type="number" min="0" max="100" x-model="g.uas" :disabled="g.status === 'Final'" class="h-10 w-full rounded-md border border-[#e2e8f0] bg-white px-2 py-1 text-center font-semibold text-[#0f172a] outline-none transition focus:border-[#3b82f6] disabled:bg-[#f8fafc] disabled:text-[#94a3b8]" placeholder="0-100">
                            </td>
                            
                            <td class="px-5 py-3 text-center font-black text-[15px] border-l border-[#f1f5f9]" :class="calculateNA(g) >= 75 ? 'text-[#16a34a]' : (calculateNA(g) === '-' ? 'text-[#94a3b8]' : 'text-[#dc2626]')" x-text="calculateNA(g)"></td>
                            
                            <td class="px-5 py-3 text-center">
                                <span x-show="g.status === 'Belum'" class="rounded px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider bg-[#f1f5f9] text-[#64748b]">Belum Diisi</span>
                                <span x-show="g.status === 'Draft'" class="rounded px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider bg-[#fff7ed] text-[#ea580c] ring-1 ring-inset ring-[#fdba74]">Draft</span>
                                <span x-show="g.status === 'Final'" class="rounded px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider bg-[#f0fdf4] text-[#16a34a] ring-1 ring-inset ring-[#86efac]">Terkunci</span>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredGrades.length === 0">
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-[#64748b]">
                                Tidak ada siswa di kelas ini.
                            </td>
                        </tr>
                    </template>
                </tbody>
                {{-- Action Footer inside Table --}}
                <tfoot x-show="filteredGrades.length > 0" class="no-print border-t border-[#e2e8f0] bg-[#f8fafc]">
                    <tr>
                        <td colspan="6" class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="hidden sm:block">
                                    <p class="text-[12px] text-[#64748b]">Terdapat <strong class="text-[#0f172a]" x-text="filteredGrades.filter(g => g.status !== 'Final').length"></strong> data siswa yang belum difinalisasi.</p>
                                </div>
                                <div class="flex w-full sm:w-auto items-center gap-3">
                                    <button @click="saveDraft()" class="flex-1 sm:flex-none flex items-center justify-center gap-2 rounded-lg border border-[#e2e8f0] bg-white px-5 py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9] hover:text-[#0f172a]">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        Simpan Draft
                                    </button>
                                    <button @click="finalize()" class="flex-1 sm:flex-none flex items-center justify-center gap-2 rounded-lg bg-[#1d4ed8] px-5 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#1e40af] shadow-[0_4px_12px_rgba(29,78,216,0.3)]">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        Finalisasi Nilai
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- ═══════ MODAL SIMPAN DRAFT ═══════ --}}
        <div x-show="saveDraftModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm px-4" style="display: none;" x-transition @click.self="saveDraftModalOpen = false">
            <div class="flex w-full max-w-sm flex-col rounded-2xl bg-white shadow-2xl">
                <div class="p-6 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#fff7ed] text-[#ea580c] mb-4 ring-4 ring-[#ffedd5]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    </div>
                    <h3 class="text-[18px] font-black text-[#0f172a]">Simpan Draft Sementara?</h3>
                    <p class="mt-2 text-[13px] leading-[1.8] text-[#64748b]">Nilai yang Anda masukkan akan disimpan sebagai draft. Anda masih dapat mengubahnya nanti.</p>
                </div>
                <div class="flex gap-3 bg-[#f8fafc] px-6 py-4 rounded-b-2xl border-t border-[#e2e8f0]">
                    <button @click="saveDraftModalOpen = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Batal</button>
                    <button @click="confirmSaveDraft()" class="flex-1 rounded-lg bg-[#ea580c] px-4 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#c2410c]">Ya, Simpan Draft</button>
                </div>
            </div>
        </div>

        {{-- ═══════ MODAL FINALISASI ═══════ --}}
        <div x-show="finalizeModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm px-4" style="display: none;" x-transition @click.self="finalizeModalOpen = false">
            <div class="flex w-full max-w-sm flex-col rounded-2xl bg-white shadow-2xl">
                <div class="p-6 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#fef2f2] text-[#dc2626] mb-4 ring-4 ring-[#fee2e2]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-[18px] font-black text-[#0f172a]">Kunci & Finalisasi?</h3>
                    <p class="mt-2 text-[13px] leading-[1.8] text-[#64748b]">Setelah difinalisasi, formulir akan <strong class="text-[#0f172a]">terkunci</strong> dan nilai akan diteruskan otomatis ke panel Wali Kelas. Apakah Anda yakin?</p>
                </div>
                <div class="flex gap-3 bg-[#f8fafc] px-6 py-4 rounded-b-2xl border-t border-[#e2e8f0]">
                    <button @click="finalizeModalOpen = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Kenbali</button>
                    <button @click="confirmFinalize()" class="flex-1 rounded-lg bg-[#dc2626] px-4 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#b91c1c]">Ya, Finalisasi</button>
                </div>
            </div>
        </div>

    </div>
</x-guru-shell>
