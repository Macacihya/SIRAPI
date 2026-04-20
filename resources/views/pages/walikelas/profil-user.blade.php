{{-- Halaman: profil-user --- menggunakan layout walikelas --}}
@extends('layouts.walikelas')
@section('title', 'Profil User')
@section('subtitle', 'Data profil wali kelas')
@section('active', 'profil')

@section('content')
user()"
    active=""
    title="Dashboard Utama"
    subtitle="Selamat datang di Panel Wali Kelas"
>
    @php
        $user = auth()->user();
    @endphp

    <div class="space-y-8">
        <div class="grid gap-6 lg:grid-cols-[320px_1fr]">
            {{-- Left: Profile Card --}}
            <div class="space-y-4">
                {{-- Photo + Name --}}
                <div class="rounded-xl bg-white p-6 text-center ring-1 ring-[#e2e8f0]">
                    <div class="relative mx-auto h-32 w-32 rounded-2xl bg-[#f1f5f9] flex items-center justify-center overflow-hidden">
                        <svg class="h-16 w-16 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <button class="absolute bottom-2 right-2 flex h-7 w-7 items-center justify-center rounded-full bg-[#0f172a] text-white shadow-lg">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke-width="2"/><circle cx="12" cy="13" r="3" stroke-width="2"/></svg>
                        </button>
                    </div>
                    <h2 class="mt-4 text-[20px] font-black text-[#0f172a]">{{ $user->name ?? 'Heryanto Pratama, S.Pd.' }}</h2>
                    <p class="mt-0.5 text-[13px] text-[#64748b]">Wali Kelas XII IPA 1</p>

                    <button class="mt-5 flex w-full items-center justify-center gap-2 rounded-lg bg-[#0f172a] py-2.5 text-[12px] font-bold text-white transition hover:bg-[#1e293b]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Ubah Profil
                    </button>
                    <button class="mt-2 flex w-full items-center justify-center gap-2 rounded-lg border border-[#e2e8f0] bg-white py-2.5 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Ubah Kata Sandi
                    </button>
                </div>

                {{-- Info Akun --}}
                <div class="rounded-xl bg-white p-5 ring-1 ring-[#e2e8f0]">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#64748b]">Informasi Akun</p>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[13px] text-[#475569]">Status Akun</span>
                            <span class="rounded px-2 py-0.5 text-[10px] font-extrabold uppercase bg-[#f0fdf4] text-[#16a34a]">Aktif</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[13px] text-[#475569]">Tahun Ajaran</span>
                            <span class="text-[13px] font-bold text-[#0f172a]">2023/2024 Genap</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[13px] text-[#475569]">Login Terakhir</span>
                            <span class="text-[13px] font-bold text-[#0f172a]">Hari ini, 08:24</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Biodata + History --}}
            <div class="space-y-6">
                {{-- Biodata --}}
                <div class="rounded-xl bg-white p-6 ring-1 ring-[#e2e8f0]">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-[#1d4ed8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <h3 class="text-[18px] font-black text-[#0f172a]">Biodata Lengkap</h3>
                    </div>
                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        @foreach ([
                            ['label' => 'Nama Lengkap', 'value' => $user->name ?? 'Heryanto Pratama, S.Pd.'],
                            ['label' => 'NIP / ID Guru', 'value' => '19880412 201503 1 002'],
                            ['label' => 'Jabatan', 'value' => 'Wali Kelas'],
                            ['label' => 'Email Instansi', 'value' => 'heryanto.p@sekolah.sch.id'],
                            ['label' => 'Nomor Telepon', 'value' => '+62 812-3456-7890'],
                            ['label' => 'Unit Kerja', 'value' => 'SMK Negeri 7 Batak'],
                        ] as $bio)
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748b]">{{ $bio['label'] }}</p>
                                <p class="mt-1 text-[14px] font-semibold text-[#0f172a]">{{ $bio['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Riwayat --}}
                <div class="rounded-xl bg-white p-6 ring-1 ring-[#e2e8f0]">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-[#1d4ed8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <h3 class="text-[18px] font-black text-[#0f172a]">Riwayat Peran & Penugasan</h3>
                    </div>
                    <div class="mt-6 space-y-6">
                        @foreach ([
                            ['year' => '2023 - SEKARANG', 'role' => 'Wali Kelas XII IPA 1', 'desc' => 'Bertanggung jawab penuh atas administrasi, bimbingan akademik, dan pelaporan nilai rapor siswa kelas XII IPA 1.', 'active' => true],
                            ['year' => '2021 - 2023', 'role' => 'Guru Mata Pelajaran Fisika', 'desc' => 'Pengampu utama mata pelajaran Fisika untuk seluruh jenjang kelas XI dan XII.', 'active' => false],
                            ['year' => '2015 - 2021', 'role' => 'Staf Kurikulum Bidang Akademik', 'desc' => 'Membantu perencanaan jadwal belajar mengajar dan koordinasi sistem penilaian sekolah.', 'active' => false],
                        ] as $r)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="h-3 w-3 rounded-full {{ $r['active'] ? 'bg-[#1d4ed8]' : 'bg-[#cbd5e1]' }}"></div>
                                    <div class="w-px flex-1 bg-[#e2e8f0]"></div>
                                </div>
                                <div class="pb-6">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] {{ $r['active'] ? 'text-[#1d4ed8]' : 'text-[#64748b]' }}">{{ $r['year'] }}</p>
                                    <p class="mt-1 text-[15px] font-bold text-[#0f172a]">{{ $r['role'] }}</p>
                                    <p class="mt-1 text-[13px] leading-relaxed text-[#475569]">{{ $r['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Bottom Cards --}}
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="flex items-center gap-4 rounded-xl bg-[#f1f5f9] p-5 ring-1 ring-[#e2e8f0]">
                        <div class="flex h-10 w-10 flex-none items-center justify-center rounded-lg bg-[#dbeafe] text-[#1d4ed8]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div>
                            <p class="text-[14px] font-bold text-[#0f172a]">Sertifikasi Pendidik</p>
                            <p class="mt-0.5 text-[11px] text-[#64748b]">Sertifikat No. 2209/CERT/2018 - Aktif</p>
                        </div>
                        <svg class="ml-auto h-4 w-4 flex-none text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <div class="flex items-center gap-4 rounded-xl bg-[#f1f5f9] p-5 ring-1 ring-[#e2e8f0]">
                        <div class="flex h-10 w-10 flex-none items-center justify-center rounded-lg bg-[#dbeafe] text-[#1d4ed8]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div>
                            <p class="text-[14px] font-bold text-[#0f172a]">Verifikasi Data Pokok</p>
                            <p class="mt-0.5 text-[11px] text-[#64748b]">Sinkronisasi Terakhir: 12 Jan 2024</p>
                        </div>
                        <svg class="ml-auto h-4 w-4 flex-none text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="border-t border-[#e2e8f0] pt-6 text-center">
            <p class="text-[11px] font-semibold uppercase tracking-[0.15em] text-[#94a3b8]">SIRAPI Â© 2024 â€¢ Sistem Rapor Pintar</p>
        </div>
    </div>
@endsection

