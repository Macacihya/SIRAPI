@extends('layouts.app')
@section('title', 'Laporan Nilai')
@section('subtitle', 'Laporan nilai siswa')
@section('active', 'laporan-nilai')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-[24px] font-black tracking-tight text-[#0f172a]">Laporan Nilai</h1>
        <p class="mt-1 text-[13px] text-[#64748b]">Admin TU dan Guru dapat melihat laporan nilai sesuai hak aksesnya.</p>
    </div>

    <form method="GET" action="{{ route('laporan-nilai') }}" class="flex flex-wrap gap-3 rounded-xl border border-[#e2e8f0] bg-white p-4 shadow-sm">
        <input name="search" value="{{ $search ?? '' }}" type="text" placeholder="Cari nama atau NIS..." class="min-w-[220px] flex-1 rounded-lg border border-[#e2e8f0] px-4 py-2 text-[13px] outline-none focus:border-[#3b82f6]">
        <select name="kelas" onchange="this.form.submit()" class="rounded-lg border border-[#e2e8f0] px-4 py-2 text-[13px] font-semibold outline-none">
            <option value="all">Semua kelas</option>
            @foreach ($kelas as $item)
                <option value="{{ $item->nama_kelas }}" @selected(($kelasFilter ?? 'all') === $item->nama_kelas)>{{ $item->nama_kelas }}</option>
            @endforeach
        </select>
        <noscript><button type="submit">Filter</button></noscript>
    </form>

    <div class="overflow-hidden rounded-xl border border-[#e2e8f0] bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[13px]">
                <thead class="border-b border-[#e2e8f0] bg-[#f8fafc] text-[11px] uppercase text-[#64748b]">
                    <tr>
                        <th class="px-5 py-3">Siswa</th>
                        <th class="px-5 py-3">Kelas</th>
                        <th class="px-5 py-3 text-center">Jumlah Nilai</th>
                        <th class="px-5 py-3 text-center">Rata-rata</th>
                        <th class="px-5 py-3 text-center">Predikat</th>
                        <th class="px-5 py-3">Detail Mapel</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f1f5f9]">
                    @forelse ($laporanSiswa as $laporan)
                        @php
                            $nama = $laporan['siswa']->nama_siswa ?? '-';
                            $nis = $laporan['siswa']->nis ?? '';
                            $namaKelas = $laporan['siswa']->kelas->nama_kelas ?? '-';
                        @endphp
                        <tr class="hover:bg-[#f8fafc]">
                            <td class="px-5 py-4">
                                <p class="font-black text-[#0f172a]">{{ $nama }}</p>
                                <p class="text-[12px] text-[#64748b]">NIS: {{ $nis ?: '-' }}</p>
                            </td>
                            <td class="px-5 py-4 font-semibold text-[#475569]">{{ $namaKelas }}</td>
                            <td class="px-5 py-4 text-center font-bold">{{ $laporan['nilai']->count() }}</td>
                            <td class="px-5 py-4 text-center text-[16px] font-black text-[#1d4ed8]">{{ number_format($laporan['rata_rata'], 2) }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="rounded bg-[#eff6ff] px-3 py-1 text-[12px] font-black text-[#1d4ed8]">{{ $laporan['predikat'] }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($laporan['nilai'] as $nilai)
                                        <span class="rounded border border-[#e2e8f0] bg-[#f8fafc] px-2 py-1 text-[12px] font-semibold">
                                            {{ $nilai->mataPelajaran->nama_mapel ?? 'Mapel' }}: {{ number_format((float) $nilai->nilai_akhir, 2) }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-[#64748b]">Belum ada nilai yang dapat ditampilkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-table-pagination :paginator="$laporanSiswa" />
    </div>
</div>
@endsection
