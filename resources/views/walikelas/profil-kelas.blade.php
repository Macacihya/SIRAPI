<x-walikelas-shell
    :user="auth()->user()"
    active="profil-kelas"
    title="Profil Kelas"
    subtitle="Selamat datang di Panel Wali Kelas"
>
    <div class="space-y-8">
        {{-- Class Title --}}
        <div>
            <h1 class="text-[28px] font-black tracking-[-0.04em] text-[#0f172a] sm:text-[36px]">XII IPA 1</h1>
            <p class="mt-3 max-w-[600px] text-[14px] leading-[1.8] text-[#475569] sm:text-[16px]">
                Profil lengkap Kelas XII IPA 1 untuk tahun ajaran 2023/2024. Informasi mencakup detail struktural, data akademik, dan daftar inventaris siswa.
            </p>
        </div>

        {{-- Info Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            {{-- Wali Kelas --}}
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Wali Kelas</p>
                <div class="mt-4 flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#1e40af] text-[14px] font-bold text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <div>
                        <p class="text-[16px] font-bold text-[#0f172a]">Budi Santoso, S.Pd.</p>
                        <p class="text-[12px] text-[#64748b]">Fisika / Guru Senior</p>
                    </div>
                </div>
            </div>

            {{-- Total Siswa --}}
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Total Siswa</p>
                <div class="mt-3 text-center">
                    <span class="text-[48px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">32</span>
                    <p class="mt-1 text-[13px] text-[#64748b]">Siswa Terdaftar</p>
                </div>
            </div>

            {{-- Ketua Kelas --}}
            <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Ketua Kelas</p>
                <div class="mt-4">
                    <p class="text-[16px] font-bold text-[#0f172a]">Aditya Pratama</p>
                    <p class="mt-1 text-[12px] text-[#64748b]">NISN: 005421983</p>
                </div>
            </div>
        </div>

        {{-- Daftar Siswa --}}
        <div>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-[24px] font-black tracking-[-0.03em] text-[#0f172a] sm:text-[28px]">Daftar Siswa</h2>
                    <div class="mt-1 flex items-center gap-4 text-[13px] text-[#64748b]">
                        <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-[#0f172a]"></span> 18 Laki-laki</span>
                        <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-[#94a3b8]"></span> 14 Perempuan</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="relative flex-1 sm:w-[240px]">
                        <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke-width="2"/><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"/></svg>
                        <input type="text" placeholder="Cari nama siswa..." class="h-10 w-full rounded-lg border border-[#e2e8f0] bg-white pl-9 pr-4 text-[13px] text-[#334155] placeholder-[#94a3b8] outline-none transition focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20">
                    </div>
                    <button class="flex h-10 w-10 items-center justify-center rounded-lg border border-[#e2e8f0] text-[#64748b] transition hover:bg-[#f1f5f9]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 4h18M7 8h10M10 12h4" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                </div>
            </div>

            {{-- Student Grid --}}
            @php
                $students = [
                    ['initials' => 'AP', 'name' => 'Aditya Pratama', 'nisn' => '005421983', 'no' => 'IPA 1 - 01', 'role' => 'KETUA KELAS'],
                    ['initials' => 'AR', 'name' => 'Anisa Rahmawati', 'nisn' => '005421772', 'no' => 'IPA 1 - 02', 'role' => 'SEKRETARIS'],
                    ['initials' => 'BK', 'name' => 'Bagus Kurniawan', 'nisn' => '005421551', 'no' => 'IPA 1 - 03', 'role' => 'SISWA'],
                    ['initials' => 'CL', 'name' => 'Citra Lestari', 'nisn' => '005421449', 'no' => 'IPA 1 - 04', 'role' => 'BENDAHARA'],
                    ['initials' => 'DW', 'name' => 'Deden Wahyudi', 'nisn' => '005421003', 'no' => 'IPA 1 - 05', 'role' => 'SISWA'],
                    ['initials' => 'EF', 'name' => 'Eka Fitriani', 'nisn' => '005421991', 'no' => 'IPA 1 - 06', 'role' => 'SISWA'],
                    ['initials' => 'FR', 'name' => 'Fajar Ramadhan', 'nisn' => '005421228', 'no' => 'IPA 1 - 07', 'role' => 'SISWA'],
                    ['initials' => 'GP', 'name' => 'Gita Permata', 'nisn' => '005421337', 'no' => 'IPA 1 - 08', 'role' => 'SISWA'],
                ];
            @endphp

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($students as $s)
                    <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0] transition hover:shadow-md">
                        <div class="flex items-start justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#f1f5f9] text-[13px] font-bold text-[#1e40af]">
                                {{ $s['initials'] }}
                            </div>
                            <span class="rounded bg-[#f1f5f9] px-2 py-0.5 text-[10px] font-bold text-[#64748b]">{{ $s['no'] }}</span>
                        </div>
                        <p class="mt-3 text-[14px] font-bold text-[#0f172a]">{{ $s['name'] }}</p>
                        <p class="mt-0.5 text-[12px] text-[#64748b]">{{ $s['nisn'] }}</p>
                        @php
                            $roleColor = match($s['role']) {
                                'KETUA KELAS' => 'bg-[#0f172a] text-white',
                                'SEKRETARIS' => 'bg-[#1e40af] text-white',
                                'BENDAHARA' => 'bg-[#1d4ed8] text-white',
                                default => 'bg-[#f1f5f9] text-[#475569]',
                            };
                        @endphp
                        <span class="mt-3 inline-block rounded px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-[0.08em] {{ $roleColor }}">
                            {{ $s['role'] }}
                        </span>
                    </div>
                @endforeach
            </div>

            {{-- See All Button --}}
            <div class="mt-6 border-t border-[#e2e8f0] pt-5 text-center">
                <button class="text-[12px] font-extrabold uppercase tracking-[0.12em] text-[#1e40af] transition hover:text-[#3b82f6]">
                    Lihat Semua 32 Siswa
                </button>
            </div>
        </div>
    </div>
</x-walikelas-shell>
