<x-walikelas-shell
    :user="auth()->user()"
    active="data-siswa"
    title="Data Siswa Kelas"
    subtitle="Selamat datang di Panel Wali Kelas"
>
    <div class="space-y-6">
        {{-- Title --}}
        <div>
            <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a] sm:text-[36px]">Data Siswa Kelas</h1>
            <p class="mt-1 text-[14px] text-[#475569]">Manajemen data perwalian Kelas XII IPA 1 — Tahun Ajaran 2023/2024</p>
        </div>

        {{-- Stat Cards --}}
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <div class="flex items-center gap-3">
                    <svg class="h-6 w-6 text-[#1e40af]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Total Siswa</p>
                </div>
                <p class="mt-3 text-[42px] font-black leading-none tracking-[-0.04em] text-[#0f172a]">36</p>
            </div>

            <div class="rounded-xl bg-white p-5 ring-1 ring-[#3b82f6]/30">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Laki-laki</p>
                <div class="mt-3 flex items-end justify-between">
                    <p class="text-[42px] font-black leading-none tracking-[-0.04em] text-[#0f172a]">20</p>
                    <p class="pb-1 text-[13px] font-semibold text-[#64748b]">55.5%</p>
                </div>
                <div class="mt-3 h-1 rounded-full bg-[#e2e8f0]"><div class="h-full w-[55.5%] rounded-full bg-[#1d4ed8]"></div></div>
            </div>

            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Perempuan</p>
                <div class="mt-3 flex items-end justify-between">
                    <p class="text-[42px] font-black leading-none tracking-[-0.04em] text-[#0f172a]">16</p>
                    <p class="pb-1 text-[13px] font-semibold text-[#64748b]">44.5%</p>
                </div>
                <div class="mt-3 h-1 rounded-full bg-[#e2e8f0]"><div class="h-full w-[44.5%] rounded-full bg-[#3b82f6]"></div></div>
            </div>
        </div>

        {{-- Search + Actions --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"/><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"/></svg>
                <input type="text" placeholder="Cari berdasarkan Nama atau NISN..." class="h-11 w-full rounded-lg border border-[#e2e8f0] bg-white pl-11 pr-4 text-[13px] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
            </div>
            <button class="h-11 rounded-lg border border-[#e2e8f0] bg-white px-5 text-[11px] font-extrabold uppercase tracking-[0.12em] text-[#475569] transition hover:bg-[#f1f5f9]">Terapkan</button>
            <button class="flex h-11 items-center gap-2 rounded-lg border border-[#e2e8f0] bg-white px-5 text-[11px] font-extrabold uppercase tracking-[0.12em] text-[#475569] transition hover:bg-[#f1f5f9]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 4h18M7 8h10M10 12h4" stroke-width="2" stroke-linecap="round"/></svg>
                Filter
            </button>
            <button class="flex h-11 items-center gap-2 rounded-lg bg-[#0f172a] px-5 text-[11px] font-extrabold uppercase tracking-[0.12em] text-white transition hover:bg-[#1e293b]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Export
            </button>
        </div>

        {{-- Table --}}
        @php
            $siswa = [
                ['no' => '01', 'nis' => '12001 / 005432101', 'init' => 'AS', 'nama' => 'ADITIA SAPUTRA', 'jk' => 'Laki-laki'],
                ['no' => '02', 'nis' => '12002 / 005432102', 'init' => 'BP', 'nama' => 'BELLA PUTRI', 'jk' => 'Perempuan'],
                ['no' => '03', 'nis' => '12003 / 005432103', 'init' => 'DR', 'nama' => 'DANI RAMDAN', 'jk' => 'Laki-laki'],
                ['no' => '04', 'nis' => '12004 / 005432104', 'init' => 'FA', 'nama' => 'FARAH AZZAHRA', 'jk' => 'Perempuan'],
                ['no' => '05', 'nis' => '12005 / 005432105', 'init' => 'GK', 'nama' => 'GILANG KURNIAWAN', 'jk' => 'Laki-laki'],
            ];
        @endphp

        <div class="overflow-x-auto rounded-xl bg-white ring-1 ring-[#e2e8f0]">
            <table class="w-full text-left text-[13px]">
                <thead>
                    <tr class="border-b border-[#e2e8f0] bg-[#f8fafc]">
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">No</th>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">NIS / NISN</th>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Nama Siswa</th>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Jenis Kelamin</th>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $s)
                        <tr class="border-b border-[#f1f5f9] transition hover:bg-[#f8fafc]">
                            <td class="px-5 py-4 text-[#64748b]">{{ $s['no'] }}</td>
                            <td class="px-5 py-4 font-bold text-[#0f172a]">{{ $s['nis'] }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-md bg-[#1e40af] text-[10px] font-bold text-white">{{ $s['init'] }}</div>
                                    <span class="font-bold text-[#0f172a]">{{ $s['nama'] }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-[#475569]">{{ $s['jk'] }}</td>
                            <td class="px-5 py-4">
                                <button class="rounded-md border border-[#e2e8f0] px-3 py-1.5 text-[11px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Detail →</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex flex-col items-center justify-between gap-3 sm:flex-row">
            <p class="text-[11px] font-bold uppercase tracking-[0.1em] text-[#64748b]">Menampilkan 5 dari 36 siswa</p>
            <div class="flex items-center gap-1">
                <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] transition hover:bg-[#f1f5f9]">‹</button>
                <button class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#1d4ed8] text-[12px] font-bold text-white">1</button>
                <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-bold text-[#64748b] transition hover:bg-[#f1f5f9]">2</button>
                <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[12px] font-bold text-[#64748b] transition hover:bg-[#f1f5f9]">3</button>
                <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[#64748b] transition hover:bg-[#f1f5f9]">›</button>
            </div>
        </div>
    </div>
</x-walikelas-shell>
