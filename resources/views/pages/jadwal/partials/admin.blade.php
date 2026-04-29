{{-- Jadwal Partial: Admin --}}

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('jadwalAdminData', () => ({
            view: 'mingguan',
            showTambah: false,
            showKonflik: false,
            showAssign: false,
            showDetail: false,
            detailData: null,
            tambahForm: { hari:'Senin', mulai:'07:00', selesai:'08:30', mapel:'', guru:'', ruang:'' },
            assignForm: { mapel:'', guru:'', ruang:'' },
            konflikResolved: false,
            daftarMapel: ['Pendidikan Agama', 'Pendidikan Pancasila', 'Bahasa Indonesia', 'Matematika', 'IPAS', 'PJOK', 'Seni Budaya', 'Fisika', 'Kimia', 'Biologi', 'Sosiologi', 'Sejarah', 'Bahasa Inggris'],
            daftarGuru: [
                'Drs. Ahmad Subagja, M.Pd.',
                'Siti Rahmawati, S.Pd.',
                'Bambang Wijaya',
                'Rina Permata, M.Si.',
                'Dr. Agus Salim',
                'Ir. Hendra Gunawan',
                'Budi Setiawan',
                'Dewi Kusuma, S.Sos',
                'Rina Sari, S.E',
                'Siti Aminah, M.Pd',
            ],
            submitTambah() { this.showTambah = false; this.tambahForm = {hari:'Senin',mulai:'07:00',selesai:'08:30',mapel:'',guru:'',ruang:''}; this.$dispatch('toast',{message:'Jadwal baru berhasil ditambahkan!',type:'success'}); },
            submitAssign() { this.showAssign = false; this.assignForm = {mapel:'',guru:'',ruang:''}; this.$dispatch('toast',{message:'Guru berhasil ditugaskan ke slot jadwal!',type:'success'}); },
            resolveKonflik(method) { this.konflikResolved = true; this.showKonflik = false; this.$dispatch('toast',{message:'Konflik berhasil diselesaikan: ' + method,type:'success'}); },
            openDetail(mapel,guru,ruang) { this.detailData = {mapel,guru,ruang}; this.showDetail = true; },
        }));
    });
</script>

