<x-walikelas-shell
    :user="auth()->user()"
    active="rapor"
    title="Data Rapor"
    subtitle="Selamat datang di Panel Wali Kelas"
>
    <!-- Halaman Utama Rapor -->
    <div x-data="{
        selectedStudentId: 1,
        students: [
            { id: 1, nis: '12001', name: 'Achmad Albar', avatar: 'AA', status: 'Selesai' },
            { id: 14, nis: '12014', name: 'Oscar Permana', avatar: 'OP', status: 'Belum' },
            { id: 2, nis: '12002', name: 'Bella Monica', avatar: 'BM', status: 'Belum' },
            { id: 6, nis: '12006', name: 'Gita Ananda', avatar: 'GA', status: 'Belum' },
            { id: 4, nis: '12004', name: 'Endah Kartika', avatar: 'EK', status: 'Belum' },
            { id: 17, nis: '12017', name: 'Rina Agustina', avatar: 'RA', status: 'Belum' },
            { id: 3, nis: '12003', name: 'Dandi Pratama', avatar: 'DP', status: 'Draft' },
            { id: 5, nis: '12005', name: 'Farhan Azis', avatar: 'FA', status: 'Belum' },
            { id: 7, nis: '12007', name: 'Hendra Yulian', avatar: 'HY', status: 'Selesai' },
            { id: 8, nis: '12008', name: 'Intan Sari', avatar: 'IS', status: 'Belum' },
            { id: 9, nis: '12009', name: 'Joko Wibowo', avatar: 'JW', status: 'Draft' },
            { id: 10, nis: '12010', name: 'Kirana Rahma', avatar: 'KR', status: 'Belum' },
            { id: 11, nis: '12011', name: 'Lukman Putra', avatar: 'LP', status: 'Selesai' },
            { id: 12, nis: '12012', name: 'Maya Nurhaliza', avatar: 'MN', status: 'Belum' },
            { id: 13, nis: '12013', name: 'Nanda Rizky', avatar: 'NR', status: 'Draft' },
            { id: 15, nis: '12015', name: 'Putri Setiawan', avatar: 'PS', status: 'Belum' },
            { id: 16, nis: '12016', name: 'Qiyamul Haq', avatar: 'QH', status: 'Selesai' },
            { id: 18, nis: '12018', name: 'Surya Bagaskara', avatar: 'SB', status: 'Belum' },
        ],
        draftModalOpen: false,
        saveModalOpen: false,
        previewModalOpen: false,
        deleteConfirmOpen: false,
        prestasiToDelete: null,
        
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
            return this.students.find(s => s.id === this.selectedStudentId) || this.students[0];
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

        saveDraft() {
            let sType = this.selectedStudent;
            sType.status = 'Draft';
            this.draftModalOpen = false;
        },

        saveFinal() {
            let sType = this.selectedStudent;
            sType.status = 'Selesai';
            this.saveModalOpen = false;
        }

    }" class="flex h-[calc(100vh-140px)] gap-6">
        
        <!-- Sidebar Kiri: Daftar Siswa -->
        <div class="w-80 flex flex-col rounded-2xl bg-white border border-[#e2e8f0] shadow-sm overflow-hidden flex-shrink-0">
            <!-- Header Sidebar -->
            <div class="p-4 border-b border-[#e2e8f0] bg-[#f8fafc]">
                <h2 class="text-[15px] font-bold text-[#0f172a]">Daftar Siswa</h2>
                <p class="text-[12px] text-[#64748b]">Kelas XII IPA 1 (Total: <span x-text="filteredStudents.length"></span> Siswa)</p>
                
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
                            <p class="text-[11px] text-[#64748b]" x-text="'NIS: ' + student.nis"></p>
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
                        <p class="text-[13px] text-[#64748b] mt-1" x-text="'NIS: ' + selectedStudent.nis + ' | Kelas: XII IPA 1'"></p>
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
                                    <select class="w-full h-10 rounded-lg border border-[#e2e8f0] px-3 font-semibold text-[13px] text-[#0f172a] focus:ring-2 focus:ring-[#3b82f6]/20 focus:border-[#3b82f6] outline-none">
                                        <option>A (Sangat Baik)</option>
                                        <option>B (Baik)</option>
                                        <option>C (Cukup)</option>
                                        <option>D (Kurang)</option>
                                    </select>
                                </div>
                                <div class="md:col-span-8">
                                    <label class="block text-[12px] font-bold text-[#475569] mb-1.5">Deskripsi Capaian Spiritual</label>
                                    <textarea rows="2" class="w-full rounded-lg border border-[#e2e8f0] p-3 text-[13px] focus:ring-2 focus:ring-[#3b82f6]/20 focus:border-[#3b82f6] outline-none resize-none" placeholder="Cth: Sangat baik dalam ketaatan beribadah dan berperilaku syukur..."></textarea>
                                </div>
                            </div>
                            
                            <!-- Sikap Sosial -->
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-4">
                                    <label class="block text-[12px] font-bold text-[#475569] mb-1.5">Sikap Sosial</label>
                                    <select class="w-full h-10 rounded-lg border border-[#e2e8f0] px-3 font-semibold text-[13px] text-[#0f172a] focus:ring-2 focus:ring-[#3b82f6]/20 focus:border-[#3b82f6] outline-none">
                                        <option>A (Sangat Baik)</option>
                                        <option>B (Baik)</option>
                                        <option>C (Cukup)</option>
                                        <option>D (Kurang)</option>
                                    </select>
                                </div>
                                <div class="md:col-span-8">
                                    <label class="block text-[12px] font-bold text-[#475569] mb-1.5">Deskripsi Capaian Sosial</label>
                                    <textarea rows="2" class="w-full rounded-lg border border-[#e2e8f0] p-3 text-[13px] focus:ring-2 focus:ring-[#3b82f6]/20 focus:border-[#3b82f6] outline-none resize-none" placeholder="Cth: Sangat baik dalam sikap disiplin, jujur, dan tanggung jawab..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Kehadiran Siswa -->
                    <div class="bg-white rounded-xl border border-[#e2e8f0] shadow-sm overflow-hidden">
                        <div class="px-5 py-3 border-b border-[#e2e8f0] bg-[#f8fafc]">
                            <h3 class="text-[14px] font-bold text-[#0f172a]">2. Kehadiran Siswa</h3>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div class="bg-[#f8fafc] border border-[#e2e8f0] rounded-xl p-3 shadow-sm">
                                    <span class="block text-[11px] font-bold text-[#64748b] uppercase tracking-wider">Sakit</span>
                                    <span class="block text-[22px] font-black text-[#0f172a] mt-1">2 <span class="text-[13px] font-bold text-[#64748b]">Hari</span></span>
                                </div>
                                <div class="bg-[#f8fafc] border border-[#e2e8f0] rounded-xl p-3 shadow-sm">
                                    <span class="block text-[11px] font-bold text-[#64748b] uppercase tracking-wider">Izin</span>
                                    <span class="block text-[22px] font-black text-[#0f172a] mt-1">0 <span class="text-[13px] font-bold text-[#64748b]">Hari</span></span>
                                </div>
                                <div class="bg-[#fef2f2] border border-[#fca5a5] rounded-xl p-3 shadow-sm">
                                    <span class="block text-[11px] font-bold text-[#dc2626] uppercase tracking-wider">Tanpa Keterangan</span>
                                    <span class="block text-[22px] font-black text-[#dc2626] mt-1">1 <span class="text-[13px] font-bold text-[#dc2626]">Hari</span></span>
                                </div>
                            </div>
                            <p class="text-[11px] text-[#94a3b8] mt-4 font-semibold italic text-center">* Data kehadiran diambil secara otomatis dari rekapitulasi modul Kehadiran Siswa.</p>
                        </div>
                    </div>

                    <!-- Section 3: Catatan Wali Kelas -->
                    <div class="bg-white rounded-xl border border-[#e2e8f0] shadow-sm overflow-hidden">
                        <div class="px-5 py-3 border-b border-[#e2e8f0] bg-[#f8fafc]">
                            <h3 class="text-[14px] font-bold text-[#0f172a]">3. Catatan Wali Kelas</h3>
                        </div>
                        <div class="p-5">
                            <textarea rows="4" class="w-full rounded-lg border border-[#e2e8f0] p-3 text-[13px] focus:ring-2 focus:ring-[#3b82f6]/20 focus:border-[#3b82f6] outline-none resize-none" placeholder="Tuliskan pesan, motivasi, atau evaluasi wali kelas untuk siswa ini..."></textarea>
                            <p class="text-[11px] text-[#94a3b8] mt-2">Catatan ini akan langsung tercetak di lembar akhir Rapor Siswa.</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Action Footer -->
            <div class="p-6 border-t border-[#e2e8f0] bg-[#f8fafc] flex justify-between items-center rounded-b-2xl flex-shrink-0">
                <a :href="`{{ route('walikelas.rapor.lihat') }}?name=${encodeURIComponent(selectedStudent.name)}&nis=${selectedStudent.nis}`" class="px-5 py-2.5 rounded-lg border border-[#e2e8f0] bg-white text-[13px] font-bold text-[#475569] hover:bg-[#f1f5f9] transition flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    Lihat Rapor
                </a>
                <div class="flex gap-3">
                    <button @click="draftModalOpen = true" class="px-5 py-2.5 rounded-lg border border-[#e2e8f0] bg-white text-[13px] font-bold text-[#0f172a] hover:bg-[#f1f5f9] transition">
                        Simpan Draft
                    </button>
                    <button @click="saveModalOpen = true" class="px-5 py-2.5 rounded-lg bg-[#1d4ed8] text-[13px] font-bold text-white hover:bg-[#2563eb] transition">
                        Finalisasi Rapor
                    </button>
                </div>
            </div>
            
            <!-- Modal Simpan Draft -->
            <div x-show="draftModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" x-transition>
                <div @click.outside="draftModalOpen = false" class="bg-white rounded-2xl p-6 w-[400px] shadow-xl">
                    <div class="w-12 h-12 rounded-full bg-[#f1f5f9] flex items-center justify-center mb-4 text-[#475569]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#0f172a] mb-2">Simpan sebagai Draft?</h3>
                    <p class="text-[13px] text-[#64748b] mb-6">Progres pengisian rapor untuk <strong x-text="selectedStudent.name"></strong> akan disimpan. Status akan menjadi Draft.</p>
                    <div class="flex justify-end gap-3">
                        <button @click="draftModalOpen = false" class="px-4 py-2 rounded-lg text-[13px] font-bold text-[#64748b] hover:bg-[#f1f5f9]">Batal</button>
                        <button @click="saveDraft()" class="px-4 py-2 rounded-lg bg-[#0f172a] text-[13px] font-bold text-white hover:bg-[#1e293b]">Simpan Draft</button>
                    </div>
                </div>
            </div>

            <!-- Modal Finalisasi Rapor -->
            <div x-show="saveModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" x-transition>
                <div @click.outside="saveModalOpen = false" class="bg-white rounded-2xl p-6 w-[400px] shadow-xl">
                    <div class="w-12 h-12 rounded-full bg-[#dcfce7] flex items-center justify-center mb-4 text-[#16a34a]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#0f172a] mb-2">Finalisasi Rapor?</h3>
                    <p class="text-[13px] text-[#64748b] mb-6">Pastikan semua nilai ekstrakurikuler, catatan, dan kehadiran sudah benar. Apakah Anda yakin untuk memfinalisasi rapor <strong x-text="selectedStudent.name"></strong>?</p>
                    <div class="flex justify-end gap-3">
                        <button @click="saveModalOpen = false" class="px-4 py-2 rounded-lg text-[13px] font-bold text-[#64748b] hover:bg-[#f1f5f9]">Batal</button>
                        <button @click="saveFinal()" class="px-4 py-2 rounded-lg bg-[#16a34a] text-[13px] font-bold text-white hover:bg-[#15803d]">Finalisasi</button>
                    </div>
                </div>
            </div>

            <!-- Modal Konfirmasi Hapus Prestasi -->
            <div x-show="deleteConfirmOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" x-transition>
                <div @click.outside="deleteConfirmOpen = false" class="bg-white rounded-2xl p-6 w-[400px] shadow-xl">
                    <div class="w-12 h-12 rounded-full bg-[#fef2f2] flex items-center justify-center mb-4 text-red-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#0f172a] mb-2">Hapus Prestasi?</h3>
                    <p class="text-[13px] text-[#64748b] mb-6">Apakah Anda yakin ingin menghapus data prestasi ini dari form?</p>
                    <div class="flex justify-end gap-3">
                        <button @click="deleteConfirmOpen = false" class="px-4 py-2 rounded-lg text-[13px] font-bold text-[#64748b] hover:bg-[#f1f5f9]">Batal</button>
                        <button @click="deletePrestasi()" class="px-4 py-2 rounded-lg bg-red-600 text-[13px] font-bold text-white hover:bg-red-700">Hapus</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-walikelas-shell>
