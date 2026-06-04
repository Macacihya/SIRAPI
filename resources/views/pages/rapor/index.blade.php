@extends('layouts.app')
@section('title', 'Rapor Siswa')
@section('subtitle', 'Cetak dan kelola rapor')
@section('active', 'rapor')

@section('content')
@php
    $raporBySiswa = $raports->keyBy('siswa_id');
    $studentsData = $siswas->map(function ($siswa) use ($raporBySiswa) {
        $raport = $raporBySiswa->get($siswa->id);
        $rekap = $raport?->rekapKehadiran;
        $nilaiSikaps = collect($raport?->nilaiSikaps ?? []);
        $spiritual = $nilaiSikaps->first(fn ($item) => strtolower($item->sikap->nama_sikap ?? '') === 'spiritual');
        $sosial = $nilaiSikaps->first(fn ($item) => strtolower($item->sikap->nama_sikap ?? '') === 'sosial');
        $initials = collect(explode(' ', trim($siswa->nama_siswa ?? 'S')))
            ->filter()
            ->take(2)
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->implode('');

        return [
            'id' => $siswa->id,
            'raport_id' => $raport?->id,
            'nis' => $siswa->nis ?? '-',
            'nisn' => $siswa->nisn ?? '-',
            'name' => $siswa->nama_siswa ?? '-',
            'kelas' => $siswa->kelas->nama_kelas ?? '-',
            'avatar' => $initials ?: 'S',
            'status' => $raport?->status === 'final' ? 'Selesai' : ($raport ? 'Draft' : 'Belum'),
            'print_url' => $raport ? route('rapor.show', $raport) : '#',
            'form' => [
                'sikap_sp' => $spiritual?->predikat ?? '',
                'desc_sp' => $spiritual?->deskripsi ?? '',
                'sikap_so' => $sosial?->predikat ?? '',
                'desc_so' => $sosial?->deskripsi ?? '',
                'eskul' => collect($raport?->raportEkskuls ?? [])->map(fn ($item) => [
                    'id' => $item->id,
                    'nama' => $item->ekstrakurikuler->nama_eskul ?? '',
                    'deskripsi' => $item->deskripsi ?? '',
                ])->values()->all(),
                'catatan' => $raport?->catatan_wali ?? '',
                'sakit' => (int) ($rekap?->sakit ?? 0),
                'izin' => (int) ($rekap?->izin ?? 0),
                'alpha' => (int) ($rekap?->alpha ?? 0),
            ],
        ];
    })->values();
