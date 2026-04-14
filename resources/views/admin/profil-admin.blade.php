<x-admin-shell
    :user="auth()->user()"
    active="profil"
    title="Dashboard Utama"
    subtitle="Selamat datang di Panel Admin TU"
>
    @php
        $user = auth()->user();
        $initials = collect(explode(' ', trim($user->name ?? 'SIRAPI')))
            ->filter()->take(2)
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->implode('');
    @endphp

    <div class="space-y-6">

        {{-- ─── TOP GRID: LEFT PROFILE + RIGHT BIODATA ──────── --}}
        <div class="grid gap-6 lg:grid-cols-[280px_minmax(0,1fr)]">

            {{-- LEFT: Avatar + Actions + Account Info --}}
            <div class="space-y-4">
                {{-- Avatar Card --}}
                <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6 text-center">
                    <div class="relative mx-auto h-[100px] w-[100px]">
                        <div class="flex h-full w-full items-center justify-center rounded-full bg-[#f1f5f9]">
                            <svg class="h-12 w-12 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <button class="absolute bottom-0 right-0 flex h-7 w-7 items-center justify-center rounded-full bg-[#e2e8f0] text-[#475569] shadow transition hover:bg-[#cbd5e1]">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke-width="2"></path><circle cx="12" cy="13" r="3" stroke-width="2"></circle></svg>
                        </button>
                    </div>
                    <h2 class="mt-4 text-[18px] font-black text-[#0f172a]">{{ $user->name }}</h2>
                    <p class="mt-0.5 text-[13px] text-[#64748b]">Admin TU</p>

                    <button class="mt-5 flex h-[42px] w-full items-center justify-center gap-2 rounded-[8px] bg-[#1d4ed8] text-[13px] font-bold text-white transition hover:bg-[#1e40af]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        Ubah Profil
                    </button>
                    <button class="mt-2 flex w-full items-center justify-center gap-2 py-2 text-[13px] font-semibold text-[#475569] transition hover:text-[#1d4ed8]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        Ubah Kata Sandi
                    </button>
                </div>

                {{-- Account Info --}}
                <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Informasi Akun</p>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between text-[13px]">
                            <span class="text-[#64748b]">Status Akun</span>
                            <span class="inline-flex items-center gap-1 rounded-md border border-[#a7f3d0] bg-[#ecfdf5] px-2 py-0.5 text-[10px] font-bold text-[#059669]">AKTIF</span>
                        </div>
                        <div class="flex items-center justify-between text-[13px]">
                            <span class="text-[#64748b]">Tahun Ajaran</span>
                            <span class="font-bold text-[#0f172a]">2023/2024 Genap</span>
                        </div>
                        <div class="flex items-center justify-between text-[13px]">
                            <span class="text-[#64748b]">Login Terakhir</span>
                            <span class="font-bold text-[#0f172a]">Hari ini, 08:24</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Biodata + Riwayat --}}
            <div class="space-y-6">
                {{-- Biodata Lengkap --}}
                <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                    <h3 class="flex items-center gap-2 text-[16px] font-bold text-[#0f172a]">
                        <svg class="h-5 w-5 text-[#1d4ed8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        Biodata Lengkap
                    </h3>
                    <div class="mt-5 grid gap-x-8 gap-y-5 sm:grid-cols-2">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Lengkap</p>
                            <p class="mt-1 text-[14px] font-bold text-[#0f172a]">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP / ID Guru</p>
                            <p class="mt-1 text-[14px] font-bold text-[#0f172a]">19880412 201503 1 002</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Jabatan</p>
                            <p class="mt-1 text-[14px] font-bold text-[#0f172a]">Admin TU</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email Instansi</p>
                            <p class="mt-1 text-[14px] font-bold text-[#0f172a]">{{ $user->email ?? 'admin@sekolah.sch.id' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nomor Telepon</p>
                            <p class="mt-1 text-[14px] font-bold text-[#0f172a]">+62 812-3456-7890</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Unit Kerja</p>
                            <p class="mt-1 text-[14px] font-bold text-[#0f172a]">SMK Negeri 7 Batak</p>
                        </div>
                    </div>
                </div>

                {{-- Riwayat Peran & Penugasan --}}
                <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                    <h3 class="flex items-center gap-2 text-[16px] font-bold text-[#0f172a]">
                        <svg class="h-5 w-5 text-[#1d4ed8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"></circle><path d="M12 6v6l4 2" stroke-width="2" stroke-linecap="round"></path></svg>
                        Riwayat Peran & Penugasan
                    </h3>

                    <div class="mt-6 relative">
                        {{-- Timeline line --}}
                        <div class="absolute left-[7px] top-2 bottom-2 w-[2px] bg-[#e2e8f0]"></div>

                        <div class="space-y-7">
                            @foreach ([
                                ['year' => '2023 - Sekarang', 'title' => 'Admin TU', 'desc' => 'Bertanggung jawab penuh atas administrasi, pengelolaan data, dan pelaporan nilai rapor siswa.', 'active' => true],
                                ['year' => '2021 - 2023', 'title' => 'Guru Mata Pelajaran Fisika', 'desc' => 'Pengampu utama mata pelajaran Fisika untuk seluruh jenjang kelas XI dan XII.', 'active' => false],
                                ['year' => '2015 - 2021', 'title' => 'Staf Kurikulum Bidang Akademik', 'desc' => 'Membantu perencanaan jadwal belajar mengajar dan koordinasi sistem penilaian sekolah.', 'active' => false],
                            ] as $riwayat)
                                <div class="relative flex gap-5 pl-6">
                                    {{-- Dot --}}
                                    <div class="absolute left-0 top-1 flex h-[16px] w-[16px] items-center justify-center rounded-full {{ $riwayat['active'] ? 'bg-[#1d4ed8]' : 'bg-[#e2e8f0]' }}">
                                        @if ($riwayat['active'])
                                            <div class="h-[6px] w-[6px] rounded-full bg-white"></div>
                                        @endif
                                    </div>

                                    <div>
                                        <p class="text-[11px] font-bold uppercase tracking-[0.12em] {{ $riwayat['active'] ? 'text-[#1d4ed8]' : 'text-[#94a3b8]' }}">{{ $riwayat['year'] }}</p>
                                        <p class="mt-1 text-[15px] font-black text-[#0f172a]">{{ $riwayat['title'] }}</p>
                                        <p class="mt-1 text-[13px] leading-[1.7] text-[#64748b]">{{ $riwayat['desc'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── BOTTOM: STATUS CARDS ────────────────────────── --}}
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="flex items-center justify-between rounded-[14px] border border-[#e2e8f0] bg-white p-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#eff6ff] text-[#1d4ed8]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </div>
                    <div>
                        <p class="text-[14px] font-bold text-[#0f172a]">Sertifikasi Pendidik</p>
                        <p class="mt-0.5 text-[12px] text-[#64748b]">Sertifikat No. 2206/CERT/2019 · <span class="text-[#059669] font-semibold">Aktif</span></p>
                    </div>
                </div>
                <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] transition hover:bg-[#f1f5f9] hover:text-[#1d4ed8]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </button>
            </div>
            <div class="flex items-center justify-between rounded-[14px] border border-[#e2e8f0] bg-white p-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#eff6ff] text-[#1d4ed8]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" stroke-width="2"></path></svg>
                    </div>
                    <div>
                        <p class="text-[14px] font-bold text-[#0f172a]">Verifikasi Data Pokok</p>
                        <p class="mt-0.5 text-[12px] text-[#64748b]">Sinkronisasi Terakhir: 12 Jan 2024</p>
                    </div>
                </div>
                <button class="flex h-8 w-8 items-center justify-center rounded-lg text-[#94a3b8] transition hover:bg-[#f1f5f9] hover:text-[#1d4ed8]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </button>
            </div>
        </div>

        {{-- ─── FOOTER ──────────────────────────────────────── --}}
        <p class="text-center text-[11px] font-semibold tracking-[0.1em] text-[#94a3b8]">
            SIRAPI &copy; 2024 &bull; SISTEM RAPOR PINTAR
        </p>

    </div>
</x-admin-shell>