<div x-data="jadwalAdminData" class="space-y-6">

    {{-- HEADING --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div><h1 class="text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Jadwal Pelajaran</h1><p class="mt-2 max-w-[480px] text-[14px] leading-[1.8] text-[#475569]">Manajemen alokasi waktu, guru, dan ruang kelas secara komprehensif.</p></div>
        <div class="flex items-center gap-2">
            <button @click="$dispatch('toast',{message:'PDF jadwal berhasil diexport!',type:'success'})" class="flex h-[42px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-5 text-[13px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>Ekspor PDF</button>
            <button @click="showTambah = true" class="flex h-[42px] items-center gap-2 rounded-[8px] bg-[#1d4ed8] px-5 text-[13px] font-bold text-white transition hover:bg-[#1e40af]"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>Tambah Jadwal</button>
        </div>
    </div>

    {{-- VIEW TOGGLE --}}
    <div class="flex items-center justify-end">
        <div class="flex items-center rounded-[8px] border border-[#e2e8f0] bg-white overflow-hidden">
            <button @click="view='mingguan'" class="h-[36px] px-4 text-[12px] font-bold transition" :class="view==='mingguan' ? 'bg-[#1d4ed8] text-white' : 'text-[#64748b] hover:bg-[#f1f5f9]'">Mingguan</button>
            <button @click="view='harian'" class="h-[36px] px-4 text-[12px] font-bold transition" :class="view==='harian' ? 'bg-[#1d4ed8] text-white' : 'text-[#64748b] hover:bg-[#f1f5f9]'">Harian</button>
        </div>
    </div>

    {{-- SCHEDULE GRID --}}
    <div x-show="view==='mingguan'" class="overflow-x-auto rounded-[14px] border border-[#e2e8f0] bg-white">
        <table class="w-full text-[12px]">
            <thead><tr class="bg-[#f8fafc]"><th class="w-[80px] border-r border-[#e2e8f0] px-3 py-3"></th>@foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)<th class="border-r border-[#e2e8f0] px-3 py-3 text-center text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b] last:border-r-0">{{ $day }}</th>@endforeach</tr></thead>
            <tbody>
                <tr class="border-t border-[#e2e8f0]">
                    <td class="border-r border-[#e2e8f0] px-3 py-3 align-top"><p class="text-[13px] font-bold text-[#0f172a]">07:00</p><p class="text-[10px] text-[#94a3b8]">08:30</p></td>
                    <td @click="openDetail('Matematika Wajib','Drs. Bambang Wijaya','Lab 02')" class="border-r border-[#e2e8f0] px-3 py-3 align-top cursor-pointer hover:bg-[#f8fafc] transition"><p class="font-bold text-[#0f172a]">Matematika Wajib</p><p class="text-[11px] text-[#64748b]">Drs. Bambang Wijaya</p><p class="mt-1 text-[10px] text-[#94a3b8]">Lab 02</p></td>
                    <td @click="openDetail('Bahasa Indonesia','Siti Aminah, M.Pd','R. 104')" class="border-r border-[#e2e8f0] px-3 py-3 align-top cursor-pointer hover:bg-[#f8fafc] transition"><p class="font-bold text-[#0f172a]">Bahasa Indonesia</p><p class="text-[11px] text-[#64748b]">Siti Aminah, M.Pd</p><p class="mt-1 text-[10px] text-[#94a3b8]">R. 104</p></td>
                    <td @click="openDetail('Fisika Terapan','Ir. Hendra Gunawan','Lab Fisika')" class="border-r border-[#e2e8f0] px-3 py-3 align-top cursor-pointer hover:bg-[#f8fafc] transition"><p class="font-bold text-[#0f172a]">Fisika Terapan</p><p class="text-[11px] text-[#64748b]">Ir. Hendra Gunawan</p><p class="mt-1 text-[10px] text-[#94a3b8]">Lab Fisika</p></td>
                    <td @click="openDetail('Ekonomi','Rina Sari, S.E','R. 201')" class="border-r border-[#e2e8f0] px-3 py-3 align-top cursor-pointer hover:bg-[#f8fafc] transition"><p class="font-bold text-[#0f172a]">Ekonomi</p><p class="text-[11px] text-[#64748b]">Rina Sari, S.E</p><p class="mt-1 text-[10px] text-[#94a3b8]">R. 201</p></td>
                    <td @click="openDetail('Olahraga','Budi Setiawan','Lapangan')" class="border-r border-[#e2e8f0] px-3 py-3 align-top cursor-pointer hover:bg-[#f8fafc] transition"><p class="font-bold text-[#0f172a]">Olahraga</p><p class="text-[11px] text-[#64748b]">Budi Setiawan</p><p class="mt-1 text-[10px] text-[#94a3b8]">Lapangan</p></td>
                    <td class="px-3 py-3 align-top text-center text-[#94a3b8] italic">Libur</td>
                </tr>
                <tr class="bg-[#f8fafc]"><td class="border-r border-[#e2e8f0] px-3 py-2 text-[10px] font-bold uppercase tracking-[0.08em] text-[#94a3b8]">IST</td><td colspan="6" class="px-3 py-2 text-center text-[10px] font-bold uppercase tracking-[0.2em] text-[#94a3b8]">Istirahat</td></tr>
                <tr class="border-t border-[#e2e8f0]">
                    <td class="border-r border-[#e2e8f0] px-3 py-3 align-top"><p class="text-[13px] font-bold text-[#0f172a]">08:45</p><p class="text-[10px] text-[#94a3b8]">10:15</p></td>
                    <td class="border-r border-[#e2e8f0] px-3 py-3 align-top">
                        <template x-if="!konflikResolved"><div><div class="rounded bg-[#fef2f2] border border-[#fecaca] px-2 py-1"><p class="text-[10px] font-bold text-[#dc2626]">KONFLIK RUANG</p><p class="text-[11px] font-semibold text-[#0f172a]">Kimia Dasar</p><p class="text-[10px] text-[#64748b]">Dr. Agus Salim</p></div><button @click="showKonflik = true" class="mt-1 text-[10px] font-bold text-[#dc2626] cursor-pointer hover:underline">SELESAIKAN</button></div></template>
                        <template x-if="konflikResolved"><div @click="openDetail('Kimia Dasar','Dr. Agus Salim','Lab Kimia')" class="cursor-pointer"><p class="font-bold text-[#0f172a]">Kimia Dasar</p><p class="text-[11px] text-[#64748b]">Dr. Agus Salim</p><p class="mt-1 text-[10px] text-[#94a3b8]">Lab Kimia</p></div></template>
                    </td>
                    <td @click="openDetail('Sejarah Indonesia','Drs. Bambang Wijaya','R. 104')" class="border-r border-[#e2e8f0] px-3 py-3 align-top cursor-pointer hover:bg-[#f8fafc] transition"><p class="font-bold text-[#0f172a]">Sejarah Indonesia</p><p class="text-[11px] text-[#64748b]">Drs. Bambang Wijaya</p><p class="mt-1 text-[10px] text-[#94a3b8]">R. 104</p></td>
                    <td @click="openDetail('Matematika Wajib','Drs. Bambang Wijaya','Lab 02')" class="border-r border-[#e2e8f0] px-3 py-3 align-top cursor-pointer hover:bg-[#f8fafc] transition"><p class="font-bold text-[#0f172a]">Matematika Wajib</p><p class="text-[11px] text-[#64748b]">Drs. Bambang Wijaya</p><p class="mt-1 text-[10px] text-[#94a3b8]">Lab 02</p></td>
                    <td class="border-r border-[#e2e8f0] px-3 py-3 align-top"><div @click="showAssign = true" class="flex flex-col items-center justify-center py-2 cursor-pointer"><div class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-dashed border-[#cbd5e1] text-[#94a3b8] hover:border-[#3b82f6] hover:text-[#3b82f6] transition"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg></div><p class="mt-1 text-[10px] font-bold uppercase text-[#94a3b8]">Assign</p></div></td>
                    <td @click="openDetail('Sosiologi','Dewi Kusuma, S.Sos','R. 302')" class="border-r border-[#e2e8f0] px-3 py-3 align-top cursor-pointer hover:bg-[#f8fafc] transition"><p class="font-bold text-[#0f172a]">Sosiologi</p><p class="text-[11px] text-[#64748b]">Dewi Kusuma, S.Sos</p><p class="mt-1 text-[10px] text-[#94a3b8]">R. 302</p></td>
                    <td class="px-3 py-3 align-top text-center text-[#94a3b8] italic">Libur</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- DAILY VIEW --}}
    <div x-show="view==='harian'" class="rounded-[14px] border border-[#e2e8f0] bg-white p-6" style="display:none">
        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Jadwal Hari Ini — Senin</p>
        <div class="mt-4 space-y-3">
            @foreach ([['time'=>'07:00-08:30','mapel'=>'Matematika Wajib','guru'=>'Drs. Bambang Wijaya','ruang'=>'Lab 02'],['time'=>'08:45-10:15','mapel'=>'Kimia Dasar','guru'=>'Dr. Agus Salim','ruang'=>'Lab Kimia'],['time'=>'10:30-12:00','mapel'=>'Bahasa Inggris','guru'=>'Siti Rahmawati, S.Pd.','ruang'=>'R. 201']] as $j)
                <div class="flex items-center gap-4 rounded-[10px] border border-[#e2e8f0] p-4 transition hover:bg-[#f8fafc]">
                    <div class="w-[100px] flex-none"><p class="text-[14px] font-black text-[#0f172a]">{{ $j['time'] }}</p></div>
                    <div class="h-10 w-[3px] rounded-full bg-[#1d4ed8]"></div>
                    <div class="flex-1"><p class="text-[14px] font-bold text-[#0f172a]">{{ $j['mapel'] }}</p><p class="text-[12px] text-[#64748b]">{{ $j['guru'] }} · {{ $j['ruang'] }}</p></div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ═══ MODAL: Tambah Jadwal ═══ --}}
    <div x-show="showTambah" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showTambah = false">
        <div class="w-[90%] max-w-lg rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4"><h3 class="text-[18px] font-black text-[#0f172a]">Tambah Jadwal Baru</h3><button @click="showTambah = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path></svg></button></div>
            <div class="space-y-4 px-6 py-5">
                <div class="grid gap-4 sm:grid-cols-3">
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Hari</label><select x-model="tambahForm.hari" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"><option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option><option>Jumat</option></select></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Jam Mulai</label><input x-model="tambahForm.mulai" type="time" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Jam Selesai</label><input x-model="tambahForm.selesai" type="time" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"></div>
                </div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Mata Pelajaran</label><select x-model="tambahForm.mapel" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"><option value="" disabled selected>-- Pilih Mapel --</option><template x-for="m in daftarMapel" :key="m"><option :value="m" x-text="m"></option></template></select></div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Guru Pengampu</label><select x-model="tambahForm.guru" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"><option value="" disabled selected>-- Pilih Guru --</option><template x-for="g in daftarGuru" :key="g"><option :value="g" x-text="g"></option></template></select></div>
                    <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Ruangan</label><input x-model="tambahForm.ruang" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="R. 201"></div>
                </div>
            </div>
            <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                <button @click="showTambah = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
                <button @click="submitTambah()" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Tambah</button>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Konflik ═══ --}}
    <div x-show="showKonflik" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showKonflik = false">
        <div class="w-[90%] max-w-sm rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="p-6">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#fef2f2] text-[#dc2626] ring-4 ring-[#fee2e2]"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></div>
                <h3 class="mt-4 text-center text-[18px] font-black text-[#0f172a]">Konflik Ruangan</h3>
                <p class="mt-2 text-center text-[13px] text-[#64748b]"><strong>Kimia Dasar</strong> dan <strong>Biologi</strong> menggunakan ruangan yang sama (R. 104) pada waktu bersamaan.</p>
                <div class="mt-5 space-y-2">
                    <button @click="resolveKonflik('Ganti ke Lab Kimia')" class="flex h-[42px] w-full items-center justify-center rounded-[8px] bg-[#1d4ed8] text-[13px] font-bold text-white hover:bg-[#1e40af]">Pindah ke Lab Kimia</button>
                    <button @click="resolveKonflik('Ubah ke jam 10:30')" class="flex h-[42px] w-full items-center justify-center rounded-[8px] border border-[#e2e8f0] bg-white text-[13px] font-bold text-[#475569] hover:bg-[#f1f5f9]">Ubah Jam ke 10:30</button>
                    <button @click="showKonflik = false" class="flex h-[42px] w-full items-center justify-center rounded-[8px] text-[13px] font-medium text-[#94a3b8] hover:text-[#475569]">Batal</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Assign ═══ --}}
    <div x-show="showAssign" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showAssign = false">
        <div class="w-[90%] max-w-sm rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4"><h3 class="text-[18px] font-black text-[#0f172a]">Assign Jadwal</h3><button @click="showAssign = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path></svg></button></div>
            <div class="space-y-4 px-6 py-5">
                <p class="text-[12px] text-[#64748b]">Slot: <strong>Kamis, 08:45 – 10:15</strong></p>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Mata Pelajaran</label><select x-model="assignForm.mapel" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"><option value="" disabled selected>-- Pilih Mapel --</option><template x-for="m in daftarMapel" :key="m"><option :value="m" x-text="m"></option></template></select></div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Guru</label><select x-model="assignForm.guru" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"><option value="" disabled selected>-- Pilih Guru --</option><template x-for="g in daftarGuru" :key="g"><option :value="g" x-text="g"></option></template></select></div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Ruangan</label><input x-model="assignForm.ruang" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="R. 201"></div>
            </div>
            <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                <button @click="showAssign = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
                <button @click="submitAssign()" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Assign</button>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Detail Jadwal ═══ --}}
    <div x-show="showDetail" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm" style="display:none" x-transition @click.self="showDetail = false">
        <div class="w-[90%] max-w-sm rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4"><h3 class="text-[18px] font-black text-[#0f172a]">Detail Jadwal</h3><button @click="showDetail = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] hover:bg-[#f1f5f9]"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"></path></svg></button></div>
            <div class="space-y-4 px-6 py-5">
                <div><p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Mata Pelajaran</p><p class="mt-1 text-[16px] font-black text-[#0f172a]" x-text="detailData?.mapel"></p></div>
                <div><p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Guru Pengampu</p><p class="mt-1 text-[14px] font-bold text-[#0f172a]" x-text="detailData?.guru"></p></div>
                <div><p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Ruangan</p><p class="mt-1 text-[14px] font-bold text-[#0f172a]" x-text="detailData?.ruang"></p></div>
            </div>
            <div class="flex gap-3 border-t border-[#e2e8f0] bg-[#f8fafc] px-6 py-4 rounded-b-2xl">
                <button @click="showDetail = false; $dispatch('toast',{message:'Mode edit jadwal aktif',type:'info'})" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Edit</button>
                <button @click="showDetail = false; $dispatch('toast',{message:'Jadwal berhasil dihapus',type:'error'})" class="flex-1 rounded-lg bg-[#dc2626] py-2.5 text-[12px] font-bold text-white">Hapus</button>
            </div>
        </div>
    </div>

</div>
