@extends('layouts.guru')
@section('title', 'Capaian Kompetensi')
@section('subtitle', 'Pencapaian siswa per kompetensi')
@section('active', 'capaian-kompetensi')

@section('content')
<style>
        .sticky-actions {
            box-shadow: 0 -4px 12px -4px rgba(0, 0, 0, 0.05);
        }
    </style>

    <div class="space-y-6 pb-24" x-data="{
        filterKelas: 'VI-A',
        filterSemester: 'Ganjil',
        searchQuery: '',
        
        globalText: '',
        
        // Modal State
        saveDraftModalOpen: false,
        finalizeModalOpen: false,

        students: [
            { id: 1, kelas: 'VI-A', nis: '12001', init: 'AA', nama: 'Achmad Albar', capaian: 'Menunjukkan penguasaan yang sangat baik dalam memahami teks narasi dan mampu menceritakan kembali dengan intonasi yang tepat.', status: 'Draft' },
            { id: 2, kelas: 'VI-A', nis: '12002', init: 'BM', nama: 'Bella Monica', capaian: 'Sangat terampil dalam menulis esai argumentatif dengan struktur kalimat yang jelas dan kosakata yang kaya.', status: 'Final' },
            { id: 3, kelas: 'VI-A', nis: '12003', init: 'DP', nama: 'Dandi Pratama', capaian: '', status: 'Belum' },
            { id: 4, kelas: 'VI-A', nis: '12004', init: 'EK', nama: 'Endah Kartika', capaian: '', status: 'Belum' },
            { id: 5, kelas: 'I-A', nis: '01011', init: 'AB', nama: 'Andi Budiman', capaian: 'Dapat mengenali huruf abjad dengan baik dan mencoba mengeja kata-kata sederhana.', status: 'Draft' },
        ],

        get filteredStudents() {
            let result = this.students;
            if (this.filterKelas) {
                result = result.filter(s => s.kelas === this.filterKelas);
            }
            if (this.searchQuery) {
                const q = this.searchQuery.toLowerCase();
                result = result.filter(s => s.nama.toLowerCase().includes(q) || s.nis.includes(q));
            }
            return result;
        },

        get filledCount() {
            return this.filteredStudents.filter(s => s.capaian.trim() !== '').length;
        },

        // Mass Apply
        applyGlobalToEmpty() {
            if(!this.globalText.trim()) return;
            this.filteredStudents.forEach(s => {
                if(s.status !== 'Final' && !s.capaian.trim()) {
                    s.capaian = this.globalText;
                    if(s.status === 'Belum') s.status = 'Draft';
                }
            });
        },
        
        applyGlobalToAllAvailable() {
            if(!this.globalText.trim()) return;
            this.filteredStudents.forEach(s => {
                if(s.status !== 'Final') {
                    s.capaian = this.globalText;
                    if(s.status === 'Belum') s.status = 'Draft';
                }
            });
            this.globalText = ''; // Clear prompt
        },

        // Actions
        saveDraft() {
            this.saveDraftModalOpen = true;
        },
        confirmSaveDraft() {
            this.filteredStudents.forEach(s => {
                if(s.status === 'Belum' && s.capaian.trim()) s.status = 'Draft';
            });
            this.saveDraftModalOpen = false;
        },
        finalize() {
            this.finalizeModalOpen = true;
        },
        confirmFinalize() {
            this.filteredStudents.forEach(s => {
                if(s.capaian.trim()) s.status = 'Final';
            });
            this.finalizeModalOpen = false;
        }
    }">
        {{-- Title + Info --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a] sm:text-[36px]">Capaian Kompetensi</h1>
                <p class="mt-1 text-[14px] text-[#475569]">Mata Pelajaran: <strong>Bahasa Indonesia</strong></p>
            </div>
            <div class="flex gap-2">
                <select x-model="filterKelas" class="h-11 rounded-lg border border-[#e2e8f0] bg-white px-4 text-[13px] font-bold text-[#0f172a] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                    <option value="I-A">Kelas I-A</option>
                    <option value="VI-A">Kelas VI-A</option>
                </select>
                <select x-model="filterSemester" class="h-11 rounded-lg border border-[#e2e8f0] bg-white px-4 text-[13px] font-bold text-[#0f172a] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                    <option value="Ganjil">SMT Ganjil</option>
                    <option value="Genap">SMT Genap</option>
                </select>
            </div>
        </div>

        {{-- Top Widgets: Global Shortcut & Status Pengisian --}}
        <div class="grid gap-4 lg:grid-cols-3">
            {{-- Global Input Shortcut --}}
            <div class="lg:col-span-2 rounded-xl bg-white p-5 lg:p-6 ring-1 ring-[#e2e8f0] shadow-sm flex flex-col justify-between">
                <div class="flex items-start gap-4">
                    <div class="hidden sm:flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-[#eff6ff] text-[#1d4ed8]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div class="flex-1 space-y-4">
                        <div>
                            <h3 class="text-[16px] font-black tracking-tight text-[#0f172a]">Isi Cepat (Capaian Umum)</h3>
                            <p class="mt-1 text-[13px] text-[#64748b]">Tuliskan deskripsi umum untuk siswa yang capaiannya setara, lalu terapkan secara massal ke kartu di bawah.</p>
                        </div>
                        <div class="relative">
                            <textarea x-model="globalText" rows="2" class="w-full rounded-lg border border-[#e2e8f0] bg-[#f8fafc] px-4 py-3 text-[13px] leading-relaxed text-[#0f172a] outline-none transition focus:border-[#3b82f6] focus:bg-white focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="Ketik narasi capaian kompetensi umum di sini..."></textarea>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button @click="applyGlobalToEmpty()" class="flex h-10 items-center justify-center gap-2 rounded-lg bg-[#0f172a] px-5 text-[12px] font-bold text-white transition hover:bg-[#1e293b]">
                                Terapkan ke Baris Kosong
                            </button>
                            <button @click="applyGlobalToAllAvailable()" class="flex h-10 items-center justify-center gap-2 rounded-lg border border-[#e2e8f0] bg-white px-5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">
                                Timpa Semua Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Status Pengisian Widget (Mirip Referensi Gambar) --}}
            <div class="rounded-xl bg-white p-5 lg:p-6 ring-1 ring-[#e2e8f0] shadow-sm flex flex-col justify-center">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#f1f5f9] text-[#475569]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16v-4m0-4h.01"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.05em] text-[#475569]">Status Pengisian</p>
                        <p class="mt-0.5 text-[22px] font-black tracking-tight text-[#0f172a]"><span x-text="filledCount"></span> / <span x-text="filteredStudents.length"></span> Siswa</p>
                    </div>
                </div>
                <div class="mt-5 h-[6px] w-full overflow-hidden rounded-full bg-[#f1f5f9]">
                    <div class="h-full rounded-full bg-[#0f172a] transition-all duration-300" :style="'width: ' + (filteredStudents.length > 0 ? (filledCount / filteredStudents.length * 100) : 0) + '%'"></div>
                </div>
                <p class="mt-5 text-[12px] italic leading-relaxed text-[#475569]">Pastikan narasi capaian menggunakan kata operasional yang sesuai dengan level kognitif siswa.</p>
            </div>
        </div>

        {{-- Search Input --}}
        <div class="relative">
            <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"/><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"/></svg>
            <input type="text" x-model="searchQuery" placeholder="Cari berdasarkan nama siswa..." class="h-11 w-full max-w-md rounded-lg border border-[#e2e8f0] bg-white pl-11 pr-4 text-[13px] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
        </div>

        {{-- Students List (Unified Container with Scroll) --}}
        <div class="rounded-xl bg-white ring-1 ring-[#e2e8f0] shadow-sm overflow-hidden flex flex-col">
            <div class="overflow-y-auto max-h-[600px] divide-y divide-[#e2e8f0]/80">
                <template x-if="filteredStudents.length > 0">
                    <template x-for="s in filteredStudents" :key="s.id">
                        <div class="flex flex-col lg:flex-row gap-4 lg:gap-6 p-5 lg:p-6 transition hover:bg-[#f8fafc]">
                            {{-- Student Profil Info --}}
                            <div class="flex w-full lg:w-[280px] flex-shrink-0 items-center lg:items-start gap-4 border-b border-[#e2e8f0] pb-4 lg:border-b-0 lg:border-r lg:pb-0 lg:pr-6">
                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-[#1e40af] text-[14px] font-black text-white" x-text="s.init"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="truncate text-[15px] font-black text-[#0f172a]" x-text="s.nama"></p>
                                    <p class="mt-0.5 text-[12px] font-semibold text-[#64748b]">NIS: <span x-text="s.nis"></span></p>
                                    <div class="mt-2 text-left">
                                        <span x-show="s.status === 'Belum'" class="inline-block rounded px-2 py-1 text-[10px] font-bold uppercase tracking-wider bg-[#f1f5f9] text-[#64748b]">Belum Diisi</span>
                                        <span x-show="s.status === 'Draft'" class="inline-block rounded px-2 py-1 text-[10px] font-bold uppercase tracking-wider bg-[#fff7ed] text-[#ea580c] ring-1 ring-inset ring-[#fdba74]">Draft</span>
                                        <span x-show="s.status === 'Final'" class="inline-block rounded px-2 py-1 text-[10px] font-bold uppercase tracking-wider bg-[#f0fdf4] text-[#16a34a] ring-1 ring-inset ring-[#86efac]">Terkunci</span>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Textarea Capaian --}}
                            <div class="flex-1 relative">
                                <textarea 
                                    x-model="s.capaian" 
                                    :disabled="s.status === 'Final'"
                                    rows="3" 
                                    class="w-full rounded-lg border px-4 py-3 text-[13px] leading-[1.8] outline-none transition"
                                    :class="s.status === 'Final' ? 'bg-[#f8fafc] border-transparent text-[#475569] cursor-not-allowed' : 'bg-white border-[#e2e8f0] text-[#0f172a] focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20'"
                                    placeholder="Deskripsi kemajuan dan catatan khusus siswa terkait CP..."
                                    @input="if(s.status === 'Belum') s.status = 'Draft'"
                                ></textarea>
                            </div>
                        </div>
                    </template>
                </template>
                <template x-if="filteredStudents.length === 0">
                    <div class="flex flex-col items-center justify-center py-16">
                        <svg class="h-10 w-10 text-[#cbd5e1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <p class="mt-3 text-[14px] font-bold text-[#64748b]">Tidak ada siswa dari kelas yang dipilih.</p>
                    </div>
                </template>
            </div>
        </div>

        {{-- Sticky Action Bottom Bar --}}
        <div class="sticky-actions fixed bottom-0 left-0 lg:left-[240px] right-0 z-40 flex items-center justify-between border-t border-[#e2e8f0] bg-white/95 px-4 py-4 sm:px-6 lg:px-8 backdrop-blur" x-show="filteredStudents.length > 0">
            <div class="hidden md:block">
                <p class="text-[12px] text-[#64748b]">Pastikan Anda memeriksa ulang sebelum menekan tombol <strong>Finalisasi</strong>.</p>
            </div>
            <div class="flex w-full md:w-auto items-center gap-3">
                <button @click="saveDraft()" class="flex-1 md:flex-none flex items-center justify-center gap-2 rounded-lg border border-[#e2e8f0] bg-white px-5 py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9] hover:text-[#0f172a]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Simpan Draft
                </button>
                <button @click="finalize()" class="flex-1 md:flex-none flex items-center justify-center gap-2 rounded-lg bg-[#1d4ed8] px-5 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#1e40af] shadow-[0_4px_12px_rgba(29,78,216,0.3)]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Finalisasi Capaian
                </button>
            </div>
        </div>

        {{-- Modal Simpan Draft --}}
        <div x-show="saveDraftModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm px-4" style="display: none;" x-transition @click.self="saveDraftModalOpen = false">
            <div class="flex w-full max-w-sm flex-col rounded-2xl bg-white shadow-2xl">
                <div class="p-6 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#fff7ed] text-[#ea580c] mb-4 ring-4 ring-[#ffedd5]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    </div>
                    <h3 class="text-[18px] font-black text-[#0f172a]">Simpan Draft?</h3>
                    <p class="mt-2 text-[13px] leading-[1.8] text-[#64748b]">Teks capaian akan disimpan sebagai draft.</p>
                </div>
                <div class="flex gap-3 bg-[#f8fafc] px-6 py-4 rounded-b-2xl border-t border-[#e2e8f0]">
                    <button @click="saveDraftModalOpen = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
                    <button @click="confirmSaveDraft()" class="flex-1 rounded-lg bg-[#ea580c] px-4 py-2.5 text-[12px] font-bold text-white hover:bg-[#c2410c]">Ya, Simpan</button>
                </div>
            </div>
        </div>

        {{-- Modal Finalisasi --}}
        <div x-show="finalizeModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm px-4" style="display: none;" x-transition @click.self="finalizeModalOpen = false">
            <div class="flex w-full max-w-sm flex-col rounded-2xl bg-white shadow-2xl">
                <div class="p-6 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#fef2f2] text-[#dc2626] mb-4 ring-4 ring-[#fee2e2]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-[18px] font-black text-[#0f172a]">Finalisasi Capaian?</h3>
                    <p class="mt-2 text-[13px] leading-[1.8] text-[#64748b]">Semua capaian kompetensi ini akan dikunci dan dikirim ke Wali Kelas.</p>
                </div>
                <div class="flex gap-3 bg-[#f8fafc] px-6 py-4 rounded-b-2xl border-t border-[#e2e8f0]">
                    <button @click="finalizeModalOpen = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[12px] font-bold text-[#475569]">Kembali</button>
                    <button @click="confirmFinalize()" class="flex-1 rounded-lg bg-[#1d4ed8] px-4 py-2.5 text-[12px] font-bold text-white hover:bg-[#1e40af]">Ya, Finalisasi</button>
                </div>
            </div>
        </div>

    </div>
@endsection
