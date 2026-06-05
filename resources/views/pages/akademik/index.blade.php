@extends('layouts.app')
@section('title', 'Akademik')
@section('subtitle', 'Manajemen data akademik')
@section('active', 'akademik')

@section('content')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('akademikData', () => ({
            showKelolaPeriode: false,
            showTambahPeriode: false,
            showTambahKelas: false,
            showPlotting: false,
            showAntrean: false,
            showDetailKelas: false,
            detailKelas: null,
            
            showConfirmDeleteTahun: false,
            deleteTahunId: null,
            deleteTahunLabel: '',
            
            // Raw data from database
            kelas: @json($kelas),
            antrean: @json($antrean),
            
            openDetail(k) { 
                this.detailKelas = k; 
                this.showDetailKelas = true; 
            },
            runPlotting() {
                document.getElementById('formPlottingOtomatis').submit();
            },
            confirmDeleteTahun(id, label) {
                this.deleteTahunId = id;
                this.deleteTahunLabel = label;
                this.showConfirmDeleteTahun = true;
            },
            runDeleteTahun() {
                if (this.deleteTahunId) {
                    const form = document.getElementById('formDeleteTahunAjaran');
                    form.action = `/akademik/tahun-ajaran/${this.deleteTahunId}`;
                    form.submit();
                }
            }
        }));
    });
</script>

