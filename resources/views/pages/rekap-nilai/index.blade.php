@extends('layouts.app')
@section('title', 'Rekap Nilai Kelas')
@section('subtitle', 'Rekap nilai per kelas')
@section('active', 'rekap-nilai')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-[24px] font-black tracking-tight text-[#0f172a]">Rekap Nilai Kelas</h1>
            <p class="mt-1 text-[13px] text-[#64748b]">Ringkasan rata-rata, nilai tertinggi, dan nilai terendah per kelas.</p>
        </div>
        <form method="GET" action="{{ route('rekap-nilai') }}" class="w-full sm:w-[280px]">
            <label for="kelas_id" class="mb-1.5 block text-[11px] font-bold uppercase text-[#64748b]">Filter Kelas</label>
            <select id="kelas_id" name="kelas_id" onchange="this.form.submit()" class="h-10 w-full rounded-lg border border-[#e2e8f0] bg-white px-3 text-[13px] font-semibold text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                <option value="">Semua Kelas</option>
                @foreach ($kelas as $kelasItem)
                    <option value="{{ $kelasItem->id }}" @selected($selectedKelasId === $kelasItem->id)>
                        {{ $kelasItem->nama_kelas }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-[#bbf7d0] bg-[#f0fdf4] px-4 py-3 text-[13px] font-semibold text-[#166534]">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded-xl border border-[#e2e8f0] bg-white p-5 shadow-sm">
            <p class="text-[11px] font-bold uppercase text-[#64748b]">Total Entri Nilai</p>
            <p class="mt-2 text-[32px] font-black text-[#0f172a]">{{ $allNilais->count() }}</p>
        </div>
        <div class="rounded-xl border border-[#e2e8f0] bg-white p-5 shadow-sm">
            <p class="text-[11px] font-bold uppercase text-[#64748b]">Rata-rata Global</p>
            <p class="mt-2 text-[32px] font-black text-[#1d4ed8]">{{ number_format((float) $allNilais->avg('nilai_akhir'), 2) }}</p>
        </div>
        <div class="rounded-xl border border-[#e2e8f0] bg-white p-5 shadow-sm">
            <p class="text-[11px] font-bold uppercase text-[#64748b]">Jumlah Kelas</p>
            <p class="mt-2 text-[32px] font-black text-[#0f172a]">{{ $rekapKelas->count() }}</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-[#e2e8f0] bg-white shadow-sm">
        <div class="border-b border-[#e2e8f0] bg-[#f8fafc] px-5 py-4">
            <h2 class="text-[14px] font-bold text-[#0f172a]">Rekap Per Kelas</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[13px]">
                <thead class="border-b border-[#e2e8f0] text-[11px] uppercase text-[#64748b]">
                    <tr>
                        <th class="px-5 py-3">Kelas</th>
                        <th class="px-5 py-3 text-center">Jumlah Siswa</th>
                        <th class="px-5 py-3 text-center">Entri Nilai</th>
                        <th class="px-5 py-3 text-center">Rata-rata</th>
                        <th class="px-5 py-3 text-center">Tertinggi</th>
                        <th class="px-5 py-3 text-center">Terendah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f1f5f9]">
                    @foreach ($rekapKelas as $rekap)
                        <tr class="hover:bg-[#f8fafc]">
                            <td class="px-5 py-4 font-black text-[#0f172a]">{{ $rekap['kelas']->nama_kelas }}</td>
                            <td class="px-5 py-4 text-center font-semibold">{{ $rekap['jumlah_siswa'] }}</td>
                            <td class="px-5 py-4 text-center font-semibold">{{ $rekap['jumlah_nilai'] }}</td>
                            <td class="px-5 py-4 text-center text-[16px] font-black text-[#1d4ed8]">{{ number_format($rekap['rata_rata'], 2) }}</td>
                            <td class="px-5 py-4 text-center font-bold text-[#059669]">{{ number_format($rekap['tertinggi'], 2) }}</td>
                            <td class="px-5 py-4 text-center font-bold text-[#dc2626]">{{ number_format($rekap['terendah'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-[#e2e8f0] bg-white shadow-sm">
        <div class="border-b border-[#e2e8f0] bg-[#f8fafc] px-5 py-4">
            <h2 class="text-[14px] font-bold text-[#0f172a]">Detail Nilai</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[13px]">
                <thead class="border-b border-[#e2e8f0] text-[11px] uppercase text-[#64748b]">
                    <tr>
                        <th class="px-5 py-3">Siswa</th>
                        <th class="px-5 py-3">Kelas</th>
                        <th class="px-5 py-3">Mapel</th>
                        <th class="px-5 py-3 text-center">Nilai Akhir</th>
                        <th class="px-5 py-3">Tahun Ajaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f1f5f9]">
                    @forelse ($nilais as $nilai)
                        <tr class="hover:bg-[#f8fafc]">
                            <td class="px-5 py-4 font-black text-[#0f172a]">{{ $nilai->siswa->nama_siswa ?? '-' }}</td>
                            <td class="px-5 py-4 font-semibold text-[#475569]">{{ $nilai->siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $nilai->mataPelajaran->nama_mapel ?? 'Mata Pelajaran' }}</td>
                            <td class="px-5 py-4 text-center text-[16px] font-black text-[#1d4ed8]">{{ number_format((float) $nilai->nilai_akhir, 2) }}</td>
                            <td class="px-5 py-4 text-[#475569]">{{ $nilai->raport->tahunAjaran->label ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-[#64748b]">Belum ada data nilai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-table-pagination :paginator="$nilais" />
    </div>
</div>
@endsection
