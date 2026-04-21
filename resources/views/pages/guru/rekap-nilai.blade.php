<x-guru-shell :user="auth()->user()" active="rekap-nilai" title="Rekap Kelas (Agregasi)" subtitle="Pantau performa agregat kelas pada mata pelajaran yang Anda ampu">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-black tracking-tight text-[#0f172a]">Rekap Pencapaian Mapel</h1>
            <p class="text-[13px] text-[#64748b] mt-1">Data scope dibatasi pada kueri: Guru ID dan Mapel ID terkait.</p>
        </div>
        <div class="flex gap-2">
            <button class="bg-[#1d4ed8] hover:bg-[#1e40af] text-white px-4 py-2 rounded-lg text-[13px] font-bold flex items-center gap-2 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Unduh PDF Rekap
            </button>
        </div>
    </div>

    {{-- Filter Data Scope --}}
    <div class="bg-white p-4 justify-between rounded-xl border border-[#e2e8f0] flex flex-wrap gap-4 items-center">
        <div class="flex gap-4">
            <select class="bg-[#f8fafc] border border-[#e2e8f0] rounded-lg px-4 py-2 text-[13px] font-bold text-[#0f172a] outline-none"><option>Tahun Ajaran 2026/2027 Ganjil</option></select>
            <select class="bg-[#f1f5f9] border border-[#e2e8f0] text-[#64748b] rounded-lg px-4 py-2 text-[13px] font-bold outline-none cursor-not-allowed" disabled>
                <option>Hanya Kelas 4-A (Kelas Diampu)</option>
            </select>
        </div>
        <div class="text-[12px] font-medium text-[#64748b] bg-[#f8fafc] px-3 py-1.5 rounded-md border border-[#e2e8f0]">Mapel Terotorisasi: <span class="font-black text-[#1d4ed8]">PJOK (Pendidikan Jasmani)</span></div>
    </div>

    {{-- Class Leaderboard & Aggregate Stats --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Aggregate Mean Card --}}
        <div class="bg-[#0f172a] p-6 rounded-2xl text-white shadow-xl relative overflow-hidden flex flex-col justify-center">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white opacity-5 rounded-full blur-2xl"></div>
            <p class="text-[12px] font-bold uppercase tracking-wider text-[#94a3b8]">Rata-Rata Kelas</p>
            <div class="flex items-end gap-3 mt-1">
                <h3 class="text-[48px] font-black leading-none">82.5</h3>
            </div>
            
            <div class="space-y-4 mt-6">
                <div>
                    <div class="flex justify-between text-[11px] font-bold mb-1"><span class="text-[#cbd5e1]">Tuntas KKM (>75)</span><span class="text-[#a7f3d0]">28 Siswa</span></div>
                    <div class="w-full bg-[#1e293b] rounded-full h-1.5"><div class="bg-[#10b981] h-1.5 rounded-full w-[94%]"></div></div>
                </div>
                <div>
                    <div class="flex justify-between text-[11px] font-bold mb-1"><span class="text-[#cbd5e1]">Perlu Remedial</span><span class="text-[#fca5a5]">2 Siswa</span></div>
                    <div class="w-full bg-[#1e293b] rounded-full h-1.5"><div class="bg-[#ef4444] h-1.5 rounded-full w-[6%]"></div></div>
                </div>
            </div>
        </div>

        {{-- Class Leaderboard --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-[#e2e8f0] p-6 shadow-sm">
            <div class="flex justify-between items-end mb-4">
                <div>
                    <h3 class="text-[16px] font-black text-[#0f172a]">Peringkat Teratas (PJOK)</h3>
                    <p class="text-[11px] text-[#64748b]">Siswa dengan pencapaian tertinggi di mata pelajaran Anda.</p>
                </div>
            </div>
            
            <div class="grid grid-cols-3 gap-4 h-[180px] items-end mt-8 relative">
                {{-- Rank 2 --}}
                <div class="flex flex-col items-center group">
                    <p class="text-[14px] font-black text-[#0f172a] mb-1">Rani (92)</p>
                    <div class="w-full bg-gradient-to-t from-[#e2e8f0] to-[#f1f5f9] h-[100px] rounded-t-xl border-t-4 border-[#94a3b8] flex justify-center pt-2 transition-all group-hover:h-[110px]">
                        <span class="w-6 h-6 rounded-full bg-[#94a3b8] text-white flex items-center justify-center text-[10px] font-black shadow-lg">2</span>
                    </div>
                </div>
                {{-- Rank 1 --}}
                <div class="flex flex-col items-center group">
                    <p class="text-[14px] font-black text-[#0f172a] mb-1">Dika (96)</p>
                    <div class="w-full bg-gradient-to-t from-[#bfdbfe] to-[#eff6ff] h-[130px] rounded-t-xl border-t-4 border-[#3b82f6] flex justify-center pt-2 transition-all shadow-[0_-10px_20px_rgba(59,130,246,0.2)] group-hover:h-[140px]">
                        <span class="w-8 h-8 rounded-full bg-[#3b82f6] text-white flex items-center justify-center text-[14px] font-black shadow-lg ring-4 ring-[#dbeafe]">1</span>
                    </div>
                </div>
                {{-- Rank 3 --}}
                <div class="flex flex-col items-center group">
                    <p class="text-[14px] font-black text-[#0f172a] mb-1">Budi (89)</p>
                    <div class="w-full bg-gradient-to-t from-[#ffedd5] to-[#fff7ed] h-[80px] rounded-t-xl border-t-4 border-[#fdba74] flex justify-center pt-2 transition-all group-hover:h-[90px]">
                        <span class="w-6 h-6 rounded-full bg-[#fdba74] text-white flex items-center justify-center text-[10px] font-black shadow-lg">3</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</x-guru-shell>
