<x-admin-shell
    :user="auth()->user()"
    active="data-sekolah"
    title="Identitas Sekolah"
    subtitle="Profil Lembaga"
>
    <div class="space-y-6">

        {{-- ─── HEADING ─────────────────────────────────────── --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Profil Lembaga</p>
                <h1 class="mt-1 text-[32px] font-black tracking-[-0.04em] text-[#0f172a]">Identitas Sekolah</h1>
                <p class="mt-2 max-w-[480px] text-[14px] leading-[1.8] text-[#475569]">
                    Perbarui informasi dasar sekolah, status akreditasi, dan detail kepala sekolah untuk keperluan sinkronisasi dapodik dan administrasi internal.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button class="flex h-[42px] items-center gap-2 rounded-[8px] border border-[#e2e8f0] bg-white px-5 text-[13px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">Batalkan Perubahan</button>
                <button class="flex h-[42px] items-center gap-2 rounded-[8px] bg-[#1d4ed8] px-5 text-[13px] font-bold text-white transition hover:bg-[#1e40af]">Simpan Identitas</button>
            </div>
        </div>

        {{-- ─── LOGO + AKREDITASI + INFORMASI UTAMA ─────────── --}}
        <div class="grid gap-6 lg:grid-cols-[260px_minmax(0,1fr)]">

            {{-- Left: Logo & Akreditasi --}}
            <div class="space-y-4">
                {{-- Logo --}}
                <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6 text-center">
                    <div class="mx-auto flex h-[120px] w-[120px] items-center justify-center rounded-[12px] bg-[#f1f5f9]">
                        <svg class="h-10 w-10 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </div>
                    <p class="mt-3 text-[13px] font-bold text-[#0f172a]">Logo Sekolah</p>
                    <p class="mt-0.5 text-[11px] text-[#94a3b8]">Format PNG/JPG, Maks 2MB</p>
                    <button class="mt-3 flex h-[36px] w-full items-center justify-center rounded-[6px] border border-[#e2e8f0] bg-white text-[12px] font-bold uppercase tracking-[0.08em] text-[#475569] transition hover:bg-[#f1f5f9]">Upload File</button>
                </div>

                {{-- Akreditasi --}}
                <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Status Akreditasi</p>
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <div class="rounded-[8px] border-2 border-[#0f172a] p-3 text-center">
                            <span class="text-[28px] font-black text-[#0f172a]">A</span>
                            <p class="mt-1 text-[9px] font-bold uppercase text-[#64748b]">Sangat Memuaskan</p>
                        </div>
                        <div class="rounded-[8px] border border-[#e2e8f0] p-3 text-center">
                            <span class="text-[28px] font-black text-[#94a3b8]">B</span>
                            <p class="mt-1 text-[9px] font-bold uppercase text-[#94a3b8]">Memuaskan</p>
                        </div>
                    </div>
                    <div class="mt-4 border-t border-[#f1f5f9] pt-3">
                        <p class="text-[10px] font-bold uppercase tracking-[0.08em] text-[#64748b]">No. Sertifikat</p>
                        <p class="mt-1 text-[14px] font-bold text-[#0f172a]">123/BAN-SM/SK/2022</p>
                    </div>
                </div>
            </div>

            {{-- Right: Info Utama --}}
            <div class="space-y-6">
                {{-- Informasi Utama --}}
                <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                    <h3 class="flex items-center gap-2 text-[16px] font-bold text-[#0f172a]">
                        <span class="text-[#1d4ed8]">|</span> Informasi Utama
                    </h3>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Sekolah</label>
                            <input class="mt-1 flex h-[42px] w-full items-center rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="SMK Teknologi Unggul Jakarta" type="text">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NPSN</label>
                            <input class="mt-1 flex h-[42px] w-full items-center rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="20304857" type="text">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Bentuk Pendidikan</label>
                            <input class="mt-1 flex h-[42px] w-full items-center rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none" value="SMK (Sekolah Menengah Kejuruan)" type="text" readonly>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Status Sekolah</label>
                            <div class="mt-2 flex items-center gap-4">
                                <label class="flex items-center gap-2 text-[14px] font-medium text-[#0f172a] cursor-pointer">
                                    <input type="radio" name="status" checked class="h-4 w-4 accent-[#0f172a]"> Swasta
                                </label>
                                <label class="flex items-center gap-2 text-[14px] font-medium text-[#64748b] cursor-pointer">
                                    <input type="radio" name="status" class="h-4 w-4 accent-[#0f172a]"> Negeri
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kepala Sekolah --}}
                <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                    <h3 class="flex items-center gap-2 text-[16px] font-bold text-[#0f172a]">
                        <span class="text-[#1d4ed8]">|</span> Data Kepala Sekolah
                    </h3>
                    <div class="mt-5 grid gap-4 sm:grid-cols-[120px_1fr_1fr]">
                        <div class="flex flex-col items-center gap-2">
                            <div class="flex h-[80px] w-[80px] items-center justify-center rounded-full bg-[#f1f5f9]">
                                <svg class="h-8 w-8 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            </div>
                            <p class="text-[10px] font-bold text-[#1d4ed8] cursor-pointer hover:underline">Ganti Foto</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nama Lengkap & Gelar</label>
                            <input class="mt-1 flex h-[42px] w-full items-center rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="Dr. Budi Santoso, M.Pd." type="text">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">NIP/NIY</label>
                            <input class="mt-1 flex h-[42px] w-full items-center rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="197508212003121002" type="text">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── ALAMAT & KONTAK ─────────────────────────────── --}}
        <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
            <h3 class="flex items-center gap-2 text-[16px] font-bold text-[#0f172a]">
                <span class="text-[#1d4ed8]">|</span> Alamat & Kontak
            </h3>
            <div class="mt-5 grid gap-4 sm:grid-cols-[1fr_200px]">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Alamat Lengkap</label>
                    <input class="mt-1 flex h-[42px] w-full items-center rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="Jl. Teknologi Cerdas No. 45, Kebayoran Baru, Jakarta Selatan, DKI Jakarta" type="text">
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Kode Pos</label>
                    <input class="mt-1 flex h-[42px] w-full items-center rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="12110" type="text">
                </div>
            </div>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Email Sekolah</label>
                    <div class="relative mt-1">
                        <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        <input class="flex h-[42px] w-full items-center rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] pl-10 pr-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="info@smk-tu.sch.id" type="email">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Nomor Telepon</label>
                    <div class="relative mt-1">
                        <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        <input class="flex h-[42px] w-full items-center rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] pl-10 pr-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="(021) 7654321" type="tel">
                    </div>
                </div>
            </div>

            {{-- Map Placeholder --}}
            <div class="mt-5 flex h-[200px] items-center justify-center rounded-[10px] bg-[#f1f5f9] border border-[#e2e8f0]">
                <div class="text-center">
                    <svg class="mx-auto h-8 w-8 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    <p class="mt-2 text-[12px] font-semibold text-[#94a3b8]">Lat: -6.2146 | Long: 106.8451</p>
                </div>
            </div>
        </div>

        {{-- ─── FOOTER ──────────────────────────────────────── --}}
        <p class="text-center text-[11px] font-semibold tracking-[0.1em] text-[#94a3b8]">
            &copy; 2024 SIRAPI ADMIN TU - ACADEMIC GOVERNANCE SYSTEM
        </p>

    </div>
</x-admin-shell>
