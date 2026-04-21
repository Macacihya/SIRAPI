<x-admin-shell :user="auth()->user()" active="rekap-nilai" title="Rekapitulasi Nilai Seluruh SD" subtitle="Tinjauan agregasi pencapaian nilai seluruh tingkat kelas dan rombel">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-black tracking-tight text-[#0f172a]">Rekap Nilai Global (Akses Penuh)</h1>
            <p class="text-[13px] text-[#64748b] mt-1">Data scope tidak dibatasi kuerinya (Akses Administratif).</p>
        </div>
        <div class="flex gap-2">
            <button class="bg-[#1d4ed8] hover:bg-[#1e40af] text-white px-4 py-2 rounded-lg text-[13px] font-bold flex items-center gap-2 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export Excel Utama
            </button>
        </div>
    </div>

    {{-- Filter Lengkap --}}
    <div class="bg-white p-5 rounded-xl border border-[#e2e8f0] flex flex-wrap gap-4 items-end shadow-sm">
        <div class="flex-1 min-w-[150px]">
            <label class="text-[11px] font-bold uppercase text-[#64748b]">Tahun Ajaran</label>
            <select class="w-full mt-1.5 border border-[#e2e8f0] rounded-lg px-3 py-2 text-[13px] font-medium outline-none focus:border-[#3b82f6]">
                <option>2026/2027 Ganjil</option>
            </select>
        </div>
        <div class="flex-1 min-w-[150px]">
            <label class="text-[11px] font-bold uppercase text-[#64748b]">Tingkat Kelas</label>
            <select class="w-full mt-1.5 border border-[#e2e8f0] rounded-lg px-3 py-2 text-[13px] font-medium outline-none focus:border-[#3b82f6]">
                <option>Menampilkan Semua Kelas</option>
                <option>Kelas 1</option>
                <option>Kelas 2</option>
                <option>Kelas 3</option>
                <option>Kelas 4</option>
                <option>Kelas 5</option>
                <option>Kelas 6</option>
            </select>
        </div>
        <div class="flex-1 min-w-[150px]">
            <label class="text-[11px] font-bold uppercase text-[#64748b]">Rombel</label>
            <select class="w-full mt-1.5 border border-[#e2e8f0] rounded-lg px-3 py-2 text-[13px] font-medium outline-none focus:border-[#3b82f6]">
                <option>Semua Rombel (A-C)</option>
            </select>
        </div>
        <div>
            <button class="bg-[#0f172a] hover:bg-[#1e293b] text-white px-6 py-2 rounded-lg text-[13px] font-bold transition">Tampilkan Agregasi</button>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-xl border border-[#e2e8f0] shadow-sm overflow-hidden">
        <div class="p-4 border-b border-[#e2e8f0] bg-[#f8fafc]">
            <h3 class="text-[14px] font-bold text-[#0f172a]">Data Rata-Rata Kelas Terhadap Mata Pelajaran Utama</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-[12px]">
                <thead>
                    <tr class="border-b border-[#e2e8f0]">
                        <th class="px-4 py-3 text-left font-bold text-[#475569] uppercase sticky left-0 bg-white shadow-[1px_0_0_#e2e8f0]">Rombel</th>
                        <th class="px-4 py-3 text-center font-bold text-[#475569] uppercase">PAI/Budi Pekerti</th>
                        <th class="px-4 py-3 text-center font-bold text-[#475569] uppercase">Pendidikan Pancasila</th>
                        <th class="px-4 py-3 text-center font-bold text-[#475569] uppercase">Bahasa Indonesia</th>
                        <th class="px-4 py-3 text-center font-bold text-[#475569] uppercase">Matematika</th>
                        <th class="px-4 py-3 text-center font-bold text-[#475569] uppercase">IPAS</th>
                        <th class="px-4 py-3 text-center font-bold text-[#475569] uppercase">PJOK</th>
                        <th class="px-4 py-3 text-center font-black text-[#0f172a] uppercase border-l border-[#e2e8f0]">Agregat Rombel</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f1f5f9]">
                    <tr class="hover:bg-[#f8fafc] transition">
                        <td class="px-4 py-3 font-bold text-[#0f172a] sticky left-0 bg-white shadow-[1px_0_0_#e2e8f0]">Kelas 4-A</td>
                        <td class="px-4 py-3 text-center font-bold text-[#0f172a]">85.2</td>
                        <td class="px-4 py-3 text-center font-bold text-[#0f172a]">80.4</td>
                        <td class="px-4 py-3 text-center font-bold text-[#0f172a]">78.5</td>
                        <td class="px-4 py-3 text-center font-bold text-[#0f172a]">75.8</td>
                        <td class="px-4 py-3 text-center font-bold text-[#0f172a]">81.0</td>
                        <td class="px-4 py-3 text-center font-bold text-[#0f172a]">86.5</td>
                        <td class="px-4 py-3 text-center font-black text-[#1d4ed8] border-l border-[#e2e8f0]">81.2</td>
                    </tr>
                    <tr class="hover:bg-[#f8fafc] transition">
                        <td class="px-4 py-3 font-bold text-[#0f172a] sticky left-0 bg-white shadow-[1px_0_0_#e2e8f0]">Kelas 4-B</td>
                        <td class="px-4 py-3 text-center font-bold text-[#0f172a]">82.0</td>
                        <td class="px-4 py-3 text-center font-bold text-[#0f172a]">79.0</td>
                        <td class="px-4 py-3 text-center font-bold text-[#0f172a]">77.2</td>
                        <td class="px-4 py-3 text-center font-bold text-[#0f172a]">74.5</td>
                        <td class="px-4 py-3 text-center font-bold text-[#0f172a]">79.0</td>
                        <td class="px-4 py-3 text-center font-bold text-[#0f172a]">85.0</td>
                        <td class="px-4 py-3 text-center font-black text-[#1d4ed8] border-l border-[#e2e8f0]">79.4</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-admin-shell>
