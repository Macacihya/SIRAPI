<x-walikelas-shell
    :user="auth()->user()"
    active="jadwal-kelas"
    title="Jadwal Kelas"
    subtitle="Selamat datang di Panel Wali Kelas"
>
    <div class="space-y-8" x-data="{
        editMode: false,
        saveModalOpen: false,
        deleteModalOpen: false,
        dayToDelete: null,
        days: [
            { day: 'SENIN', slots: [
                { time: '07:30 - 09:00', subject: 'Matematika Peminatan', teacher: 'Drs. Bambang H.', isBreak: false },
                { time: '09:00 - 10:30', subject: 'Fisika', teacher: 'Ir. Siti Aminah', isBreak: false },
                { time: '10:30 - 11:00', subject: 'ISTIRAHAT', teacher: '', isBreak: true },
                { time: '11:00 - 12:30', subject: 'Biologi', teacher: 'Dr. Heru Prasetyo', isBreak: false },
            ]},
            { day: 'SELASA', slots: [
                { time: '07:30 - 09:00', subject: 'Kimia', teacher: 'Hj. Rina Wati, M.Si', isBreak: false },
                { time: '09:00 - 10:30', subject: 'Bahasa Inggris', teacher: 'Sarah Jane, M.Pd', isBreak: false },
                { time: '10:30 - 11:00', subject: 'ISTIRAHAT', teacher: '', isBreak: true },
                { time: '11:00 - 12:30', subject: 'Bahasa Indonesia', teacher: 'Drs. M. Taufik', isBreak: false },
            ]},
            { day: 'RABU', slots: [
                { time: '07:30 - 09:00', subject: 'Olahraga', teacher: 'Slamet Raharjo, S.Pd', isBreak: false },
                { time: '09:00 - 10:30', subject: 'PKn', teacher: 'Drs. H. Anwar', isBreak: false },
                { time: '10:30 - 11:00', subject: 'ISTIRAHAT', teacher: '', isBreak: true },
                { time: '11:00 - 12:30', subject: 'Seni Budaya', teacher: 'Larasati, S.Sn', isBreak: false },
            ]},
            { day: 'KAMIS', slots: [
                { time: '07:30 - 09:00', subject: 'Agama Islam', teacher: 'H. Syamsul Maarif, Lc', isBreak: false },
                { time: '09:00 - 10:30', subject: 'Matematika Wajib', teacher: 'Drs. Bambang H.', isBreak: false },
                { time: '10:30 - 11:00', subject: 'ISTIRAHAT', teacher: '', isBreak: true },
                { time: '11:00 - 12:30', subject: 'TIK', teacher: 'Budi Darmawan, S.Kom', isBreak: false },
            ]},
            { day: 'JUMAT', slots: [
                { time: '07:30 - 08:30', subject: 'Pembinaan Wali Kelas', teacher: 'Drs. Ahmad Subarjo', isBreak: false },
                { time: '08:30 - 10:00', subject: 'Sejarah Indonesia', teacher: 'Nurul Huda, M.Pd', isBreak: false },
                { time: '10:00 - 10:30', subject: 'ISTIRAHAT', teacher: '', isBreak: true },
                { time: '10:30 - 11:30', subject: 'Ekonomi (Lintas Minat)', teacher: 'Reni Susanti, SE', isBreak: false },
            ]}
        ],
        addDay() {
            this.days.push({ day: 'HARI BARU', slots: [] });
        },
        removeDay(index) {
            this.dayToDelete = index;
            this.deleteModalOpen = true;
        },
        executeDeleteDay() {
            if (this.dayToDelete !== null) {
                this.days.splice(this.dayToDelete, 1);
                this.dayToDelete = null;
                this.deleteModalOpen = false;
            }
        },
        addSlot(dayIndex, isBreak = false) {
            this.days[dayIndex].slots.push({
                time: '00:00 - 00:00',
                subject: isBreak ? 'ISTIRAHAT' : 'Pelajaran Baru',
                teacher: isBreak ? '' : 'Nama Guru',
                isBreak: isBreak
            });
        },
        removeSlot(dayIndex, slotIndex) {
            this.days[dayIndex].slots.splice(slotIndex, 1);
        }
    }">
        {{-- Title + Actions --}}
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a] sm:text-[36px]">Jadwal Pelajaran Kelas</h1>
                <div class="mt-2 flex flex-wrap gap-2">
                    <span class="rounded-full border border-[#e2e8f0] px-3 py-1 text-[11px] font-bold text-[#475569]">XII IPA 1</span>
                    <span class="rounded-full border border-[#e2e8f0] px-3 py-1 text-[11px] font-bold text-[#475569]">T.A 2023/2024</span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button class="flex items-center gap-2 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Cetak Jadwal
                </button>
                <button @click="editMode = true" class="flex items-center gap-2 rounded-lg bg-[#0f172a] px-4 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#1e293b]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Edit Jadwal
                </button>
            </div>
        </div>

        {{-- Schedule Grid (View Mode) --}}
        <div class="flex gap-4 overflow-x-auto pb-4 custom-scrollbar items-start">
            <template x-for="(day, dIndex) in days" :key="dIndex">
                <div class="flex flex-col h-[460px] w-[280px] shrink-0 rounded-xl bg-white p-4 ring-1 ring-[#e2e8f0]">
                    {{-- Header Hari --}}
                    <div class="flex items-center justify-between border-b border-[#e2e8f0] pb-3 shrink-0">
                        <h3 class="text-[13px] font-black uppercase tracking-[0.1em] text-[#0f172a]" x-text="day.day"></h3>
                        <div class="flex items-center gap-2 shrink-0">
                            <svg class="h-4 w-4 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2" stroke-width="2"/><path d="M16 3v4M8 3v4M3 10h18" stroke-linecap="round" stroke-width="2"/></svg>
                        </div>
                    </div>

                    {{-- List Jam (Scrollable) --}}
                    <div class="mt-3 space-y-3 overflow-y-auto custom-scrollbar pr-1 flex-1">
                        <template x-for="(slot, sIndex) in day.slots" :key="sIndex">
                            <div>
                                {{-- Jika Istirahat --}}
                                <template x-if="slot.isBreak">
                                    <div class="rounded-lg bg-[#f1f5f9] p-2 text-center">
                                        <p class="text-[9px] font-semibold text-[#64748b]" x-text="slot.time"></p>
                                        <div class="py-1 text-[10px] font-bold uppercase tracking-[0.15em] text-[#94a3b8]" x-text="slot.subject"></div>
                                    </div>
                                </template>

                                {{-- Jika Pelajaran Normal --}}
                                <template x-if="!slot.isBreak">
                                    <div class="rounded-lg bg-[#f8fafc] p-3 border border-transparent">
                                        <p class="text-[10px] font-semibold text-[#64748b]" x-text="slot.time"></p>
                                        <p class="mt-1 text-[13px] font-bold text-[#0f172a]" x-text="slot.subject"></p>
                                        <p class="mt-0.5 text-[11px] text-[#64748b]" x-text="slot.teacher"></p>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        {{-- Edit Modal --}}
        <div x-show="editMode" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display: none;" x-transition @click.self="editMode = false">
            <div class="flex max-h-[90vh] w-[95%] max-w-7xl flex-col rounded-2xl bg-[#f8fafc] shadow-2xl">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between border-b border-[#e2e8f0] bg-white px-6 py-4 rounded-t-2xl shrink-0">
                    <div>
                        <h2 class="text-[20px] font-black text-[#0f172a]">Edit Jadwal Pelajaran</h2>
                        <p class="text-[12px] text-[#64748b]">Sesuaikan mata pelajaran, jam, dan guru pengajar.</p>
                    </div>
                    <button @click="editMode = false" class="rounded-full bg-[#f1f5f9] p-2 text-[#64748b] hover:bg-[#e2e8f0] hover:text-[#0f172a]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="flex-1 overflow-x-auto overflow-y-auto p-6 bg-[#f1f5f9] custom-scrollbar">
                    <div class="flex gap-4 items-start min-w-max">
                        <template x-for="(day, dIndex) in days" :key="dIndex">
                            <div class="w-[280px] shrink-0 rounded-xl bg-white p-4 ring-2 ring-[#3b82f6] flex flex-col h-[65vh]">
                                {{-- Header Hari --}}
                                <div class="flex items-center justify-between border-b border-[#e2e8f0] pb-3 shrink-0">
                                    <input type="text" x-model="day.day" class="w-full bg-[#f8fafc] border border-[#e2e8f0] font-black uppercase tracking-[0.1em] text-[13px] text-[#0f172a] rounded px-2 py-1 mr-2 outline-none focus:border-[#3b82f6]">
                                    <button @click="removeDay(dIndex)" class="shrink-0 text-[#dc2626] hover:bg-[#fef2f2] rounded p-1 transition" title="Hapus Hari">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>

                                {{-- List Jam (Editable) --}}
                                <div class="mt-3 space-y-3 overflow-y-auto custom-scrollbar pr-1 flex-1">
                                    <template x-for="(slot, sIndex) in day.slots" :key="sIndex">
                                        <div>
                                            {{-- Jika Istirahat --}}
                                            <template x-if="slot.isBreak">
                                                <div class="group relative rounded-lg bg-[#f1f5f9] p-2 text-center border border-dashed border-[#cbd5e1] hover:border-[#94a3b8]">
                                                    <div class="space-y-2">
                                                        <input type="text" x-model="slot.time" placeholder="Jam" class="w-full text-center text-[10px] font-semibold text-[#475569] bg-white border border-[#e2e8f0] rounded px-1 py-0.5 outline-none focus:border-[#3b82f6]">
                                                        <input type="text" x-model="slot.subject" placeholder="Keterangan" class="w-full text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b] bg-white border border-[#e2e8f0] rounded px-1 py-0.5 outline-none focus:border-[#3b82f6]">
                                                    </div>
                                                    <button @click="removeSlot(dIndex, sIndex)" class="absolute -right-2 -top-2 hidden md:flex h-5 w-5 items-center justify-center rounded-full bg-[#dc2626] text-white shadow hover:bg-[#b91c1c] transition z-10">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </div>
                                            </template>

                                            {{-- Jika Pelajaran Normal --}}
                                            <template x-if="!slot.isBreak">
                                                <div class="group relative rounded-lg bg-white p-3 border border-[#e2e8f0] hover:ring-1 hover:ring-[#3b82f6]/30 transition">
                                                    <div class="space-y-1.5">
                                                        <input type="text" x-model="slot.time" placeholder="Jam" class="w-full text-[10px] font-semibold text-[#64748b] bg-[#f8fafc] border border-[#e2e8f0] rounded px-2 py-1 outline-none focus:border-[#3b82f6]">
                                                        <input type="text" x-model="slot.subject" placeholder="Mata Pelajaran" class="w-full text-[13px] font-bold text-[#0f172a] bg-[#f8fafc] border border-[#e2e8f0] rounded px-2 py-1 outline-none focus:border-[#3b82f6]">
                                                        <input type="text" x-model="slot.teacher" placeholder="Nama Guru" class="w-full text-[11px] text-[#64748b] bg-[#f8fafc] border border-[#e2e8f0] rounded px-2 py-1 outline-none focus:border-[#3b82f6]">
                                                    </div>
                                                    <button @click="removeSlot(dIndex, sIndex)" class="absolute -right-2 -top-2 hidden md:flex h-5 w-5 items-center justify-center rounded-full bg-[#dc2626] text-white shadow hover:bg-[#b91c1c] transition z-10">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>

                                {{-- Tombol Tambah Row --}}
                                <div class="mt-3 flex gap-2 shrink-0">
                                    <button @click="addSlot(dIndex, false)" class="flex-1 rounded border border-dashed border-[#cbd5e1] py-1.5 text-[10px] font-bold text-[#64748b] hover:border-[#3b82f6] hover:bg-[#eff6ff] hover:text-[#1d4ed8] transition">
                                        + Pelajaran
                                    </button>
                                    <button @click="addSlot(dIndex, true)" class="flex-1 rounded border border-dashed border-[#cbd5e1] py-1.5 text-[10px] font-bold text-[#64748b] hover:border-[#3b82f6] hover:bg-[#eff6ff] hover:text-[#1d4ed8] transition">
                                        + Istirahat
                                    </button>
                                </div>
                            </div>
                        </template>

                        {{-- Tombol Tambah Hari --}}
                        <div class="w-[280px] shrink-0 h-[65vh]">
                            <button @click="addDay()" class="h-full w-full rounded-xl border-2 border-dashed border-[#cbd5e1] flex flex-col items-center justify-center text-[#94a3b8] transition hover:border-[#3b82f6] hover:bg-white hover:text-[#1d4ed8]">
                                <svg class="mb-2 h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span class="text-[12px] font-bold uppercase tracking-[0.1em]">Tambah Hari</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-end gap-3 border-t border-[#e2e8f0] bg-white px-6 py-4 rounded-b-2xl shrink-0">
                    <button @click="editMode = false" class="rounded-lg border border-[#e2e8f0] bg-white px-4 py-2 text-[13px] font-bold text-[#475569] transition hover:bg-[#f8fafc]">Batal</button>
                    <button @click="saveModalOpen = true" class="rounded-lg bg-[#16a34a] px-5 py-2 text-[13px] font-bold text-white transition hover:bg-[#15803d]">Simpan Perubahan</button>
                </div>
            </div>
        </div>

        {{-- Bottom Section --}}
        <div class="grid gap-6 lg:grid-cols-[1fr_300px]">
            {{-- Teacher Table dgn Scroll Vertikal --}}
            <div class="space-y-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <h2 class="text-[22px] font-black tracking-[-0.03em] text-[#0f172a] sm:text-[28px]">Daftar Guru Pengajar Semester Ganjil</h2>
                    <p class="text-[11px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Total: 12 Mata Pelajaran</p>
                </div>
                
                {{-- Container yang bisa di scroll vertikal dan horizontal --}}
                <div class="rounded-xl bg-white ring-1 ring-[#e2e8f0] flex flex-col max-h-[380px] overflow-hidden">
                    <div class="overflow-x-auto overflow-y-auto flex-1 custom-scrollbar">
                        <table class="w-full text-left text-[13px] relative border-collapse">
                            <thead class="sticky top-0 z-10 bg-[#f8fafc] shadow-sm">
                                <tr>
                                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b] border-b border-[#e2e8f0]">Mata Pelajaran</th>
                                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b] border-b border-[#e2e8f0]">Guru Pengajar</th>
                                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b] border-b border-[#e2e8f0]">Kontak</th>
                                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b] border-b border-[#e2e8f0]">Ruang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Memperbanyak dummy data agar scrollnya terlihat
                                    $gurus = [
                                        ['mapel' => 'Matematika Peminatan', 'guru' => 'Drs. Bambang H.', 'kontak' => '0812-3344-XXXX', 'ruang' => 'Lab Mat A'],
                                        ['mapel' => 'Fisika', 'guru' => 'Ir. Siti Aminah', 'kontak' => '0813-5566-XXXX', 'ruang' => 'Lab Fisika'],
                                        ['mapel' => 'Kimia', 'guru' => 'Hj. Rina Wati, M.Si', 'kontak' => '0811-7788-XXXX', 'ruang' => 'Lab Kimia'],
                                        ['mapel' => 'Biologi', 'guru' => 'Dr. Heru Prasetyo', 'kontak' => '0819-9900-XXXX', 'ruang' => 'Lab Biologi'],
                                        ['mapel' => 'Bahasa Inggris', 'guru' => 'Sarah Jane, M.Pd', 'kontak' => '0852-1122-XXXX', 'ruang' => 'Ruang 12'],
                                        ['mapel' => 'Bahasa Indonesia', 'guru' => 'Drs. M. Taufik', 'kontak' => '0812-8877-XXXX', 'ruang' => 'Ruang 18'],
                                        ['mapel' => 'Olahraga', 'guru' => 'Slamet Raharjo, S.Pd', 'kontak' => '0877-3311-XXXX', 'ruang' => 'Lap. Utama'],
                                        ['mapel' => 'PKn', 'guru' => 'Drs. H. Anwar', 'kontak' => '0813-2211-XXXX', 'ruang' => 'Ruang 15'],
                                        ['mapel' => 'Seni Budaya', 'guru' => 'Larasati, S.Sn', 'kontak' => '0899-4455-XXXX', 'ruang' => 'Lab Seni'],
                                        ['mapel' => 'Agama Islam', 'guru' => 'H. Syamsul Maarif, Lc', 'kontak' => '0821-6677-XXXX', 'ruang' => 'Masjid/R.08'],
                                        ['mapel' => 'Matematika Wajib', 'guru' => 'Drs. Bambang H.', 'kontak' => '0812-3344-XXXX', 'ruang' => 'Lab Mat B'],
                                        ['mapel' => 'TIK', 'guru' => 'Budi Darmawan, S.Kom', 'kontak' => '0852-9988-XXXX', 'ruang' => 'Lab Komputer'],
                                    ];
                                @endphp
                                @foreach ($gurus as $g)
                                    <tr class="border-b border-[#f1f5f9] hover:bg-[#f8fafc] transition">
                                        <td class="px-5 py-3.5 font-bold text-[#0f172a]">{{ $g['mapel'] }}</td>
                                        <td class="px-5 py-3.5 text-[#475569]">{{ $g['guru'] }}</td>
                                        <td class="px-5 py-3.5 text-[#475569]">{{ $g['kontak'] }}</td>
                                        <td class="px-5 py-3.5 text-[#475569]">{{ $g['ruang'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Right Sidebar Cards --}}
            <div class="space-y-4">
                {{-- Catatan Perwalian --}}
                <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Catatan Perwalian</p>
                    <blockquote class="mt-3 text-[13px] italic leading-[1.8] text-[#334155]">
                        "Pastikan seluruh siswa sudah berada di kelas 5 menit sebelum jam pelajaran pertama dimulai. Hari Senin diwajibkan menggunakan atribut lengkap untuk Upacara Bendera."
                    </blockquote>
                    <div class="mt-4 flex items-center gap-2 text-[11px] font-bold text-[#64748b]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#1d4ed8]"></span>
                        Update: 12 Okt 2023
                    </div>
                </div>

                {{-- Ringkasan Beban Jam --}}
                <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Ringkasan Beban Jam</p>
                    <div class="mt-3 flex items-end gap-2">
                        <span class="text-[12px] text-[#475569]">Total Jam Pelajaran</span>
                        <span class="ml-auto text-[36px] font-black leading-none text-[#0f172a]">42</span>
                        <span class="pb-1 text-[11px] font-bold uppercase text-[#64748b]">JP/Minggu</span>
                    </div>
                    <div class="mt-4 border-t border-[#e2e8f0] pt-3">
                        <div class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider text-[#64748b]">
                            <span>Kapasitas</span>
                            <span>85% Terisi</span>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-[#e2e8f0]">
                            <div class="h-full w-[85%] rounded-full bg-[#1d4ed8]"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    {{-- FAB --}}
    <button class="fixed bottom-6 right-6 z-20 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#0f172a] text-white shadow-lg transition hover:bg-[#1e293b]">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 5v14m-7-7h14" stroke-width="2" stroke-linecap="round"/></svg>
    </button>

    {{-- MODAL HAPUS HARI --}}
    <div x-show="deleteModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display: none;" x-transition @click.self="deleteModalOpen = false">
        <div class="flex w-[90%] max-w-sm flex-col rounded-2xl bg-white shadow-2xl">
            <div class="p-6 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#fef2f2] text-[#dc2626] mb-4 ring-4 ring-[#fee2e2]">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h3 class="text-[18px] font-black text-[#0f172a]">Hapus Hari Ini?</h3>
                <p class="mt-2 text-[13px] leading-[1.8] text-[#64748b]">Hari ini dan seluruh jadwal pelajaran di dalamnya akan dihapus. Aksi ini tidak dapat dibatalkan.</p>
            </div>
            <div class="flex gap-3 bg-[#f8fafc] px-6 py-4 rounded-b-2xl border-t border-[#e2e8f0]">
                <button @click="deleteModalOpen = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Batal</button>
                <button @click="executeDeleteDay()" class="flex-1 rounded-lg bg-[#dc2626] px-4 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#b91c1c]">Ya, Hapus</button>
            </div>
        </div>
    </div>

    {{-- MODAL SIMPAN JADWAL --}}
    <div x-show="saveModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display: none;" x-transition @click.self="saveModalOpen = false">
        <div class="flex w-[90%] max-w-sm flex-col rounded-2xl bg-white shadow-2xl">
            <div class="p-6 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#eff6ff] text-[#1d4ed8] mb-4 ring-4 ring-[#dbeafe]">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-[18px] font-black text-[#0f172a]">Simpan Jadwal?</h3>
                <p class="mt-2 text-[13px] leading-[1.8] text-[#64748b]">Jadwal terbaru akan diterapkan ke kelas ini. Pastikan tidak ada bentrok waktu.</p>
            </div>
            <div class="flex gap-3 bg-[#f8fafc] px-6 py-4 rounded-b-2xl border-t border-[#e2e8f0]">
                <button @click="saveModalOpen = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Batal</button>
                <button @click="saveModalOpen = false; editMode = false; alert('Jadwal berhasil disimpan!')" class="flex-1 rounded-lg bg-[#1d4ed8] px-4 py-2.5 text-[12px] font-bold text-white transition hover:bg-[#2563eb]">Ya, Simpan</button>
            </div>
        </div>
    </div>

    </div>
</x-walikelas-shell>
