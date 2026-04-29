@extends('layouts.app')
@section('title', 'Akademik')
@section('subtitle', 'Manajemen data akademik')
@section('active', 'akademik')

@section('content')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('akademikData', () => ({
            showUbahPeriode: false,
            showTambahKelas: false,
            showPlotting: false,
            showAntrean: false,
            showDetailKelas: false,
            detailKelas: null,
            periodeForm: { tahun: '2026/2027', semester: 'Ganjil', mulai: '2026-07-15', selesai: '2026-12-20' },
            kelasForm: { nama: '', kapasitas: 32, wali: '' },
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
            kelas: [
                { id:1, nama:'1-A', kapasitas:'32 / 32', pct:100, wali:'Wali Kelas: Drs. Budi Santoso', siswa:['Ahmad F.','Siti A.','Budi D.','Rina M.'] },
                { id:2, nama:'1-B', kapasitas:'28 / 32', pct:88, wali:'Wali Kelas: Rina Permata, M.Si.', siswa:['Dimas P.','Dewi K.','Andi W.'] },
                { id:3, nama:'2-A', kapasitas:'30 / 32', pct:94, wali:'Wali Kelas: Siti Aminah, M.Pd', siswa:['Rizky P.','Bella N.'] },
            ],
            antrean: [
                {init:'AD',name:'Aditya Pratama',nisn:'0092182711',gender:'Laki-laki'},
                {init:'BP',name:'Bella Puspita',nisn:'0092182744',gender:'Perempuan'},
                {init:'DR',name:'Deni Ramadhan',nisn:'0092182788',gender:'Laki-laki'},
                {init:'FH',name:'Fitri Handayani',nisn:'0092182799',gender:'Perempuan'},
                {init:'GS',name:'Galih Saputra',nisn:'0092182801',gender:'Laki-laki'},
            ],
            addKelas() { this.kelas.push({id:Date.now(),nama:this.kelasForm.nama,kapasitas:'0 / '+this.kelasForm.kapasitas,pct:0,wali:'Wali: '+this.kelasForm.wali,siswa:[]}); this.kelasForm={nama:'',kapasitas:32,wali:''}; this.showTambahKelas=false; this.$dispatch('toast',{message:'Kelas baru berhasil ditambahkan!',type:'success'}); },
            openDetail(k) { this.detailKelas = k; this.showDetailKelas = true; },
        }));
    });
</script>

