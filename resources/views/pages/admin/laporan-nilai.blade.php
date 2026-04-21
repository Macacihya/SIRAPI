<x-admin-shell :user="auth()->user()" active="laporan-nilai" title="Laporan Breakdown Siswa" subtitle="Detail nilai komponen tiap siswa untuk keperluan administratif">
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
</x-admin-shell>
