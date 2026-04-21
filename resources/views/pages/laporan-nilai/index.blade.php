@extends(getLayout())
@section('title', 'Laporan Nilai')
@section('subtitle', 'Laporan nilai siswa')
@section('active', 'laporan-nilai')

@section('content')

    @if(getUserRole() === 'admin')
        {{-- Konten Admin: Laporan Nilai --}}
<div class="space-y-6" x-data="{ expanded: null }">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-black tracking-tight text-[#0f172a]">Arsip Laporan Siswa</h1>
            <p class="text-[13px] text-[#64748b] mt-1">Data scope tidak dibatasi (menampilkan seluruh laporan yang telah dikunci guru).</p>
        </div>
    </div>

    {{-- Filter Cepat --}}
    <div class="bg-white p-4 rounded-xl border border-[#e2e8f0] shadow-sm flex flex-wrap items-center gap-4">
        <input type="text" placeholder="Cari NIS/Nama Siswa..." class="flex-1 bg-[#f8fafc] border border-[#e2e8f0] rounded-lg px-4 py-2.5 text-[13px] font-medium outline-none focus:border-[#3b82f6]">
        <select class="border border-[#e2e8f0] rounded-lg px-4 py-2.5 text-[13px] font-medium outline-none">
            <option>Semua Kelas</option>
            <option>Kelas 4-A</option>
            <option>Kelas 4-B</option>
        </select>
        <select class="border border-[#e2e8f0] rounded-lg px-4 py-2.5 text-[13px] font-medium outline-none">
            <option>Semua Mapel</option>
            <option>Pendidikan Pancasila</option>
        </select>
    </div>

    {{-- Tabel Drill-Down (Expandable) --}}
    <div class="bg-white rounded-xl border border-[#e2e8f0] shadow-sm overflow-hidden">
        <table class="w-full text-left text-[13px]">
            <thead class="bg-[#f8fafc] border-b border-[#e2e8f0]">
                <tr>
                    <th class="px-6 py-4 font-bold text-[#64748b] uppercase text-[11px] tracking-wider w-12 text-center">NIS</th>
                    <th class="px-6 py-4 font-bold text-[#64748b] uppercase text-[11px] tracking-wider">Siswa</th>
                    <th class="px-6 py-4 font-bold text-[#64748b] uppercase text-[11px] tracking-wider">Kelas</th>
                    <th class="px-6 py-4 font-bold text-[#64748b] uppercase text-[11px] tracking-wider text-center">Predikat Total</th>
                    <th class="px-6 py-4 w-12 text-center font-bold text-[#64748b] uppercase text-[11px] tracking-wider">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f1f5f9]">
                
                {{-- Siswa 1 --}}
                <tr class="hover:bg-[#f8fafc] cursor-pointer transition" @click="expanded = expanded === 1 ? null : 1">
                    <td class="px-6 py-4 text-center text-[#94a3b8] font-medium">1021</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#1e40af] text-white flex items-center justify-center font-bold text-[10px]">DA</div>
                            <div><p class="font-black text-[#0f172a]">Dika Aryanto</p></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-[#475569]">Kelas 4-A</td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-[#ecfdf5] text-[#059669] font-black border border-[#a7f3d0] px-2.5 py-1 rounded text-[12px]">B (Sangat Baik)</span>
                    </td>
                    <td class="px-6 py-4 text-center text-[#94a3b8]">
                        <svg class="h-5 w-5 mx-auto transition-transform" :class="expanded === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </td>
                </tr>
                {{-- Row Expand Siswa 1 --}}
                <tr x-show="expanded === 1" x-transition class="bg-[#f8fafc] border-b-2 border-[#e2e8f0]" style="display:none;">
                    <td colspan="5" class="px-6 py-5">
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-white p-3 rounded-lg border border-[#e2e8f0]">
                                <p class="text-[10px] font-bold uppercase text-[#64748b]">Tugas Akhir / Sumatif</p>
                                <div class="mt-2 space-y-1 text-[11px]">
                                    <div class="flex justify-between"><span>Sumatif 1:</span> <span class="font-bold text-[#0f172a]">85</span></div>
                                    <div class="flex justify-between"><span>Sumatif 2:</span> <span class="font-bold text-[#0f172a]">88</span></div>
                                </div>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-[#e2e8f0]">
                                <p class="text-[10px] font-bold uppercase text-[#64748b]">Evaluasi Kinerja</p>
                                <div class="mt-2 space-y-1 text-[11px]">
                                    <div class="flex justify-between"><span>Tengah Semester:</span> <span class="font-bold text-[#0f172a]">80</span></div>
                                    <div class="flex justify-between"><span>Akhir Semester:</span> <span class="font-bold text-[#0f172a]">84</span></div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
    @elseif(getUserRole() === 'guru')
        {{-- Konten Guru: Laporan Nilai --}}
<div class="space-y-6" x-data="{ expanded: null }">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-black tracking-tight text-[#0f172a]">Laporan Breakdown Siswa</h1>
            <p class="text-[13px] text-[#64748b] mt-1">Hanya menampilkan data kueri dari kelas yang Anda ajar.</p>
        </div>
    </div>

    {{-- Filter Data Scope --}}
    <div class="bg-white p-4 rounded-xl border border-[#e2e8f0] shadow-sm flex flex-wrap items-center gap-4">
        <select class="border border-[#e2e8f0] bg-[#f1f5f9] text-[#64748b] rounded-lg px-4 py-2 text-[13px] font-bold outline-none cursor-not-allowed" disabled>
            <option>PJOK - Kelas 4-A</option>
        </select>
        <input type="text" placeholder="Cari nama siswa..." class="flex-1 min-w-[200px] border border-[#e2e8f0] rounded-lg px-4 py-2 text-[13px] font-medium outline-none focus:border-[#3b82f6]">
    </div>

    {{-- Tabel Drill-Down (Expandable) --}}
    <div class="bg-white rounded-xl border border-[#e2e8f0] shadow-sm overflow-hidden">
        <table class="w-full text-left text-[13px]">
            <thead class="bg-[#f8fafc] border-b border-[#e2e8f0]">
                <tr>
                    <th class="px-6 py-4 font-bold text-[#64748b] uppercase text-[11px] tracking-wider w-12 text-center">No</th>
                    <th class="px-6 py-4 font-bold text-[#64748b] uppercase text-[11px] tracking-wider">Siswa</th>
                    <th class="px-6 py-4 font-bold text-[#64748b] uppercase text-[11px] tracking-wider text-center">Nilai Mapel</th>
                    <th class="px-6 py-4 font-bold text-[#64748b] uppercase text-[11px] tracking-wider text-center">Predikat</th>
                    <th class="px-6 py-4 w-12 text-center font-bold text-[#64748b] uppercase text-[11px] tracking-wider">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f1f5f9]">
                
                {{-- Siswa 1 --}}
                <tr class="hover:bg-[#f8fafc] cursor-pointer transition" @click="expanded = expanded === 1 ? null : 1">
                    <td class="px-6 py-4 text-center font-bold text-[#94a3b8]">1</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#1e40af] text-white flex items-center justify-center font-bold text-[10px]">DA</div>
                            <div><p class="font-black text-[#0f172a]">Dika Aryanto</p></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center font-black text-[#059669] text-[16px]">92</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded bg-[#ecfdf5] border border-[#a7f3d0] text-[#059669] text-[12px] font-black uppercase">A (Sangat Baik)</span>
                    </td>
                    <td class="px-6 py-4 text-center text-[#1d4ed8]">
                        <svg class="h-5 w-5 mx-auto transition-transform" :class="expanded === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </td>
                </tr>
                {{-- Row Expand Siswa 1 --}}
                <tr x-show="expanded === 1" x-transition class="bg-[#f8fafc] border-b-2 border-[#e2e8f0]" style="display:none;">
                    <td colspan="5" class="px-6 py-5">
                        <div class="mb-4 text-[12px] font-bold text-[#0f172a] uppercase border-b border-[#e2e8f0] pb-2">Breakdown Nilai PJOK</div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div class="bg-white p-3 rounded-lg border border-[#e2e8f0] text-center">
                                <p class="text-[10px] font-bold uppercase text-[#64748b]">Tugas Teori (30%)</p>
                                <p class="text-[16px] font-black text-[#0f172a] mt-1">90</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-[#e2e8f0] text-center shadow-[0_4px_0_0_#059669]">
                                <p class="text-[10px] font-bold uppercase text-[#64748b]">Praktik Lapangan (40%)</p>
                                <p class="text-[16px] font-black text-[#059669] mt-1">95</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-[#e2e8f0] text-center">
                                <p class="text-[10px] font-bold uppercase text-[#64748b]">Ujian/Sumatif (30%)</p>
                                <p class="text-[16px] font-black text-[#0f172a] mt-1">88</p>
                            </div>
                        </div>
                    </td>
                </tr>

                {{-- Siswa 2 --}}
                <tr class="hover:bg-[#f8fafc] cursor-pointer transition" @click="expanded = expanded === 2 ? null : 2">
                    <td class="px-6 py-4 text-center font-bold text-[#94a3b8]">2</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#ef4444] text-white flex items-center justify-center font-bold text-[10px]">BU</div>
                            <div><p class="font-black text-[#0f172a]">Budi Utomo</p></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center font-black text-[#ef4444] text-[16px]">65</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded bg-[#fef2f2] border border-[#fecaca] text-[#dc2626] text-[12px] font-black uppercase">D (Kurang)</span>
                    </td>
                    <td class="px-6 py-4 text-center text-[#1d4ed8]">
                        <svg class="h-5 w-5 mx-auto transition-transform" :class="expanded === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </td>
                </tr>
                {{-- Row Expand Siswa 2 --}}
                <tr x-show="expanded === 2" x-transition class="bg-[#fef2f2] border-b-2 border-[#fecaca]" style="display:none;">
                    <td colspan="5" class="px-6 py-5">
                        <div class="mb-4 text-[12px] font-bold text-[#dc2626] uppercase border-b border-[#fecaca] pb-2">Status: Tidak Memenuhi KKM (75)</div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div class="bg-white p-3 rounded-lg border border-[#fecaca] text-center shadow-[0_4px_0_0_#dc2626]">
                                <p class="text-[10px] font-bold uppercase text-[#dc2626]">Tugas Teori (30%)</p>
                                <p class="text-[16px] font-black text-[#dc2626] mt-1">40</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-[#e2e8f0] text-center">
                                <p class="text-[10px] font-bold uppercase text-[#64748b]">Praktik Lapangan (40%)</p>
                                <p class="text-[16px] font-black text-[#0f172a] mt-1">80</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-[#fecaca] text-center shadow-[0_4px_0_0_#dc2626]">
                                <p class="text-[10px] font-bold uppercase text-[#dc2626]">Ujian/Sumatif (30%)</p>
                                <p class="text-[16px] font-black text-[#dc2626] mt-1">60</p>
                            </div>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
    @elseif(getUserRole() === 'walikelas')
        {{-- Konten Walikelas: Laporan Nilai --}}
<div class="space-y-6" x-data="{ expanded: null }">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-black tracking-tight text-[#0f172a]">Laporan Anak Didik (Kelas Binaan)</h1>
            <p class="text-[13px] text-[#64748b] mt-1">Data scope menampilkan seluruh mata pelajaran khusus untuk siswa di kelas Anda.</p>
        </div>
    </div>

    {{-- Filter Data Scope --}}
    <div class="bg-white p-4 rounded-xl border border-[#e2e8f0] shadow-sm flex flex-wrap items-center gap-4">
        <select class="border border-[#e2e8f0] bg-[#f1f5f9] text-[#64748b] rounded-lg px-4 py-2 text-[13px] font-bold outline-none cursor-not-allowed" disabled>
            <option>Hanya Kelas 4-B</option>
        </select>
        <input type="text" placeholder="Cari nama anak didik..." class="flex-1 min-w-[200px] border border-[#e2e8f0] rounded-lg px-4 py-2 text-[13px] font-medium outline-none focus:border-[#3b82f6]">
    </div>

    {{-- Tabel Drill-Down (Expandable) --}}
    <div class="bg-white rounded-xl border border-[#e2e8f0] shadow-sm overflow-hidden">
        <table class="w-full text-left text-[13px]">
            <thead class="bg-[#f8fafc] border-b border-[#e2e8f0]">
                <tr>
                    <th class="px-6 py-4 font-bold text-[#64748b] uppercase text-[11px] tracking-wider w-12 text-center">No</th>
                    <th class="px-6 py-4 font-bold text-[#64748b] uppercase text-[11px] tracking-wider">Anak Didik</th>
                    <th class="px-6 py-4 font-bold text-[#64748b] uppercase text-[11px] tracking-wider text-center">Total N. Rapor</th>
                    <th class="px-6 py-4 font-bold text-[#64748b] uppercase text-[11px] tracking-wider text-center">Predikat Akhir</th>
                    <th class="px-6 py-4 w-12 text-center font-bold text-[#64748b] uppercase text-[11px] tracking-wider">Detail Mapel</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f1f5f9]">
                
                {{-- Siswa 1 --}}
                <tr class="hover:bg-[#f8fafc] cursor-pointer transition" @click="expanded = expanded === 1 ? null : 1">
                    <td class="px-6 py-4 text-center font-bold text-[#94a3b8]">1</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#1e40af] text-white flex items-center justify-center font-bold text-[10px]">DA</div>
                            <div><p class="font-black text-[#0f172a]">Dika Aryanto</p></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center font-black text-[#059669] text-[16px]">94.2</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded bg-[#ecfdf5] border border-[#a7f3d0] text-[#059669] text-[12px] font-black uppercase">A (Sangat Baik)</span>
                    </td>
                    <td class="px-6 py-4 text-center text-[#1d4ed8]">
                        <svg class="h-5 w-5 mx-auto transition-transform" :class="expanded === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </td>
                </tr>
                {{-- Row Expand Siswa 1 --}}
                <tr x-show="expanded === 1" x-transition class="bg-[#f8fafc] border-b-2 border-[#e2e8f0]" style="display:none;">
                    <td colspan="5" class="px-6 py-5">
                        <div class="mb-4 text-[12px] font-bold text-[#0f172a] uppercase border-b border-[#e2e8f0] pb-2">Nilai Rapor Lintas Mata Pelajaran</div>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                            <div class="bg-white p-3 rounded-lg border border-[#e2e8f0] text-center">
                                <p class="text-[10px] font-bold uppercase text-[#64748b]">Pendidikan Pancasila</p>
                                <p class="text-[16px] font-black text-[#0f172a] mt-1">90</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-[#e2e8f0] text-center">
                                <p class="text-[10px] font-bold uppercase text-[#64748b]">Bahasa Indonesia</p>
                                <p class="text-[16px] font-black text-[#0f172a] mt-1">95</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-[#e2e8f0] text-center shadow-[0_4px_0_0_#059669]">
                                <p class="text-[10px] font-bold uppercase text-[#64748b]">Matematika</p>
                                <p class="text-[16px] font-black text-[#059669] mt-1">98</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-[#e2e8f0] text-center">
                                <p class="text-[10px] font-bold uppercase text-[#64748b]">IPAS</p>
                                <p class="text-[16px] font-black text-[#0f172a] mt-1">92</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-[#e2e8f0] text-center">
                                <p class="text-[10px] font-bold uppercase text-[#64748b]">PJOK</p>
                                <p class="text-[16px] font-black text-[#0f172a] mt-1">96</p>
                            </div>
                        </div>
                    </td>
                </tr>

                {{-- Siswa 2 --}}
                <tr class="hover:bg-[#f8fafc] cursor-pointer transition" @click="expanded = expanded === 2 ? null : 2">
                    <td class="px-6 py-4 text-center font-bold text-[#94a3b8]">2</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#f59e0b] text-white flex items-center justify-center font-bold text-[10px]">BU</div>
                            <div><p class="font-black text-[#0f172a]">Budi Utomo</p></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center font-black text-[#f59e0b] text-[16px]">75.5</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded bg-[#fffbeb] border border-[#fde68a] text-[#d97706] text-[12px] font-black uppercase">C (Cukup)</span>
                    </td>
                    <td class="px-6 py-4 text-center text-[#1d4ed8]">
                        <svg class="h-5 w-5 mx-auto transition-transform" :class="expanded === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </td>
                </tr>
                {{-- Row Expand Siswa 2 --}}
                <tr x-show="expanded === 2" x-transition class="bg-[#f8fafc] border-b-2 border-[#e2e8f0]" style="display:none;">
                    <td colspan="5" class="px-6 py-5">
                        <div class="mb-4 text-[12px] font-bold text-[#0f172a] uppercase border-b border-[#e2e8f0] pb-2">Terdapat 1 Mapel Belum Tuntas (<75)</div>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                            <div class="bg-white p-3 rounded-lg border border-[#e2e8f0] text-center">
                                <p class="text-[10px] font-bold uppercase text-[#64748b]">Pendidikan Pancasila</p>
                                <p class="text-[16px] font-black text-[#0f172a] mt-1">78</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-[#e2e8f0] text-center">
                                <p class="text-[10px] font-bold uppercase text-[#64748b]">Bahasa Indonesia</p>
                                <p class="text-[16px] font-black text-[#0f172a] mt-1">80</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-[#e2e8f0] text-center shadow-[0_4px_0_0_#dc2626]">
                                <p class="text-[10px] font-bold uppercase text-[#dc2626]">Matematika</p>
                                <p class="text-[16px] font-black text-[#dc2626] mt-1">65</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-[#e2e8f0] text-center">
                                <p class="text-[10px] font-bold uppercase text-[#64748b]">IPAS</p>
                                <p class="text-[16px] font-black text-[#0f172a] mt-1">75</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-[#e2e8f0] text-center">
                                <p class="text-[10px] font-bold uppercase text-[#64748b]">PJOK</p>
                                <p class="text-[16px] font-black text-[#0f172a] mt-1">80</p>
                            </div>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
    @endif
@endsection
