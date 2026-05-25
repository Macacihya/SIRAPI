@extends('layouts.app')
@section('title', 'Rapor Siswa')
@section('subtitle', 'Kelola, hasilkan, dan cetak rapor siswa')
@section('active', 'rapor')

@section('content')
@php
    $tahunAktif = $tahunAjarans->firstWhere('is_active', true) ?? $tahunAjarans->first();
@endphp

<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[24px] font-black tracking-tight text-[#0f172a]">Rapor Siswa</h1>
            <p class="mt-1 text-[13px] text-[#64748b]">Rapor dibuat otomatis untuk siswa kelas binaan pada tahun ajaran aktif.</p>
        </div>
        <form action="{{ route('rapor.generate') }}" method="POST" class="flex flex-wrap gap-2 rounded-xl border border-[#e2e8f0] bg-white p-3 shadow-sm">
            @csrf
            <select name="siswa_id" required class="h-10 rounded-lg border border-[#e2e8f0] px-3 text-[13px] font-semibold outline-none">
                <option value="">Pilih siswa</option>
                @foreach ($siswas as $siswa)
                    <option value="{{ $siswa->id }}">{{ $siswa->nama_siswa }} - {{ $siswa->kelas->nama_kelas ?? '-' }}</option>
                @endforeach
            </select>
            <select name="tahun_ajaran_id" required class="h-10 rounded-lg border border-[#e2e8f0] px-3 text-[13px] font-semibold outline-none">
                @foreach ($tahunAjarans as $tahun)
                    <option value="{{ $tahun->id }}" @selected($tahunAktif?->id === $tahun->id)>{{ $tahun->label }}</option>
                @endforeach
            </select>
            <button class="h-10 rounded-lg bg-[#1d4ed8] px-4 text-[13px] font-bold text-white hover:bg-[#1e40af]">Generate</button>
        </form>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-[#bbf7d0] bg-[#f0fdf4] px-4 py-3 text-[13px] font-semibold text-[#166534]">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-[#e2e8f0] bg-white shadow-sm">
        <div class="border-b border-[#e2e8f0] bg-[#f8fafc] px-5 py-4">
            <h2 class="text-[14px] font-bold text-[#0f172a]">Daftar Rapor</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[13px]">
                <thead class="border-b border-[#e2e8f0] text-[11px] uppercase text-[#64748b]">
                    <tr>
                        <th class="px-5 py-3">Siswa</th>
                        <th class="px-5 py-3">Kelas</th>
                        <th class="px-5 py-3">Tahun Ajaran</th>
                        <th class="px-5 py-3 text-center">Nilai</th>
                        <th class="px-5 py-3 text-center">Kehadiran</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f1f5f9]">
                    @forelse ($raports as $raport)
                        @php
                            $hadir = $raport->rekapKehadirans->countBy('status');
                        @endphp
                        <tr class="hover:bg-[#f8fafc]">
                            <td class="px-5 py-4">
                                <p class="font-black text-[#0f172a]">{{ $raport->siswa->nama_siswa ?? '-' }}</p>
                                <p class="text-[12px] text-[#64748b]">NIS: {{ $raport->siswa->nis ?? '-' }} / NISN: {{ $raport->siswa->nisn ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-4 font-semibold text-[#475569]">{{ $raport->siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td class="px-5 py-4 font-semibold text-[#475569]">{{ $raport->tahunAjaran->label ?? '-' }}</td>
                            <td class="px-5 py-4 text-center font-black text-[#1d4ed8]">{{ $raport->nilais->count() }}</td>
                            <td class="px-5 py-4 text-center text-[12px] font-semibold text-[#475569]">
                                S {{ $hadir->get('sakit', 0) }} / I {{ $hadir->get('izin', 0) }} / A {{ $hadir->get('alpha', 0) }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('rekap-kehadiran.sync') }}" method="POST" class="flex items-center gap-1">
                                        @csrf
                                        <input type="hidden" name="raport_id" value="{{ $raport->id }}">
                                        <input name="sakit" type="number" min="0" value="{{ $hadir->get('sakit', 0) }}" class="h-9 w-14 rounded border border-[#e2e8f0] px-2 text-center text-[12px]" title="Sakit">
                                        <input name="izin" type="number" min="0" value="{{ $hadir->get('izin', 0) }}" class="h-9 w-14 rounded border border-[#e2e8f0] px-2 text-center text-[12px]" title="Izin">
                                        <input name="alpha" type="number" min="0" value="{{ $hadir->get('alpha', 0) }}" class="h-9 w-14 rounded border border-[#e2e8f0] px-2 text-center text-[12px]" title="Alpha">
                                        <button class="h-9 rounded-lg border border-[#e2e8f0] px-3 text-[12px] font-bold text-[#0f172a] hover:bg-[#f1f5f9]">Simpan</button>
                                    </form>
                                    <a href="{{ route('rapor.show', $raport) }}" class="inline-flex h-9 items-center rounded-lg bg-[#0f172a] px-3 text-[12px] font-bold text-white hover:bg-[#1e293b]">Lihat/Cetak</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-[13px] text-[#64748b]">Belum ada data rapor untuk kelas binaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