@endphp
<!-- Halaman Utama Rapor -->
    <div x-data="{
        selectedStudentId: @js($studentsData->first()['id'] ?? null),
        students: @js($studentsData),
        draftModalOpen: false,
        saveModalOpen: false,
        previewModalOpen: false,
        eskulToDelete: null,
        deleteConfirmOpen: false,
        prestasiToDelete: null,

        addEskul() {
            this.selectedStudent.form.eskul.push({ id: Date.now(), nama: '', deskripsi: '' });
        },

        removeEskul(id) {
            this.selectedStudent.form.eskul = this.selectedStudent.form.eskul.filter(e => e.id !== id);
        },

        prestasiList: [
            { id: 1, jenis: 'Akademik', keterangan: 'Juara 1 Olimpiade Matematika Tingkat Kota' }
        ],
        searchQuery: '',

        get filteredStudents() {
            if (this.searchQuery.trim() === '') {
                return this.students;
            }
            const q = this.searchQuery.toLowerCase();
            return this.students.filter(s => s.name.toLowerCase().includes(q) || s.nis.includes(q));
        },

        get selectedStudent() {
            return this.students.find(s => s.id === this.selectedStudentId) || this.students[0] || { form: { eskul: [], sakit: 0, izin: 0, alpha: 0 }, name: '-', nis: '-', nisn: '-', kelas: '-', status: 'Belum', print_url: '#' };
        },

        addPrestasi() {
            this.prestasiList.push({ id: Date.now(), jenis: 'Akademik', keterangan: '' });
        },

        confirmDeletePrestasi(id) {
            this.prestasiToDelete = id;
            this.deleteConfirmOpen = true;
        },

        deletePrestasi() {
            if(this.prestasiToDelete) {
                this.prestasiList = this.prestasiList.filter(p => p.id !== this.prestasiToDelete);
                this.prestasiToDelete = null;
            }
            this.deleteConfirmOpen = false;
        },

        async saveRaport(action) {
            if (!this.selectedStudent.raport_id) {
                alert('Rapor belum tersedia untuk siswa ini.');
                return;
            }

            // Payload ini adalah bentuk data form rapor yang akan disimpan ke Laravel.
            const payload = {
                action: action,
                sikap_sp: this.selectedStudent.form.sikap_sp || null,
                desc_sp: this.selectedStudent.form.desc_sp || null,
                sikap_so: this.selectedStudent.form.sikap_so || null,
                desc_so: this.selectedStudent.form.desc_so || null,
                eskul: this.selectedStudent.form.eskul || [],
                sakit: this.selectedStudent.form.sakit || 0,
                izin: this.selectedStudent.form.izin || 0,
                alpha: this.selectedStudent.form.alpha || 0,
                catatan: this.selectedStudent.form.catatan || null,
            };

            const response = await fetch(`/rapor/${this.selectedStudent.raport_id}/save-form`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify(payload),
            });

            if (!response.ok) {
                alert('Gagal menyimpan rapor. Periksa data form atau akses wali kelas.');
                return;
            }

            const result = await response.json();
            this.selectedStudent.status = result.status === 'final' ? 'Selesai' : 'Draft';
            this.draftModalOpen = false;
            this.saveModalOpen = false;
        },

        saveDraft() {
            this.saveRaport('draft');
        },

        // Simulasi Validasi (Set ke false agar bisa dicoba)
        hasMissingSubjectGrades: false, 
        isFormIncomplete: false,

        submitToBackend() {
            this.saveRaport('final');
        }

    }" class="flex h-[calc(100vh-140px)] gap-6">
        
        <!-- Sidebar Kiri: Daftar Siswa -->
        <div class="w-80 flex flex-col rounded-2xl bg-white border border-[#e2e8f0] shadow-sm overflow-hidden flex-shrink-0">
            <!-- Header Sidebar -->
            <div class="p-4 border-b border-[#e2e8f0] bg-[#f8fafc]">
                <h2 class="text-[15px] font-bold text-[#0f172a]">Daftar Siswa</h2>
                <p class="text-[12px] text-[#64748b]">Total: <span x-text="filteredStudents.length"></span> Siswa</p>
                
                <div class="mt-3 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" x-model="searchQuery" placeholder="Cari nama atau NIS/NISN siswa..." class="w-full h-9 pl-9 pr-3 text-[13px] rounded-lg border border-[#e2e8f0] focus:ring-2 focus:ring-[#3b82f6]/20 focus:border-[#3b82f6] outline-none transition">
                </div>
            </div>

            <!-- List Siswa -->
            <div class="flex-1 overflow-y-auto">
                <template x-for="student in filteredStudents" :key="student.id">
                    <button @click="selectedStudentId = student.id" 
                            :class="selectedStudentId === student.id ? 'bg-[#eff6ff] border-l-4 border-l-[#1d4ed8]' : 'hover:bg-[#f8fafc] border-l-4 border-l-transparent'"
                            class="w-full flex items-center gap-3 p-3 text-left border-b border-[#e2e8f0] transition">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-[#e2e8f0] flex items-center justify-center font-bold text-[#64748b] text-[13px]">
                            <span x-text="student.avatar"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-bold text-[#0f172a] truncate" x-text="student.name"></p>
                            <p class="text-[11px] text-[#64748b]" x-text="'NIS: ' + student.nis + ' | NISN: ' + (student.nisn || '-')"></p>
                        </div>
                        <div class="flex-shrink-0">
                            <!-- Indikator Status -->
                            <span x-show="student.status === 'Selesai'" class="flex h-5 w-5 bg-[#dcfce7] rounded-full items-center justify-center text-[#16a34a]">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span x-show="student.status === 'Draft'" class="flex h-5 w-5 bg-[#fef2f2] rounded-full items-center justify-center text-[#dc2626]">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </span>
                        </div>
                    </button>
                </template>
            </div>
        </div>

        <!-- Area Kanan: Form Input Rapor -->
        <div class="flex-1 flex flex-col rounded-2xl bg-white border border-[#e2e8f0] shadow-sm overflow-hidden relative">
            
            <!-- Breadcrumbs -->
            <div class="flex items-center gap-2 px-6 py-4 border-b border-[#e2e8f0] bg-[#f8fafc]">
                <span class="text-[12px] font-bold text-[#64748b]">Rapor</span>
                <svg class="h-3 w-3 text-[#cbd5e1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                <span class="text-[12px] font-bold text-[#64748b]">Input Rapor</span>
                <svg class="h-3 w-3 text-[#cbd5e1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                <span class="text-[12px] font-bold text-[#0f172a]" x-text="selectedStudent.name"></span>
            </div>

            <div class="flex-1 overflow-y-auto p-6 scroll-smooth">
                <!-- Header Info Siswa -->
                <div class="flex items-start justify-between mb-8">
                    <div>
                        <h1 class="text-[24px] font-black tracking-[-0.03em] text-[#0f172a]" x-text="'Input Rapor: ' + selectedStudent.name"></h1>
                        <p class="text-[13px] text-[#64748b] mt-1" x-text="'NIS: ' + selectedStudent.nis + ' | NISN: ' + (selectedStudent.nisn || '-') + ' | Kelas: ' + (selectedStudent.kelas || '-')"></p>
                    </div>
                    <div x-show="selectedStudent.status === 'Selesai'" class="flex items-center gap-2 px-4 py-2 bg-[#dcfce7] text-[#16a34a] rounded-lg border border-[#b9f6ca]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        <span class="text-[12px] font-bold">Rapor Terkunci (Sudah Final)</span>
                    </div>
                </div>

                <!-- Form Content (3 Bagian) -->
                <div class="space-y-8">

                    <!-- Section 1: Sikap Siswa -->
                    <div class="bg-white rounded-xl border border-[#e2e8f0] shadow-sm overflow-hidden">
                        <div class="px-5 py-3 border-b border-[#e2e8f0] bg-[#f8fafc]">
                            <h3 class="text-[14px] font-bold text-[#0f172a]">1. Sikap Siswa</h3>
                        </div>
                        <div class="p-5 space-y-6">
                            <!-- Sikap Spiritual -->
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-4">
                                    <label class="block text-[12px] font-bold text-[#475569] mb-1.5">Sikap Spiritual</label>
                                    <select x-model="selectedStudent.form.sikap_sp" :disabled="selectedStudent.status === 'Selesai'" class="w-full h-10 rounded-lg border border-[#e2e8f0] px-3 font-semibold text-[13px] text-[#0f172a] focus:ring-2 focus:ring-[#3b82f6]/20 focus:border-[#3b82f6] outline-none disabled:bg-[#f1f5f9] disabled:text-[#94a3b8]">
                                        <option value="">-- Pilih Nilai --</option>
                                        <option value="A">A (Sangat Baik)</option>
                                        <option value="B">B (Baik)</option>
                                        <option value="C">C (Cukup)</option>
                                        <option value="D">D (Kurang)</option>
                                    </select>
                                </div>
                                <div class="md:col-span-8">
                                    <label class="block text-[12px] font-bold text-[#475569] mb-1.5">Deskripsi Capaian Spiritual</label>
                                    <textarea x-model="selectedStudent.form.desc_sp" :readonly="selectedStudent.status === 'Selesai'" rows="2" class="w-full rounded-lg border border-[#e2e8f0] p-3 text-[13px] focus:ring-2 focus:ring-[#3b82f6]/20 focus:border-[#3b82f6] outline-none resize-none readonly:bg-[#f1f5f9] readonly:text-[#94a3b8]" placeholder="Cth: Sangat baik dalam ketaatan beribadah dan berperilaku syukur..."></textarea>
                                </div>
                            </div>
                            
                            <!-- Sikap Sosial -->
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-4">
                                    <label class="block text-[12px] font-bold text-[#475569] mb-1.5">Sikap Sosial</label>
                                    <select x-model="selectedStudent.form.sikap_so" :disabled="selectedStudent.status === 'Selesai'" class="w-full h-10 rounded-lg border border-[#e2e8f0] px-3 font-semibold text-[13px] text-[#0f172a] focus:ring-2 focus:ring-[#3b82f6]/20 focus:border-[#3b82f6] outline-none disabled:bg-[#f1f5f9] disabled:text-[#94a3b8]">
                                        <option value="">-- Pilih Nilai --</option>
                                        <option value="A">A (Sangat Baik)</option>
                                        <option value="B">B (Baik)</option>
                                        <option value="C">C (Cukup)</option>
                                        <option value="D">D (Kurang)</option>
                                    </select>
                                </div>
                                <div class="md:col-span-8">
                                    <label class="block text-[12px] font-bold text-[#475569] mb-1.5">Deskripsi Capaian Sosial</label>
                                    <textarea x-model="selectedStudent.form.desc_so" :readonly="selectedStudent.status === 'Selesai'" rows="2" class="w-full rounded-lg border border-[#e2e8f0] p-3 text-[13px] focus:ring-2 focus:ring-[#3b82f6]/20 focus:border-[#3b82f6] outline-none resize-none readonly:bg-[#f1f5f9] readonly:text-[#94a3b8]" placeholder="Cth: Sangat baik dalam sikap disiplin, jujur, and tanggung jawab..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Ekstrakurikuler -->
                    <div class="bg-white rounded-xl border border-[#e2e8f0] shadow-sm overflow-hidden">
                        <div class="px-5 py-3 border-b border-[#e2e8f0] bg-[#f8fafc] flex items-center justify-between">
                            <h3 class="text-[14px] font-bold text-[#0f172a]">2. Ekstrakurikuler</h3>
                            <button @click="addEskul()" :disabled="selectedStudent.status === 'Selesai'" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#eff6ff] text-[11px] font-bold text-[#1d4ed8] hover:bg-[#dbeafe] transition disabled:opacity-50">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                Tambah Eskul
                            </button>
                        </div>
                        <div class="p-5">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="text-[11px] font-bold text-[#64748b] uppercase tracking-wider">
                                            <th class="text-left pb-3 w-[40px]">No</th>
                                            <th class="text-left pb-3">Kegiatan Ekstrakurikuler</th>
                                            <th class="text-left pb-3 pl-4">Keterangan / Capaian</th>
                                            <th class="pb-3 w-[40px]"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#f1f5f9]">
                                        <template x-for="(e, index) in selectedStudent.form.eskul" :key="e.id">
                                            <tr>
                                                <td class="py-3 text-[13px] font-medium text-[#64748b]" x-text="index + 1"></td>
                                                <td class="py-3">
                                                    <input type="text" x-model="e.nama" :readonly="selectedStudent.status === 'Selesai'" class="w-full h-9 rounded-lg border border-[#e2e8f0] px-3 text-[13px] focus:ring-2 focus:ring-[#3b82f6]/20 focus:border-[#3b82f6] outline-none transition readonly:bg-[#f8fafc]">
                                                </td>
                                                <td class="py-3 pl-4">
                                                    <input type="text" x-model="e.deskripsi" :readonly="selectedStudent.status === 'Selesai'" class="w-full h-9 rounded-lg border border-[#e2e8f0] px-3 text-[13px] focus:ring-2 focus:ring-[#3b82f6]/20 focus:border-[#3b82f6] outline-none transition readonly:bg-[#f8fafc]" placeholder="Cth: Sangat aktif dalam kegiatan perkemahan...">
                                                </td>
                                                <td class="py-3 text-right">
                                                    <button @click="removeEskul(e.id)" :disabled="selectedStudent.status === 'Selesai'" class="p-1.5 text-[#94a3b8] hover:text-[#dc2626] transition disabled:hidden">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                        <template x-if="selectedStudent.form.eskul.length === 0">
                                            <tr>
                                                <td colspan="4" class="py-6 text-center text-[12px] text-[#94a3b8] italic">Belum ada data ekstrakurikuler yang ditambahkan.</td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Rekap Kehadiran Siswa -->
                    <div class="bg-white rounded-xl border border-[#e2e8f0] shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-[#e2e8f0] bg-[#f8fafc] flex items-center justify-between">
                            <div>
                                <h3 class="text-[14px] font-bold text-[#0f172a]">3. Rekap Kehadiran Siswa</h3>
                                <p class="text-[12px] text-[#64748b] mt-0.5">Gunakan tombol + / − untuk menentukan jumlah hari. Keterangan bersifat opsional.</p>
                            </div>
                            <!-- Total badge -->
                            <div x-show="((selectedStudent.form.sakit||0) + (selectedStudent.form.izin||0) + (selectedStudent.form.alpha||0)) > 0"
                                 x-transition
                                 class="flex-shrink-0 px-4 py-1.5 rounded-full bg-[#f8fafc] border border-[#e2e8f0] text-[12px] font-bold text-[#475569]">
                                Total tidak hadir: <span x-text="(selectedStudent.form.sakit||0) + (selectedStudent.form.izin||0) + (selectedStudent.form.alpha||0)"></span> hari
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                                {{-- Card SAKIT --}}
                                <div class="rounded-xl border border-[#e2e8f0] bg-white transition flex flex-col"
                                     :class="(selectedStudent.form.sakit||0) > 0 ? 'border-[#94a3b8] shadow-sm' : ''">
                                    <div class="px-5 py-6 text-center flex-1 flex flex-col items-center justify-center relative">
                                        <div class="flex items-center justify-center gap-1.5 text-[#64748b] mb-3">
                                            <span class="text-[12px] font-bold uppercase tracking-widest text-[#475569]">Sakit</span>
                                        </div>
                                        <div class="text-[48px] font-black leading-none text-[#0f172a]" x-text="selectedStudent.form.sakit || 0"></div>
                                        <div class="text-[12px] font-medium text-[#94a3b8] mt-1">hari</div>
                                        
                                        <!-- Controls -->
                                        <div class="flex items-center gap-3 mt-6">
                                            <button @click="if((selectedStudent.form.sakit||0) > 0) selectedStudent.form.sakit = (selectedStudent.form.sakit||0) - 1"
                                                    :disabled="selectedStudent.status === 'Selesai' || (selectedStudent.form.sakit||0) === 0"
                                                    class="w-9 h-9 rounded-full border border-[#e2e8f0] bg-white text-[#475569] hover:bg-[#f1f5f9] disabled:opacity-30 disabled:cursor-not-allowed transition flex items-center justify-center">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                            </button>
                                            <button @click="selectedStudent.form.sakit = (selectedStudent.form.sakit||0) + 1"
                                                    :disabled="selectedStudent.status === 'Selesai'"
                                                    class="w-9 h-9 rounded-full border border-[#e2e8f0] bg-white text-[#0f172a] hover:bg-[#f8fafc] disabled:opacity-30 disabled:cursor-not-allowed transition flex items-center justify-center shadow-sm">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Keterangan -->
                                    <div x-show="(selectedStudent.form.sakit||0) > 0"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         class="p-4 border-t border-[#e2e8f0] bg-[#f8fafc] rounded-b-xl">
                                        <label class="block text-[10px] font-bold text-[#475569] uppercase tracking-wider mb-2">Keterangan Opsional</label>
                                        <input type="text"
                                               x-model="selectedStudent.form.ket_sakit"
                                               :readonly="selectedStudent.status === 'Selesai'"
                                               maxlength="255"
                                               placeholder="Cth: sakit perut, demam..."
                                               class="w-full text-[12px] rounded-lg border border-[#e2e8f0] bg-white px-3 py-2 text-[#0f172a] focus:ring-2 focus:ring-[#94a3b8]/30 focus:border-[#94a3b8] outline-none transition placeholder:text-[#cbd5e1] readonly:bg-[#f1f5f9] readonly:text-[#94a3b8]">
                                    </div>
                                </div>

                                {{-- Card IZIN --}}
                                <div class="rounded-xl border border-[#e2e8f0] bg-white transition flex flex-col"
                                     :class="(selectedStudent.form.izin||0) > 0 ? 'border-[#94a3b8] shadow-sm' : ''">
                                    <div class="px-5 py-6 text-center flex-1 flex flex-col items-center justify-center relative">
                                        <div class="flex items-center justify-center gap-1.5 text-[#64748b] mb-3">
                                            <span class="text-[12px] font-bold uppercase tracking-widest text-[#475569]">Izin</span>
                                        </div>
                                        <div class="text-[48px] font-black leading-none text-[#0f172a]" x-text="selectedStudent.form.izin || 0"></div>
                                        <div class="text-[12px] font-medium text-[#94a3b8] mt-1">hari</div>
                                        
                                        <!-- Controls -->
                                        <div class="flex items-center gap-3 mt-6">
                                            <button @click="if((selectedStudent.form.izin||0) > 0) selectedStudent.form.izin = (selectedStudent.form.izin||0) - 1"
                                                    :disabled="selectedStudent.status === 'Selesai' || (selectedStudent.form.izin||0) === 0"
                                                    class="w-9 h-9 rounded-full border border-[#e2e8f0] bg-white text-[#475569] hover:bg-[#f1f5f9] disabled:opacity-30 disabled:cursor-not-allowed transition flex items-center justify-center">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                            </button>
                                            <button @click="selectedStudent.form.izin = (selectedStudent.form.izin||0) + 1"
                                                    :disabled="selectedStudent.status === 'Selesai'"
                                                    class="w-9 h-9 rounded-full border border-[#e2e8f0] bg-white text-[#0f172a] hover:bg-[#f8fafc] disabled:opacity-30 disabled:cursor-not-allowed transition flex items-center justify-center shadow-sm">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Keterangan -->
                                    <div x-show="(selectedStudent.form.izin||0) > 0"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         class="p-4 border-t border-[#e2e8f0] bg-[#f8fafc] rounded-b-xl">
                                        <label class="block text-[10px] font-bold text-[#475569] uppercase tracking-wider mb-2">Keterangan Opsional</label>
                                        <input type="text"
                                               x-model="selectedStudent.form.ket_izin"
                                               :readonly="selectedStudent.status === 'Selesai'"
                                               maxlength="255"
                                               placeholder="Cth: acara keluarga, keperluan..."
                                               class="w-full text-[12px] rounded-lg border border-[#e2e8f0] bg-white px-3 py-2 text-[#0f172a] focus:ring-2 focus:ring-[#94a3b8]/30 focus:border-[#94a3b8] outline-none transition placeholder:text-[#cbd5e1] readonly:bg-[#f1f5f9] readonly:text-[#94a3b8]">
                                    </div>
                                </div>

                                {{-- Card ALPHA --}}
                                <div class="rounded-xl border border-[#e2e8f0] bg-white transition flex flex-col"
                                     :class="(selectedStudent.form.alpha||0) > 0 ? 'border-[#94a3b8] shadow-sm' : ''">
                                    <div class="px-5 py-6 text-center flex-1 flex flex-col items-center justify-center relative">
                                        <div class="flex items-center justify-center gap-1.5 text-[#64748b] mb-3">
                                            <span class="text-[12px] font-bold uppercase tracking-widest text-[#475569]">Alpha</span>
                                        </div>
                                        <div class="text-[48px] font-black leading-none text-[#0f172a]" x-text="selectedStudent.form.alpha || 0"></div>
                                        <div class="text-[12px] font-medium text-[#94a3b8] mt-1">hari</div>
                                        
                                        <!-- Controls -->
                                        <div class="flex items-center gap-3 mt-6">
                                            <button @click="if((selectedStudent.form.alpha||0) > 0) selectedStudent.form.alpha = (selectedStudent.form.alpha||0) - 1"
                                                    :disabled="selectedStudent.status === 'Selesai' || (selectedStudent.form.alpha||0) === 0"
                                                    class="w-9 h-9 rounded-full border border-[#e2e8f0] bg-white text-[#475569] hover:bg-[#f1f5f9] disabled:opacity-30 disabled:cursor-not-allowed transition flex items-center justify-center">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                            </button>
                                            <button @click="selectedStudent.form.alpha = (selectedStudent.form.alpha||0) + 1"
                                                    :disabled="selectedStudent.status === 'Selesai'"
                                                    class="w-9 h-9 rounded-full border border-[#e2e8f0] bg-white text-[#0f172a] hover:bg-[#f8fafc] disabled:opacity-30 disabled:cursor-not-allowed transition flex items-center justify-center shadow-sm">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Keterangan -->
                                    <div x-show="(selectedStudent.form.alpha||0) > 0"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         class="p-4 border-t border-[#e2e8f0] bg-[#f8fafc] rounded-b-xl">
                                        <label class="block text-[10px] font-bold text-[#475569] uppercase tracking-wider mb-2">Keterangan Opsional</label>
                                        <input type="text"
                                               x-model="selectedStudent.form.ket_alpha"
                                               :readonly="selectedStudent.status === 'Selesai'"
                                               maxlength="255"
                                               placeholder="Cth: tanpa keterangan..."
                                               class="w-full text-[12px] rounded-lg border border-[#e2e8f0] bg-white px-3 py-2 text-[#0f172a] focus:ring-2 focus:ring-[#94a3b8]/30 focus:border-[#94a3b8] outline-none transition placeholder:text-[#cbd5e1] readonly:bg-[#f1f5f9] readonly:text-[#94a3b8]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Catatan Wali Kelas -->
                    <div class="bg-white rounded-xl border border-[#e2e8f0] shadow-sm overflow-hidden">
                        <div class="px-5 py-3 border-b border-[#e2e8f0] bg-[#f8fafc]">
                            <h3 class="text-[14px] font-bold text-[#0f172a]">4. Catatan Wali Kelas</h3>
                        </div>
                        <div class="p-5">
                            <textarea x-model="selectedStudent.form.catatan" :readonly="selectedStudent.status === 'Selesai'" rows="4" class="w-full rounded-lg border border-[#e2e8f0] p-3 text-[13px] focus:ring-2 focus:ring-[#3b82f6]/20 focus:border-[#3b82f6] outline-none resize-none readonly:bg-[#f1f5f9] readonly:text-[#94a3b8]" placeholder="Tuliskan pesan, motivasi, atau evaluasi wali kelas untuk siswa ini..."></textarea>
                            <p class="text-[11px] text-[#94a3b8] mt-2">Catatan ini akan langsung tercetak di lembar akhir Rapor Siswa.</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Action Footer -->
            <div class="p-6 border-t border-[#e2e8f0] bg-[#f8fafc] flex justify-between items-center rounded-b-2xl flex-shrink-0">
                <a :href="selectedStudent.print_url" class="px-5 py-2.5 rounded-lg border border-[#e2e8f0] bg-white text-[13px] font-bold text-[#475569] hover:bg-[#f1f5f9] transition flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    Lihat Rapor
                </a>
                <div class="flex gap-3">
                    <button @click="draftModalOpen = true" x-show="selectedStudent.status !== 'Selesai'" class="px-5 py-2.5 rounded-lg border border-[#e2e8f0] bg-white text-[13px] font-bold text-[#0f172a] hover:bg-[#f1f5f9] transition">
                        Simpan Draft
                    </button>
                    <button @click="saveModalOpen = true" x-show="selectedStudent.status !== 'Selesai'" class="px-5 py-2.5 rounded-lg bg-[#1d4ed8] text-[13px] font-bold text-white hover:bg-[#2563eb] transition">
                        Finalisasi Rapor
                    </button>
                </div>
            </div>
            
            <!-- Modal Simpan Draft -->
            <x-confirm-dialog
                alpineShow="draftModalOpen"
                type="warning"
                title="Simpan Draft Rapor?"
                message="Data sikap dan catatan wali kelas untuk <strong x-text='selectedStudent.name'></strong> akan disimpan sebagai draft."
                confirmText="Ya, Simpan Draft"
                confirmAction="saveDraft()"
            />

            <!-- Modal Finalisasi Rapor -->
            <x-confirm-dialog
                alpineShow="saveModalOpen"
                type="danger"
                title="Finalisasi Rapor?"
                message="Rapor <strong x-text='selectedStudent.name'></strong> akan dikunci. Data yang sudah final tidak dapat diubah lagi dan siap untuk dicetak."
                confirmText="Ya, Finalisasi"
                confirmAction="submitToBackend()"
                disabledCondition="hasMissingSubjectGrades || isFormIncomplete"
                disabledText="Belum Lengkap"
            />

            <!-- Modal Konfirmasi Hapus Prestasi -->
            <x-confirm-dialog
                alpineShow="deleteConfirmOpen"
                type="danger"
                title="Hapus Prestasi?"
                message="Apakah Anda yakin ingin menghapus data prestasi ini dari form?"
                confirmText="Hapus"
                confirmAction="deletePrestasi()"
            />

        </div>
    </div>
@endsection