<div x-data="akademikData" class="space-y-6">

    @if(session('success'))
    <div class="rounded-[10px] border border-[#a7f3d0] bg-[#ecfdf5] px-4 py-3 text-[13px] font-semibold text-[#059669]">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="rounded-[10px] border border-red-200 bg-red-50 px-4 py-3 text-[13px] font-semibold text-red-600">
        {{ $errors->first() }}
    </div>
    @endif

    {{-- TAHUN AJARAN --}}
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_280px]">
        <div class="space-y-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a]">
                        @if($selectedTahun)
                            Tahun Ajaran {{ $selectedTahun->tahun_mulai }}/{{ $selectedTahun->tahun_selesai }} ({{ $selectedTahun->semester }})
                        @else
                            Belum Ada Periode Aktif
                        @endif
                    </h1>
                    <p class="mt-1 text-[14px] text-[#475569]">Linimasa kegiatan akademik berjalan.</p>
                </div>
                <div class="flex items-center gap-2">
                    <form method="GET" action="{{ route('akademik') }}" id="formSwitchTahun">
                        <select 
                            name="tahun_ajaran_id" 
                            onchange="document.getElementById('formSwitchTahun').submit()" 
                            class="h-[38px] rounded-[8px] border border-[#e2e8f0] bg-white px-3 text-[13px] font-semibold text-[#334155] outline-none focus:border-[#3b82f6] transition"
                        >
                            @foreach($tahunAjarans as $ta)
                                <option value="{{ $ta->id }}" {{ $ta->id == $tahunAjaranId ? 'selected' : '' }}>
                                    TA {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} - {{ $ta->semester }} {!! $ta->is_active ? '(&bull; Aktif)' : '' !!}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    <button @click="showKelolaPeriode = true" class="flex h-[38px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-4 text-[12px] font-bold uppercase tracking-[0.08em] text-[#334155] transition hover:bg-[#f1f5f9]">Kelola Periode</button>
                </div>
            </div>
            
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#e2e8f0]">
                            <svg class="h-4 w-4 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"></rect>
                                <path d="M16 2v4M8 2v4M3 10h18" stroke-width="2" stroke-linecap="round"></path>
                            </svg>
                        </div>
                        <span class="text-[12px] font-bold uppercase tracking-[0.08em] text-[#64748b]">Juli</span>
                    </div>
                    <div class="mx-4 h-[3px] flex-1 rounded-full bg-[#e2e8f0]">
                        <div class="h-full w-[65%] rounded-full bg-[#1d4ed8]"></div>
                    </div>
                    <span class="text-[12px] font-bold uppercase tracking-[0.08em] text-[#64748b]">Juni</span>
                </div>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-[10px] border border-[#e2e8f0] bg-[#f8fafc] p-4">
                        <p class="text-[13px] font-bold text-[#0f172a]">Semester Ganjil</p>
                        <p class="mt-0.5 text-[12px] font-semibold text-[#475569]">Status: @if($selectedTahun && $selectedTahun->semester === 'Ganjil') Aktif @else Selesai @endif</p>
                    </div>
                    <div class="rounded-[10px] border border-[#1d4ed8] bg-[#eff6ff] p-4">
                        <p class="text-[13px] font-bold text-[#1d4ed8]">Semester Genap</p>
                        <p class="mt-0.5 text-[12px] font-semibold text-[#475569]">Status: @if($selectedTahun && $selectedTahun->semester === 'Genap') Aktif @else Selesai @endif</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="space-y-3">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Detail Periode</p>
            <div class="rounded-[10px] border border-[#e2e8f0] bg-white p-4">
                <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Total Kelas</p>
                <p class="mt-1">
                    <span class="text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">{{ count($kelas) }}</span>
                </p>
            </div>
            <div class="rounded-[10px] border border-[#e2e8f0] bg-white p-4">
                <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Siswa Belum Ditempatkan</p>
                <p class="mt-1">
                    <span class="text-[32px] font-black tracking-[-0.04em] text-[#dc2626]" x-text="antrean.length"></span>
                </p>
            </div>
        </div>
    </div>

    {{-- PLOTTING --}}
    <div>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-[22px] font-black tracking-[-0.04em] text-[#0f172a]">Kelas Terdaftar</h2>
                <p class="mt-1 text-[13px] text-[#475569]">Daftar rombongan belajar pada periode terpilih.</p>
            </div>
            @if($selectedTahun)
            <div class="flex items-center gap-2">
                <button @click="showTambahKelas = true" class="flex h-[38px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-4 text-[12px] font-bold text-[#334155] transition hover:bg-[#f1f5f9]">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path>
                    </svg>
                    Tambah Kelas
                </button>
                <button @click="showPlotting = true" :disabled="antrean.length === 0" class="flex h-[38px] items-center gap-2 rounded-[8px] bg-[#1d4ed8] px-4 text-[12px] font-bold text-white transition hover:bg-[#1e40af] disabled:cursor-not-allowed disabled:opacity-50">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"></path>
                    </svg>
                    Plotting Otomatis
                </button>
            </div>
            @endif
        </div>
        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <template x-for="k in kelas" :key="k.id">
                <div @click="openDetail(k)" class="cursor-pointer rounded-[14px] border border-[#e2e8f0] bg-white p-5 transition hover:shadow-md hover:border-[#3b82f6]">
                    <div class="mb-3 flex h-8 w-8 items-center justify-center rounded-[6px] bg-[#f1f5f9]">
                        <svg class="h-4 w-4 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <p class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a]" x-text="'Kelas ' + k.nama"></p>
                    <p class="text-[11px] font-semibold text-[#64748b]" x-text="k.jurusan"></p>
                    <div class="mt-3 flex items-center justify-between text-[12px]">
                        <span class="font-semibold text-[#334155]">Kapasitas</span>
                        <span class="font-bold text-[#0f172a]" x-text="k.kapasitas"></span>
                    </div>
                    <div class="mt-1.5 h-[4px] overflow-hidden rounded-full bg-[#e2e8f0]">
                        <div class="h-full rounded-full" :class="k.pct >= 100 ? 'bg-[#dc2626]' : 'bg-[#1d4ed8]'" :style="'width:'+Math.min(k.pct,100)+'%'"></div>
                    </div>
                    <p class="mt-2 text-[11px]" :class="k.pct >= 100 ? 'font-bold text-[#dc2626]' : 'text-[#64748b]'" x-text="k.wali"></p>
                </div>
            </template>
            <a href="{{ route('kelas') }}" class="flex flex-col items-center justify-center rounded-[14px] border-2 border-dashed border-[#cbd5e1] bg-[#f8fafc] p-5 transition hover:border-[#3b82f6] cursor-pointer">
                <div class="flex h-10 w-10 items-center justify-center rounded-[8px] bg-[#e2e8f0] text-[#64748b]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <p class="mt-2 text-[12px] font-bold uppercase tracking-[0.08em] text-[#64748b]">Kelola Data Kelas</p>
            </a>
        </div>
    </div>

    {{-- ANTREAN --}}
    <div class="rounded-[14px] border border-[#e2e8f0] bg-white overflow-hidden">
        <div class="flex items-center justify-between border-b border-[#e2e8f0] px-6 py-4">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Antrean Plotting Siswa</p>
            </div>
            <span class="rounded bg-[#dc2626] px-2.5 py-1 text-[10px] font-bold text-white" x-text="antrean.length + ' SISWA'"></span>
        </div>
        <table class="w-full text-[13px]">
            <thead>
                <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                    <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">No</th>
                    <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama</th>
                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NISN</th>
                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Gender</th>
                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Status</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(s, index) in antrean.slice(0, 5)" :key="s.nisn">
                    <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                        <td class="px-6 py-3.5 font-semibold text-[#64748b]" x-text="index + 1"></td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 flex-none items-center justify-center rounded-[6px] bg-[#eff6ff] text-[10px] font-bold text-[#1d4ed8]" x-text="s.init"></div>
                                <span class="font-bold text-[#0f172a]" x-text="s.name"></span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 font-mono text-[12px] text-[#64748b]" x-text="s.nisn"></td>
                        <td class="px-4 py-3.5 text-[#475569]" x-text="s.gender"></td>
                        <td class="px-4 py-3.5">
                            <span class="rounded bg-[#1d4ed8] px-2 py-0.5 text-[10px] font-bold text-white" x-text="s.peminatan"></span>
                        </td>
                    </tr>
                </template>
                <tr x-show="antrean.length === 0">
                    <td colspan="5" class="py-8 text-center text-[#94a3b8]">Tidak ada siswa dalam antrean plotting.</td>
                </tr>
            </tbody>
        </table>
        <div class="border-t border-[#e2e8f0] px-6 py-3 text-center" x-show="antrean.length > 5">
            <button @click="showAntrean = true" class="text-[12px] font-bold uppercase tracking-[0.08em] text-[#1d4ed8] transition hover:text-[#1e40af]" x-text="'Lihat Semua Antrean (' + (antrean.length - 5) + ' Lainnya)'"></button>
        </div>
    </div>

    {{-- ═══ MODAL: Kelola Periode ═══ --}}
    <x-modal alpineShow="showKelolaPeriode" title="Kelola Periode Akademik" maxWidth="2xl">
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-[#334155]">Daftar Periode Akademik</h3>
                <button @click="showTambahPeriode = !showTambahPeriode" class="rounded-lg bg-[#1d4ed8] px-3 py-1.5 text-[11px] font-bold text-white transition hover:bg-[#1e40af]">
                    + Tambah Periode
                </button>
            </div>

            {{-- Form Tambah Periode (collapsible) --}}
            <div x-show="showTambahPeriode" class="rounded-xl border border-[#e2e8f0] bg-[#f8fafc] p-4 space-y-4" style="display:none" x-transition>
                <form method="POST" action="{{ route('akademik.tahun-ajaran.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Tahun Mulai</label>
                            <input name="tahun_mulai" type="number" required placeholder="Contoh: 2026" class="mt-1 flex h-[38px] w-full rounded-[8px] border border-[#e2e8f0] bg-white px-3 text-[13px] outline-none focus:border-[#3b82f6]">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Tahun Selesai</label>
                            <input name="tahun_selesai" type="number" required placeholder="Contoh: 2027" class="mt-1 flex h-[38px] w-full rounded-[8px] border border-[#e2e8f0] bg-white px-3 text-[13px] outline-none focus:border-[#3b82f6]">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Semester</label>
                            <select name="semester" class="mt-1 h-[38px] w-full rounded-[8px] border border-[#e2e8f0] bg-white px-3 text-[13px] outline-none focus:border-[#3b82f6]">
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-[13px] font-medium text-[#475569] cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="rounded text-[#1d4ed8]">
                            Jadikan periode aktif langsung
                        </label>
                        <button type="submit" class="rounded-lg bg-[#059669] px-4 py-2 text-[12px] font-bold text-white hover:bg-[#047857]">Simpan</button>
                    </div>
                </form>
            </div>

            {{-- Table Periode --}}
            <div class="border border-[#e2e8f0] rounded-xl overflow-hidden bg-white max-h-[300px] overflow-y-auto">
                <table class="w-full text-[13px] text-left">
                    <thead>
                        <tr class="bg-[#f8fafc] border-b border-[#e2e8f0] text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">
                            <th class="px-4 py-2.5">Tahun Ajaran</th>
                            <th class="px-4 py-2.5">Semester</th>
                            <th class="px-4 py-2.5">Status</th>
                            <th class="px-4 py-2.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e2e8f0]">
                        @foreach($tahunAjarans as $ta)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-[#0f172a]">{{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }}</td>
                            <td class="px-4 py-3 text-[#475569]">{{ $ta->semester }}</td>
                            <td class="px-4 py-3">
                                @if($ta->is_active)
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-[10px] font-bold text-green-700">Aktif</span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-[10px] font-medium text-gray-600">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if(!$ta->is_active)
                                    <form method="POST" action="{{ route('akademik.tahun-ajaran.set-active', $ta->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[11px] font-bold text-[#1d4ed8] hover:underline">Set Aktif</button>
                                    </form>
                                    <span class="text-[#cbd5e1]">&bull;</span>
                                    @endif
                                    <button 
                                        type="button" 
                                        @click="confirmDeleteTahun({{ $ta->id }}, 'TA {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} - {{ $ta->semester }}')" 
                                        class="text-[11px] font-bold text-[#dc2626] hover:underline"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <x-slot:footer>
            <button @click="showKelolaPeriode = false" class="w-full rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Tutup</button>
        </x-slot:footer>
    </x-modal>

    {{-- ═══ MODAL: Tambah Kelas ═══ --}}
    @if($selectedTahun)
    <x-modal alpineShow="showTambahKelas" title="Tambah Kelas Baru" maxWidth="md">
        <form method="POST" action="{{ route('akademik.kelas.store') }}">
            @csrf
            <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranId }}">
            <div class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Kelas</label>
                        <input name="nama_kelas" required class="mt-1 flex h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]" placeholder="Contoh: 1-A">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Tingkat (1-6)</label>
                        <select name="tingkat" required class="mt-1 h-[42px] w-full rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                            <option value="1">Tingkat 1</option>
                            <option value="2">Tingkat 2</option>
                            <option value="3">Tingkat 3</option>
                            <option value="4">Tingkat 4</option>
                            <option value="5">Tingkat 5</option>
                            <option value="6">Tingkat 6</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Wali Kelas</label>
                    <select name="wali_guru_id" required class="mt-1 h-[42px] w-full appearance-none rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] outline-none focus:border-[#3b82f6]">
                        <option value="" disabled selected>-- Pilih Wali Kelas --</option>
                        @foreach($daftarGuru as $g)
                            <option value="{{ $g['id'] }}">{{ $g['nama'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex gap-3 mt-6 border-t border-[#e2e8f0] pt-4">
                <button type="button" @click="showTambahKelas = false" class="flex-1 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569]">Batal</button>
                <button type="submit" class="flex-1 rounded-lg bg-[#1d4ed8] py-2.5 text-[12px] font-bold text-white">Tambah Kelas</button>
            </div>
        </form>
    </x-modal>
    @endif

    {{-- ═══ MODAL: Plotting Otomatis ═══ --}}
    @if($selectedTahun)
    <x-confirm-dialog
        alpineShow="showPlotting"
        type="warning"
        title="Plotting Otomatis"
        message="Sistem akan menempatkan siswa dalam antrean ke kelas yang tersedia secara otomatis (kapasitas maksimal 32 per kelas)."
        confirmText="Jalankan"
        confirmAction="runPlotting()"
    />
    <form id="formPlottingOtomatis" method="POST" action="{{ route('akademik.plotting-otomatis') }}" class="hidden">
        @csrf
        <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranId }}">
    </form>
    @endif

    {{-- ═══ MODAL: Konfirmasi Hapus Tahun Ajaran ═══ --}}
    <x-confirm-dialog
        alpineShow="showConfirmDeleteTahun"
        type="danger"
        title="Hapus Periode Akademik"
        message="Apakah Anda yakin ingin menghapus periode akademik <strong class='font-bold text-[#0f172a]' x-text='deleteTahunLabel'></strong>? Semua data kelas dan rapor yang terkait akan dihapus secara permanen dari sistem."
        confirmText="Ya, Hapus"
        confirmAction="runDeleteTahun()"
    />
    <form id="formDeleteTahunAjaran" method="POST" action="" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    {{-- ═══ MODAL: Semua Antrean ═══ --}}
    <x-modal alpineShow="showAntrean" title="Semua Antrean Plotting" maxWidth="2xl">
        <div class="max-h-[60vh] overflow-y-auto -mx-6">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">No</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NISN</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Gender</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(s, index) in antrean" :key="s.nisn">
                        <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                            <td class="px-6 py-3 font-semibold text-[#64748b]" x-text="index + 1"></td>
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 flex-none items-center justify-center rounded-[6px] bg-[#eff6ff] text-[10px] font-bold text-[#1d4ed8]" x-text="s.init"></div>
                                    <span class="font-bold text-[#0f172a]" x-text="s.name"></span>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-mono text-[12px] text-[#64748b]" x-text="s.nisn"></td>
                            <td class="px-4 py-3 text-[#475569]" x-text="s.gender"></td>
                            <td class="px-4 py-3">
                                <span class="rounded bg-[#1d4ed8] px-2 py-0.5 text-[10px] font-bold text-white" x-text="s.peminatan"></span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </x-modal>

    {{-- ═══ MODAL: Detail Kelas ═══ --}}
    <x-modal alpineShow="showDetailKelas" title="Detail Kelas" maxWidth="md">
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Tingkat</p>
                    <p class="mt-1 text-[14px] font-bold text-[#0f172a]" x-text="detailKelas?.jurusan"></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kapasitas</p>
                    <p class="mt-1 text-[14px] font-bold text-[#0f172a]" x-text="detailKelas?.kapasitas"></p>
                </div>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Wali Kelas</p>
                <p class="mt-1 text-[14px] font-bold text-[#0f172a]" x-text="detailKelas?.wali"></p>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Daftar Siswa</p>
                <div class="mt-2 space-y-2 max-h-[40vh] overflow-y-auto">
                    <template x-for="(nama, i) in (detailKelas?.siswa || [])" :key="i">
                        <div class="flex items-center gap-2 rounded-lg bg-[#f8fafc] px-3 py-2">
                            <div class="h-6 w-6 flex items-center justify-center rounded-full bg-[#e2e8f0] text-[9px] font-bold text-[#475569]" x-text="i+1"></div>
                            <span class="text-[13px] font-medium text-[#0f172a]" x-text="nama"></span>
                        </div>
                    </template>
                    <div x-show="(detailKelas?.siswa || []).length === 0" class="text-center py-4 text-gray-500 text-[13px]">
                        Belum ada siswa di kelas ini.
                    </div>
                </div>
            </div>
        </div>
    </x-modal>
</div>
@endsection
