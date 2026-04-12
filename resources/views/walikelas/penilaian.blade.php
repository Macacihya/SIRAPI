<x-walikelas-shell
    :user="auth()->user()"
    active="penilaian"
    title="Penilaian Kelas"
    subtitle="Selamat datang di Panel Wali Kelas"
>
    <div class="space-y-6">
        {{-- Title + Actions --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a] sm:text-[36px]">Penilaian Kelas</h1>
                <p class="mt-1 text-[14px] text-[#475569]">Rekapitulasi capaian akademik siswa Kelas XII-MIPA 1 untuk Semester Ganjil 2023/2024.</p>
            </div>
            <div class="flex items-center gap-2">
                <button class="flex items-center gap-2 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2.5 text-[11px] font-extrabold uppercase tracking-wider text-[#475569] transition hover:bg-[#f1f5f9]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Export PDF
                </button>
                <button class="flex items-center gap-2 rounded-lg bg-[#0f172a] px-4 py-2.5 text-[11px] font-extrabold uppercase tracking-wider text-white transition hover:bg-[#1e293b]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 5v14m-7-7h14" stroke-width="2" stroke-linecap="round"/></svg>
                    Input Nilai Baru
                </button>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Total Siswa</p>
                <p class="mt-2"><span class="text-[36px] font-black leading-none text-[#0f172a]">32</span> <span class="text-[13px] text-[#64748b]">Peserta</span></p>
            </div>
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Rata-rata Kelas</p>
                <p class="mt-2"><span class="text-[36px] font-black leading-none text-[#0f172a]">84.5</span> <span class="text-[13px] text-[#64748b]">Poin</span></p>
            </div>
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#3b82f6]/30">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Ketuntasan</p>
                <p class="mt-2"><span class="text-[36px] font-black leading-none text-[#0f172a]">92%</span> <span class="text-[13px] text-[#64748b]">Tuntas</span></p>
            </div>
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Update Terakhir</p>
                <p class="mt-2 text-[24px] font-black leading-tight text-[#0f172a]">12 Okt 2023</p>
            </div>
        </div>

        {{-- Search + Filter --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"/><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"/></svg>
                <input type="text" placeholder="Cari nama atau NIS siswa..." class="h-11 w-full rounded-lg border border-[#e2e8f0] bg-white pl-11 pr-4 text-[13px] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
            </div>
            <div class="flex items-center gap-2">
                <select class="h-11 rounded-lg border border-[#e2e8f0] bg-white px-4 pr-8 text-[11px] font-bold uppercase tracking-wider text-[#475569] outline-none">
                    <option>Semua Mata Pelajaran</option>
                    <option>Matematika</option>
                    <option>B. Indonesia</option>
                    <option>Fisika</option>
                </select>
                <button class="flex h-11 w-11 items-center justify-center rounded-lg border border-[#e2e8f0] text-[#64748b] transition hover:bg-[#f1f5f9]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 4h18M7 8h10M10 12h4" stroke-width="2" stroke-linecap="round"/></svg>
                </button>
            </div>
        </div>

        {{-- Grade Table --}}
        @php
            $grades = [
                ['no'=>'01','init'=>'AA','nama'=>'Achmad Albar','mtk'=>85,'bi'=>90,'fis'=>78,'avg'=>84.3,'status'=>'TUNTAS'],
                ['no'=>'02','init'=>'BM','nama'=>'Bella Monica','mtk'=>92,'bi'=>88,'fis'=>95,'avg'=>91.6,'status'=>'TUNTAS'],
                ['no'=>'03','init'=>'DP','nama'=>'Dandi Pratama','mtk'=>65,'bi'=>70,'fis'=>62,'avg'=>65.6,'status'=>'BELUM'],
                ['no'=>'04','init'=>'EK','nama'=>'Endah Kartika','mtk'=>88,'bi'=>82,'fis'=>80,'avg'=>83.3,'status'=>'TUNTAS'],
                ['no'=>'05','init'=>'FA','nama'=>'Farhan Azis','mtk'=>78,'bi'=>85,'fis'=>77,'avg'=>80.0,'status'=>'TUNTAS'],
            ];
        @endphp

        <div class="overflow-x-auto rounded-xl bg-white ring-1 ring-[#e2e8f0]">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">No</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Nama Siswa</th>
                        <th class="px-4 py-3 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Matematika</th>
                        <th class="px-4 py-3 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">B. Indonesia</th>
                        <th class="px-4 py-3 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Fisika</th>
                        <th class="px-4 py-3 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Rata-rata</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grades as $g)
                        <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                            <td class="px-5 py-4 text-[#64748b]">{{ $g['no'] }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-md bg-[#1e40af] text-[10px] font-bold text-white">{{ $g['init'] }}</div>
                                    <span class="font-bold text-[#0f172a]">{{ $g['nama'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center text-[#475569]">{{ $g['mtk'] }}</td>
                            <td class="px-4 py-4 text-center text-[#475569]">{{ $g['bi'] }}</td>
                            <td class="px-4 py-4 text-center text-[#475569]">{{ $g['fis'] }}</td>
                            <td class="px-4 py-4 text-center font-bold {{ $g['avg'] < 75 ? 'text-[#dc2626]' : 'text-[#0f172a]' }}">{{ number_format($g['avg'], 1) }}</td>
                            <td class="px-5 py-4 text-center">
                                @if ($g['status'] === 'TUNTAS')
                                    <span class="rounded px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider bg-[#f0fdf4] text-[#16a34a]">Tuntas</span>
                                @else
                                    <span class="rounded px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider bg-[#fef2f2] text-[#dc2626]">Belum</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex flex-col items-center justify-between gap-3 sm:flex-row">
            <p class="text-[11px] font-bold uppercase tracking-[0.1em] text-[#64748b]">Menampilkan 5 dari 32 siswa</p>
            <div class="flex items-center gap-1">
                <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] transition hover:bg-[#f1f5f9]">‹</button>
                <button class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#1d4ed8] text-[12px] font-bold text-white">1</button>
                <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-bold text-[#64748b] transition hover:bg-[#f1f5f9]">2</button>
                <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-bold text-[#64748b] transition hover:bg-[#f1f5f9]">3</button>
                <span class="px-1 text-[#94a3b8]">…</span>
                <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-bold text-[#64748b] transition hover:bg-[#f1f5f9]">7</button>
                <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[#64748b] transition hover:bg-[#f1f5f9]">›</button>
            </div>
        </div>

        {{-- Bottom: Catatan + Pengesahan --}}
        <div class="grid gap-6 lg:grid-cols-[1fr_300px]">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Catatan Wali Kelas</p>
                <div class="mt-3 rounded-xl bg-[#f1f5f9] p-5 ring-1 ring-[#e2e8f0]">
                    <p class="text-[14px] italic leading-[1.8] text-[#334155]">
                        "Sebagian besar siswa menunjukkan progress positif pada mata pelajaran sains. Perlu perhatian khusus bagi 3 siswa yang belum mencapai KKM pada mata pelajaran Fisika sebelum penutupan nilai semester."
                    </p>
                </div>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Pengesahan</p>
                <div class="mt-3 flex flex-col items-center rounded-xl bg-white p-6 ring-1 ring-[#e2e8f0]">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-[#f1f5f9] text-[#94a3b8]">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <p class="mt-3 text-[14px] font-bold text-[#0f172a]">Budi Santoso, S.Pd</p>
                    <p class="mt-0.5 text-[11px] text-[#64748b]">NIP. 198503122010121004</p>
                </div>
            </div>
        </div>
    </div>
</x-walikelas-shell>
