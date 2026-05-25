@php
    abort_unless(isset($raport), 404);

    $siswa = $raport->siswa;
    $kelas = $siswa?->kelas;
    $tahun = $raport->tahunAjaran;
    $kehadiran = $raport->rekapKehadiran;
    $rataRata = round((float) $raport->nilais->avg('nilai_akhir'), 2);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rapor - {{ $siswa->nama_siswa ?? 'Siswa' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .sheet { box-shadow: none !important; margin: 0 !important; width: 210mm !important; min-height: 297mm !important; }
        }
        table { border-collapse: collapse; }
        th, td { border: 1px solid #1e293b; }
    </style>
</head>
<body class="bg-[#e2e8f0] text-[#0f172a]">
    <header class="no-print sticky top-0 z-10 flex h-14 items-center justify-between border-b border-[#e2e8f0] bg-white px-6 shadow-sm">
        <a href="{{ route('rapor') }}" class="text-[13px] font-bold text-[#475569] hover:text-[#0f172a]">Kembali</a>
        <button onclick="window.print()" class="rounded-lg bg-[#0f172a] px-4 py-2 text-[12px] font-bold text-white">Cetak Rapor</button>
    </header>

    <main class="mx-auto py-8">
        <section class="sheet mx-auto min-h-[297mm] w-[210mm] bg-white p-12 shadow-xl">
            <div class="flex items-center justify-between border-b-4 border-double border-[#0f172a] pb-4">
                <img src="{{ asset('images/tutwuri.png') }}" class="h-20 w-auto" alt="Logo Tut Wuri">
                <div class="text-center">
                    <p class="text-[14px] font-bold uppercase">Pemerintah Kota Batam</p>
                    <p class="text-[14px] font-bold uppercase">Dinas Pendidikan</p>
                    <h1 class="mt-1 text-[20px] font-black uppercase">SD Negeri 01 Indonesia</h1>
                    <p class="mt-1 text-[10px]">Jl. Imajinasi No. 42, Batam Kota, Batam</p>
                </div>
                <img src="{{ asset('images/logo-sekolah.png') }}" class="h-20 w-auto" alt="Logo Sekolah">
            </div>

            <h2 class="mt-6 text-center text-[18px] font-black uppercase tracking-wide">Laporan Hasil Belajar</h2>

            <div class="mt-8 grid grid-cols-2 gap-8 text-[12px]">
                <div class="space-y-1">
                    <p><span class="inline-block w-28 text-[#475569]">Nama Siswa</span>: <strong>{{ $siswa->nama_siswa ?? '-' }}</strong></p>
                    <p><span class="inline-block w-28 text-[#475569]">NIS / NISN</span>: {{ $siswa->nis ?? '-' }} / {{ $siswa->nisn ?? '-' }}</p>
                    <p><span class="inline-block w-28 text-[#475569]">Kelas</span>: {{ $kelas->nama_kelas ?? '-' }}</p>
                </div>
                <div class="space-y-1">
                    <p><span class="inline-block w-28 text-[#475569]">Semester</span>: {{ $tahun->semester ?? '-' }}</p>
                    <p><span class="inline-block w-28 text-[#475569]">Tahun Ajaran</span>: {{ $tahun ? $tahun->tahun_mulai . '/' . $tahun->tahun_selesai : '-' }}</p>
                    <p><span class="inline-block w-28 text-[#475569]">Rata-rata</span>: <strong>{{ number_format($rataRata, 2) }}</strong></p>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-[13px] font-black uppercase">A. Nilai Mata Pelajaran</h3>
                <table class="mt-3 w-full text-[12px]">
                    <thead class="bg-[#f8fafc]">
                        <tr>
                            <th class="w-10 px-2 py-2 text-center">No</th>
                            <th class="px-3 py-2">Mata Pelajaran</th>
                            <th class="w-20 px-3 py-2 text-center">Nilai</th>
                            <th class="px-3 py-2">Capaian Kompetensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($raport->nilais as $nilai)
                            <tr>
                                <td class="px-2 py-2 text-center">{{ $loop->iteration }}</td>
                                <td class="px-3 py-2 font-semibold">{{ $nilai->mataPelajaran->nama_mapel ?? 'Mata Pelajaran' }}</td>
                                <td class="px-3 py-2 text-center font-black">{{ number_format((float) $nilai->nilai_akhir, 2) }}</td>
                                <td class="px-3 py-2 text-[11px] leading-5">
                                    {{ $nilai->capaianKompetensis->pluck('deskripsi')->filter()->join(' ') ?: 'Capaian kompetensi belum diisi.' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-6 text-center text-[#64748b]">Nilai belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-6">
                <div>
                    <h3 class="text-[13px] font-black uppercase">B. Sikap</h3>
                    <div class="mt-3 space-y-3">
                        @forelse ($raport->nilaiSikaps as $nilaiSikap)
                            <div class="border border-[#1e293b] p-3 text-[12px] leading-6">
                                <p class="font-bold">{{ $nilaiSikap->sikap->nama_sikap ?? 'Sikap' }}: {{ $nilaiSikap->predikat ?? '-' }}</p>
                                <p>{{ $nilaiSikap->deskripsi ?? 'Deskripsi sikap belum diisi.' }}</p>
                            </div>
                        @empty
                            <div class="border border-[#1e293b] p-4 text-[12px] text-[#64748b]">Nilai sikap belum diisi.</div>
                        @endforelse
                    </div>
                </div>
                <div>
                    <h3 class="text-[13px] font-black uppercase">C. Kehadiran</h3>
                    <table class="mt-3 w-full text-[12px]">
                        <tr><td class="px-3 py-2 font-semibold">Sakit</td><td class="w-24 px-3 py-2 text-center font-black">{{ $kehadiran->sakit ?? 0 }} hari</td></tr>
                        <tr><td class="px-3 py-2 font-semibold">Izin</td><td class="px-3 py-2 text-center font-black">{{ $kehadiran->izin ?? 0 }} hari</td></tr>
                        <tr><td class="px-3 py-2 font-semibold">Alpha</td><td class="px-3 py-2 text-center font-black">{{ $kehadiran->alpha ?? 0 }} hari</td></tr>
                    </table>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-[13px] font-black uppercase">D. Ekstrakurikuler</h3>
                <table class="mt-3 w-full text-[12px]">
                    <thead class="bg-[#f8fafc]"><tr><th class="w-10 px-2 py-2">No</th><th class="px-3 py-2">Kegiatan</th><th class="w-20 px-3 py-2">Predikat</th><th class="px-3 py-2">Keterangan</th></tr></thead>
                    <tbody>
                        @forelse ($raport->raportEkskuls as $ekskul)
                            <tr>
                                <td class="px-2 py-2 text-center">{{ $loop->iteration }}</td>
                                <td class="px-3 py-2">{{ $ekskul->ekstrakurikuler->nama_eskul ?? '-' }}</td>
                                <td class="px-3 py-2 text-center font-bold">{{ $ekskul->predikat ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $ekskul->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-center text-[#64748b]">Belum ada data ekstrakurikuler.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-12 grid grid-cols-2 text-center text-[12px]">
                <div>
                    <p>Orang Tua/Wali,</p>
                    <div class="mx-auto mt-20 w-40 border-b border-[#0f172a]"></div>
                </div>
                <div>
                    <p>Batam, {{ now()->translatedFormat('d F Y') }}</p>
                    <p class="font-bold">Wali Kelas,</p>
                    <p class="mt-16 font-black underline">{{ auth()->user()->nama ?? '-' }}</p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