<div x-data="akademikData" class="space-y-6">

    {{-- TAHUN AJARAN --}}
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_280px]">
        <div class="space-y-5">
            <div class="flex items-start justify-between">
                <div><h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a]">Tahun Ajaran 2026/2027</h1><p class="mt-1 text-[14px] text-[#475569]">Linimasa kegiatan akademik berjalan.</p></div>
                <button @click="showUbahPeriode = true" class="flex h-[38px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-4 text-[12px] font-bold uppercase tracking-[0.08em] text-[#334155] transition hover:bg-[#f1f5f9]">Ubah Periode</button>
            </div>
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <div class="flex items-center justify-between"><div class="flex items-center gap-3"><div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#e2e8f0]"><svg class="h-4 w-4 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"></rect><path d="M16 2v4M8 2v4M3 10h18" stroke-width="2" stroke-linecap="round"></path></svg></div><span class="text-[12px] font-bold uppercase tracking-[0.08em] text-[#64748b]">JUL '23</span></div><div class="mx-4 h-[3px] flex-1 rounded-full bg-[#e2e8f0]"><div class="h-full w-[65%] rounded-full bg-[#1d4ed8]"></div></div><span class="text-[12px] font-bold uppercase tracking-[0.08em] text-[#64748b]">JUNI '24</span></div>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-[10px] border border-[#e2e8f0] bg-[#f8fafc] p-4"><p class="text-[13px] font-bold text-[#0f172a]">Semester Ganjil</p><p class="mt-0.5 text-[12px] font-semibold text-[#475569]">Status: Selesai</p><p class="mt-1 text-[11px] text-[#94a3b8]">17 Juli - 22 Des 2023</p></div>
                    <div class="rounded-[10px] border border-[#1d4ed8] bg-[#eff6ff] p-4"><p class="text-[13px] font-bold text-[#1d4ed8]">Semester Genap</p><p class="mt-0.5 text-[12px] font-semibold text-[#475569]">Status: Aktif</p><p class="mt-1 text-[11px] text-[#94a3b8]">08 Jan - 21 Juni 2024</p></div>
                </div>
            </div>
        </div>
        <div class="space-y-3">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Detail Semester</p>
            <div class="rounded-[10px] border border-[#e2e8f0] bg-white p-4"><p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Minggu Berjalan</p><p class="mt-1"><span class="text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">14</span> <span class="text-[14px] font-medium text-[#64748b]">dari 20</span></p></div>
            <div class="rounded-[10px] border border-[#e2e8f0] bg-white p-4"><p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Target Kurikulum</p><div class="mt-2 h-[6px] overflow-hidden rounded-full bg-[#e2e8f0]"><div class="h-full w-[78%] rounded-full bg-[#1d4ed8]"></div></div><p class="mt-1 text-right text-[12px] font-bold text-[#1d4ed8]">78%</p></div>
            <div class="rounded-[10px] bg-[#0f172a] p-4 text-white"><div class="flex items-center justify-between"><svg class="h-5 w-5 text-[#60a5fa]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg><span class="rounded bg-white/20 px-2 py-0.5 text-[9px] font-bold uppercase">Penting</span></div><p class="mt-3 text-[13px] leading-[1.6] font-medium">Pengisian Rapor Semester Genap dimulai dalam 12 hari.</p></div>
        </div>
    </div>

    {{-- PLOTTING --}}
    <div>
        <div class="flex items-center justify-between">
            <div><h2 class="text-[22px] font-black tracking-[-0.04em] text-[#0f172a]">Plotting Siswa & Kelas</h2><p class="mt-1 text-[13px] text-[#475569]">Distribusi kapasitas dan pembagian rombongan belajar.</p></div>
            <div class="flex items-center gap-2">
                <button @click="showPlotting = true" class="flex h-[38px] items-center gap-2 rounded-[8px] bg-[#1d4ed8] px-4 text-[12px] font-bold text-white transition hover:bg-[#1e40af]"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path></svg>Plotting Otomatis</button>
            </div>
        </div>
        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <template x-for="k in kelas" :key="k.id">
                <div @click="openDetail(k)" class="cursor-pointer rounded-[14px] border border-[#e2e8f0] bg-white p-5 transition hover:shadow-md hover:border-[#3b82f6]">
                    <div class="mb-3 flex h-8 w-8 items-center justify-center rounded-[6px] bg-[#f1f5f9]"><svg class="h-4 w-4 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></div>
                    <p class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a]" x-text="'Kelas ' + k.nama"></p>
                    <p class="text-[11px] font-semibold text-[#64748b]">Tahun Ajaran 2026/2027</p>
                    <div class="mt-3 flex items-center justify-between text-[12px]"><span class="font-semibold text-[#334155]">Kapasitas</span><span class="font-bold text-[#0f172a]" x-text="k.kapasitas"></span></div>
                    <div class="mt-1.5 h-[4px] overflow-hidden rounded-full bg-[#e2e8f0]"><div class="h-full rounded-full" :class="k.pct >= 100 ? 'bg-[#dc2626]' : 'bg-[#1d4ed8]'" :style="'width:'+Math.min(k.pct,100)+'%'"></div></div>
                    <p class="mt-2 text-[11px]" :class="k.pct >= 100 ? 'font-bold text-[#dc2626]' : 'text-[#64748b]'" x-text="k.wali"></p>
                </div>
            </template>
            <a href="{{ route('kelas') }}" class="flex flex-col items-center justify-center rounded-[14px] border-2 border-dashed border-[#cbd5e1] bg-[#f8fafc] p-5 transition hover:border-[#3b82f6] cursor-pointer">
                <div class="flex h-10 w-10 items-center justify-center rounded-[8px] bg-[#e2e8f0] text-[#64748b]"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></div>
                <p class="mt-2 text-[12px] font-bold uppercase tracking-[0.08em] text-[#64748b]">Kelola Data Kelas</p>
            </a>
        </div>
    </div>

    {{-- ANTREAN --}}
    <div class="rounded-[14px] border border-[#e2e8f0] bg-white overflow-hidden">
        <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4">
            <div><p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Antrean Plotting Siswa</p></div>
            <span class="rounded bg-[#dc2626] px-2.5 py-1 text-[10px] font-bold text-white" x-text="antrean.length + ' SISWA'"></span>
        </div>
        <table class="w-full text-[13px]">
            <thead><tr class="border-b border-[#e2e8f0] bg-[#f8fafc]"><th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NISN</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Gender</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Peminatan</th></tr></thead>
            <tbody>
                <template x-for="s in antrean.slice(0,3)" :key="s.nisn">
                    <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                        <td class="px-6 py-3.5"><div class="flex items-center gap-3"><div class="flex h-8 w-8 flex-none items-center justify-center rounded-[6px] bg-[#eff6ff] text-[10px] font-bold text-[#1d4ed8]" x-text="s.init"></div><span class="font-bold text-[#0f172a]" x-text="s.name"></span></div></td>
                        <td class="px-4 py-3.5 font-mono text-[12px] text-[#64748b]" x-text="s.nisn"></td>
                        <td class="px-4 py-3.5 text-[#475569]" x-text="s.gender"></td>
                        <td class="px-4 py-3.5"><span class="rounded bg-[#1d4ed8] px-2 py-0.5 text-[10px] font-bold text-white" x-text="s.peminatan"></span></td>
                    </tr>
                </template>
            </tbody>
        </table>
        <div class="border-t border-[#e2e8f0] px-6 py-3 text-center">
            <button @click="showAntrean = true" class="text-[12px] font-bold uppercase tracking-[0.08em] text-[#1d4ed8] transition hover:text-[#1e40af]" x-text="'Lihat Semua Antrean (' + (antrean.length - 3) + ' Lainnya)'"></button>
        </div>
    </div>

    {{-- ═══ MODAL: Ubah Periode ═══ --}}
    <x-modal alpineShow="showUbahPeriode" title="Ubah Periode Akademik" maxWidth="md">
        <div class="space-y-4">
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Tahun Ajaran</label><input x-model="periodeForm.tahun" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"></div>
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Semester</label><div class="mt-2 flex gap-4"><label class="flex items-center gap-2 text-[14px] font-medium cursor-pointer"><input type="radio" value="Ganjil" x-model="periodeForm.semester" class="accent-[#0f172a]"> Ganjil</label><label class="flex items-center gap-2 text-[14px] font-medium cursor-pointer"><input type="radio" value="Genap" x-model="periodeForm.semester" class="accent-[#0f172a]"> Genap</label></div></div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Tanggal Mulai</label><input x-model="periodeForm.mulai" type="date" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"></div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Tanggal Selesai</label><input x-model="periodeForm.selesai" type="date" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"></div>
            </div>
        </div>
        <x-slot:footer>
            <button @click="showUbahPeriode = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
            <button @click="showUbahPeriode = false; $dispatch('toast',{message:'Periode akademik berhasil diperbarui!',type:'success'})" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Simpan</button>
        </x-slot:footer>
    </x-modal>

    {{-- ═══ MODAL: Tambah Kelas ═══ --}}
    <x-modal alpineShow="showTambahKelas" title="Tambah Kelas Baru" maxWidth="md">
        <div class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Kelas</label><input x-model="kelasForm.nama" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" placeholder="X-D"></div>
                <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kapasitas</label><input x-model="kelasForm.kapasitas" type="number" class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20"></div>
            </div>
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Jurusan / Peminatan</label><select x-model="kelasForm.jurusan" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"><option>SAINTEK / MIPA</option><option>SOSHUM / IPS</option></select></div>
            <div><label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Wali Kelas</label><select x-model="kelasForm.wali" class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]"><option value="" disabled selected>-- Pilih Guru --</option><template x-for="g in daftarGuru" :key="g"><option :value="g" x-text="g"></option></template></select></div>
        </div>
        <x-slot:footer>
            <button @click="showTambahKelas = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
            <button @click="addKelas()" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Tambah Kelas</button>
        </x-slot:footer>
    </x-modal>

    {{-- ═══ MODAL: Plotting Otomatis ═══ --}}
    <x-confirm-dialog
        alpineShow="showPlotting"
        type="warning"
        title="Plotting Otomatis"
        message="Sistem akan menempatkan <strong x-text='antrean.length'></strong> siswa dalam antrean ke kelas yang tersedia berdasarkan peminatannya."
        confirmText="Jalankan"
        confirmAction="antrean = []; showPlotting = false; $dispatch('toast',{message:'Plotting otomatis selesai! Semua siswa telah ditempatkan.',type:'success'})"
    />

    {{-- ═══ MODAL: Semua Antrean ═══ --}}
    <x-modal alpineShow="showAntrean" title="Semua Antrean Plotting" maxWidth="2xl">
        <div class="max-h-[60vh] overflow-y-auto -mx-6">
            <table class="w-full text-[13px]">
                <thead><tr class="border-b border-[#e2e8f0] bg-[#f8fafc]"><th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NISN</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Gender</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Peminatan</th></tr></thead>
                <tbody><template x-for="s in antrean" :key="s.nisn"><tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]"><td class="px-6 py-3"><div class="flex items-center gap-3"><div class="flex h-8 w-8 flex-none items-center justify-center rounded-[6px] bg-[#eff6ff] text-[10px] font-bold text-[#1d4ed8]" x-text="s.init"></div><span class="font-bold text-[#0f172a]" x-text="s.name"></span></div></td><td class="px-4 py-3 font-mono text-[12px] text-[#64748b]" x-text="s.nisn"></td><td class="px-4 py-3 text-[#475569]" x-text="s.gender"></td><td class="px-4 py-3"><span class="rounded bg-[#1d4ed8] px-2 py-0.5 text-[10px] font-bold text-white" x-text="s.peminatan"></span></td></tr></template></tbody>
            </table>
        </div>
    </x-modal>

    {{-- ═══ MODAL: Detail Kelas ═══ --}}
    <x-modal alpineShow="showDetailKelas" title="Detail Kelas" maxWidth="md">
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div><p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Jurusan</p><p class="mt-1 text-[14px] font-bold text-[#0f172a]" x-text="detailKelas?.jurusan"></p></div>
                <div><p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kapasitas</p><p class="mt-1 text-[14px] font-bold text-[#0f172a]" x-text="detailKelas?.kapasitas"></p></div>
            </div>
            <div><p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Wali Kelas</p><p class="mt-1 text-[14px] font-bold text-[#0f172a]" x-text="detailKelas?.wali"></p></div>
            <div><p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Daftar Siswa</p>
                <div class="mt-2 space-y-2 max-h-[40vh] overflow-y-auto"><template x-for="(nama, i) in (detailKelas?.siswa || [])" :key="i"><div class="flex items-center gap-2 rounded-lg bg-[#f8fafc] px-3 py-2"><div class="h-6 w-6 flex items-center justify-center rounded-full bg-[#e2e8f0] text-[9px] font-bold text-[#475569]" x-text="i+1"></div><span class="text-[13px] font-medium text-[#0f172a]" x-text="nama"></span></div></template></div>
            </div>
        </div>
    </x-modal>
</div>
@endsection
