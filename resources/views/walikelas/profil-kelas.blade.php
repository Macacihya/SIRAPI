<x-walikelas-shell
    :user="auth()->user()"
    active="profil-kelas"
    title="Profil Kelas"
    subtitle="Selamat datang di Panel Wali Kelas"
>
    <div class="space-y-8" x-data="{
        editModalOpen: false,
        saveModalOpen: false,
        editIndex: null,
        editForm: { nama: '', nis: '', jk: '', role: '', init: '', no: '' },

        siswa: [
            { no: '01', nis: '12001 / 005432101', init: 'AP', nama: 'ADITYA PRATAMA', jk: 'Laki-laki', role: 'KETUA KELAS' },
            { no: '02', nis: '12002 / 005432102', init: 'AR', nama: 'ANISA RAHMAWATI', jk: 'Perempuan', role: 'SEKRETARIS 1' },
            { no: '03', nis: '12003 / 005432103', init: 'BK', nama: 'BAGUS KURNIAWAN', jk: 'Laki-laki', role: 'SISWA' },
            { no: '04', nis: '12004 / 005432104', init: 'CL', nama: 'CITRA LESTARI', jk: 'Perempuan', role: 'BENDAHARA 1' },
            { no: '05', nis: '12005 / 005432105', init: 'DR', nama: 'DANI RAMDAN', jk: 'Laki-laki', role: 'SISWA' },
            { no: '06', nis: '12006 / 005432106', init: 'EF', nama: 'EKA FITRIANI', jk: 'Perempuan', role: 'SEKRETARIS 2' },
            { no: '07', nis: '12007 / 005432107', init: 'FA', nama: 'FARAH AZZAHRA', jk: 'Perempuan', role: 'SISWA' },
            { no: '08', nis: '12008 / 005432108', init: 'GK', nama: 'GILANG KURNIAWAN', jk: 'Laki-laki', role: 'BENDAHARA 2' },
        ],

        // Batas maksimum tiap jabatan (masing-masing 1 orang)
        limits: {
            'KETUA KELAS': 1,
            'WAKIL KETUA': 1,
            'SEKRETARIS 1': 1,
            'SEKRETARIS 2': 1,
            'BENDAHARA 1': 1,
            'BENDAHARA 2': 1,
            'SISWA': 999,
        },

        // Hitung berapa yang sudah mengisi jabatan tertentu (kecuali siswa yang sedang diedit)
        countRole(role) {
            return this.siswa.filter((s, i) => s.role === role && i !== this.editIndex).length;
        },

        // Cek apakah jabatan masih bisa dipilih
        isRoleAvailable(role) {
            return this.countRole(role) < this.limits[role];
        },

        // Sisa kuota jabatan
        remainingSlots(role) {
            const remaining = this.limits[role] - this.countRole(role);
            return Math.max(0, remaining);
        },

        openEdit(index) {
            this.editIndex = index;
            this.editForm = { ...this.siswa[index] };
            this.editModalOpen = true;
        },

        confirmSave() {
            this.saveModalOpen = true;
        },

        executeSave() {
            this.siswa[this.editIndex] = { ...this.editForm };
            this.saveModalOpen = false;
            this.editModalOpen = false;
            this.editIndex = null;
        },

        getRoleStyle(role) {
            const styles = {
                'KETUA KELAS': 'bg-[#0f172a] text-white',
                'WAKIL KETUA': 'bg-[#334155] text-white',
                'SEKRETARIS 1': 'bg-[#1e40af] text-white',
                'SEKRETARIS 2': 'bg-[#3b82f6] text-white',
                'BENDAHARA 1': 'bg-[#1d4ed8] text-white',
                'BENDAHARA 2': 'bg-[#60a5fa] text-white',
            };
            return styles[role] || 'bg-[#f1f5f9] text-[#475569]';
        }
    }">
        {{-- Class Title --}}
        <div>
            <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a] sm:text-[36px]">XII IPA 1</h1>
            <p class="mt-3 max-w-[600px] text-[14px] leading-[1.8] text-[#475569] sm:text-[16px]">
                Profil lengkap Kelas XII IPA 1 untuk tahun ajaran 2023/2024. Informasi mencakup detail struktural, data akademik, dan daftar siswa.
            </p>
        </div>

        {{-- Info Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Wali Kelas --}}
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Wali Kelas</p>
                <div class="mt-4 flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#1e40af] text-[14px] font-bold text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <div>
                        <p class="text-[15px] font-bold text-[#0f172a]">Budi Santoso, S.Pd.</p>
                        <p class="text-[12px] text-[#64748b]">Fisika / Guru Senior</p>
                    </div>
                </div>
            </div>

            {{-- Total Siswa --}}
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Total Siswa</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#1d4ed8]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
                <div class="mt-3 flex items-end gap-2">
                    <span class="text-[42px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">36</span>
                    <span class="pb-1 text-[13px] font-semibold text-[#64748b]">Terdaftar</span>
                </div>
            </div>

            {{-- Laki-laki --}}
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#3b82f6]/30">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Laki-laki</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#1d4ed8]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4" stroke-width="2"/><path d="M5.5 21a6.5 6.5 0 0113 0" stroke-width="2" stroke-linecap="round"/></svg>
                    </div>
                </div>
                <div class="mt-3 flex items-end justify-between">
                    <p class="text-[42px] font-black leading-none tracking-[-0.04em] text-[#0f172a]">20</p>
                    <p class="pb-1 text-[13px] font-semibold text-[#64748b]">55.5%</p>
                </div>
                <div class="mt-3 h-1.5 rounded-full bg-[#e2e8f0]"><div class="h-full w-[55.5%] rounded-full bg-[#1d4ed8]"></div></div>
            </div>

            {{-- Perempuan --}}
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Perempuan</p>
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#eff6ff] text-[#3b82f6]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4" stroke-width="2"/><path d="M5.5 21a6.5 6.5 0 0113 0" stroke-width="2" stroke-linecap="round"/></svg>
                    </div>
                </div>
                <div class="mt-3 flex items-end justify-between">
                    <p class="text-[42px] font-black leading-none tracking-[-0.04em] text-[#0f172a]">16</p>
                    <p class="pb-1 text-[13px] font-semibold text-[#64748b]">44.5%</p>
                </div>
                <div class="mt-3 h-1.5 rounded-full bg-[#e2e8f0]"><div class="h-full w-[44.5%] rounded-full bg-[#3b82f6]"></div></div>
            </div>
        </div>

        {{-- Daftar Siswa Section --}}
        <div class="space-y-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-[22px] font-black tracking-[-0.03em] text-[#0f172a] sm:text-[28px]">Daftar Siswa</h2>
                    <p class="mt-1 text-[13px] text-[#64748b]">Data lengkap siswa Kelas XII IPA 1</p>
                </div>
            </div>

            {{-- Search + Actions --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"/><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"/></svg>
                    <input type="text" placeholder="Cari berdasarkan Nama atau NISN..." class="h-11 w-full rounded-lg border border-[#e2e8f0] bg-white pl-11 pr-4 text-[13px] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                </div>
                <button class="flex h-11 items-center gap-2 rounded-lg border border-[#e2e8f0] bg-white px-5 text-[11px] font-extrabold uppercase tracking-[0.12em] text-[#475569] transition hover:bg-[#f1f5f9]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 4h18M7 8h10M10 12h4" stroke-width="2" stroke-linecap="round"/></svg>
                    Filter
                </button>
                <button class="flex h-11 items-center gap-2 rounded-lg border border-[#e2e8f0] bg-white px-5 text-[11px] font-extrabold uppercase tracking-[0.12em] text-[#475569] transition hover:bg-[#f1f5f9]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Cetak
                </button>
                <button class="flex h-11 items-center gap-2 rounded-lg bg-[#0f172a] px-5 text-[11px] font-extrabold uppercase tracking-[0.12em] text-white transition hover:bg-[#1e293b]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Export
                </button>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto rounded-xl bg-white ring-1 ring-[#e2e8f0]">
                <table class="w-full text-left text-[13px]">
                    <thead>
                        <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">No</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">NIS / NISN</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Nama Siswa</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Jenis Kelamin</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Jabatan</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(s, index) in siswa" :key="index">
                            <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                                <td class="px-5 py-4 text-[#64748b]" x-text="s.no"></td>
                                <td class="px-5 py-4 font-bold text-[#0f172a]" x-text="s.nis"></td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-[#1e40af] text-[10px] font-bold text-white" x-text="s.init"></div>
                                        <span class="font-bold text-[#0f172a]" x-text="s.nama"></span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-[#475569]" x-text="s.jk"></td>
                                <td class="px-5 py-4">
                                    <span class="inline-block rounded px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-[0.08em]" :class="getRoleStyle(s.role)" x-text="s.role"></span>
                                </td>
                                <td class="px-5 py-4">
                                    <button @click="openEdit(index)" class="flex items-center gap-1.5 rounded-md border border-[#e2e8f0] px-3 py-1.5 text-[11px] font-bold text-[#475569] transition hover:bg-[#f1f5f9] hover:border-[#3b82f6] hover:text-[#1d4ed8]">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex flex-col items-center justify-between gap-3 sm:flex-row">
                <p class="text-[11px] font-bold uppercase tracking-[0.1em] text-[#64748b]">Menampilkan 8 dari 36 siswa</p>
                <div class="flex items-center gap-1">
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] transition hover:bg-[#f1f5f9]">‹</button>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#1d4ed8] text-[12px] font-bold text-white">1</button>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-bold text-[#64748b] transition hover:bg-[#f1f5f9]">2</button>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-bold text-[#64748b] transition hover:bg-[#f1f5f9]">3</button>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-bold text-[#64748b] transition hover:bg-[#f1f5f9]">4</button>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-bold text-[#64748b] transition hover:bg-[#f1f5f9]">5</button>
                    <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[#64748b] transition hover:bg-[#f1f5f9]">›</button>
                </div>
            </div>
        </div>

        {{-- ═══════ MODAL EDIT SISWA ═══════ --}}
        <div x-show="editModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display: none;" x-transition @click.self="editModalOpen = false">
            <div class="flex w-[95%] max-w-lg flex-col rounded-2xl bg-white shadow-2xl" @click.stop>
                {{-- Modal Header --}}
                <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4">
                    <div>
                        <h2 class="text-[18px] font-black text-[#0f172a]">Edit Data Siswa</h2>
                        <p class="mt-0.5 text-[12px] text-[#64748b]">Ubah jabatan organisasi kelas siswa</p>
                    </div>
                    <button @click="editModalOpen = false" class="rounded-full bg-[#f1f5f9] p-2 text-[#64748b] transition hover:bg-[#e2e8f0] hover:text-[#0f172a]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="space-y-5 px-6 py-5">
                    {{-- Student Info Preview --}}
                    <div class="flex items-center gap-4 rounded-xl bg-[#f8fafc] p-4 ring-1 ring-[#e2e8f0]">
                        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-[#1e40af] text-[16px] font-bold text-white" x-text="editForm.init"></div>
                        <div>
                            <p class="text-[16px] font-bold text-[#0f172a]" x-text="editForm.nama"></p>
                            <p class="mt-0.5 text-[12px] text-[#64748b]" x-text="editForm.nis"></p>
                            <p class="mt-0.5 text-[11px] text-[#64748b]" x-text="editForm.jk"></p>
                        </div>
                    </div>

                    {{-- Jabatan Dropdown --}}
                    <div>
                        <label class="mb-2 block text-[11px] font-bold uppercase tracking-[0.15em] text-[#64748b]">
                            Jabatan Organisasi Kelas
                        </label>
                        <select x-model="editForm.role" class="h-12 w-full rounded-lg border border-[#e2e8f0] bg-white px-4 text-[14px] font-semibold text-[#0f172a] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                            <template x-for="role in ['SISWA', 'KETUA KELAS', 'WAKIL KETUA', 'SEKRETARIS 1', 'SEKRETARIS 2', 'BENDAHARA 1', 'BENDAHARA 2']" :key="role">
                                <option :value="role" :disabled="!isRoleAvailable(role) && editForm.role !== role" x-text="role + (limits[role] < 999 ? (isRoleAvailable(role) || editForm.role === role ? ' — Tersedia' : ' — Terisi') : '')"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Kuota Info --}}
                    <div class="rounded-xl bg-[#f8fafc] p-4 ring-1 ring-[#e2e8f0]">
                        <p class="mb-3 text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Kuota Jabatan Saat Ini</p>
                        <div class="grid grid-cols-2 gap-2">
                            <template x-for="role in ['KETUA KELAS', 'WAKIL KETUA', 'SEKRETARIS 1', 'SEKRETARIS 2', 'BENDAHARA 1', 'BENDAHARA 2']" :key="role">
                                <div class="flex items-center justify-between rounded-lg bg-white px-3 py-2 ring-1 ring-[#e2e8f0]">
                                    <span class="text-[11px] font-semibold text-[#475569]" x-text="role"></span>
                                    <span class="text-[12px] font-black" :class="countRole(role) >= limits[role] ? 'text-[#dc2626]' : 'text-[#16a34a]'" x-text="countRole(role) + '/' + limits[role]"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                    <button @click="editModalOpen = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Batal</button>
                    <button @click="confirmSave()" class="flex-1 rounded-lg bg-[#1d4ed8] px-4 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#2563eb]">Simpan Perubahan</button>
                </div>
            </div>
        </div>

        {{-- ═══════ MODAL KONFIRMASI SIMPAN ═══════ --}}
        <div x-show="saveModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display: none;" x-transition @click.self="saveModalOpen = false">
            <div class="flex w-[90%] max-w-sm flex-col rounded-2xl bg-white shadow-2xl">
                <div class="p-6 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#eff6ff] text-[#1d4ed8] mb-4 ring-4 ring-[#dbeafe]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-[18px] font-black text-[#0f172a]">Simpan Perubahan?</h3>
                    <p class="mt-2 text-[13px] leading-[1.8] text-[#64748b]">Jabatan siswa <strong class="text-[#0f172a]" x-text="editForm.nama"></strong> akan diubah menjadi <strong class="text-[#1d4ed8]" x-text="editForm.role"></strong>.</p>
                </div>
                <div class="flex gap-3 bg-[#f8fafc] px-6 py-4 rounded-b-2xl border-t border-[#e2e8f0]">
                    <button @click="saveModalOpen = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Batal</button>
                    <button @click="executeSave()" class="flex-1 rounded-lg bg-[#1d4ed8] px-4 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#2563eb]">Ya, Simpan</button>
                </div>
            </div>
        </div>
    </div>

</x-walikelas-shell>
