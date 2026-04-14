<x-admin-shell
    :user="auth()->user()"
    active="aturan-nilai"
    title="Aturan Penilaian"
    subtitle="Konfigurasi bobot dan aturan nilai"
>
    <div class="space-y-6">

        {{-- ─── TOP: KONFIGURASI + PREVIEW ──────────────────── --}}
        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">

            {{-- Konfigurasi Bobot Nilai --}}
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <h2 class="text-[22px] font-black tracking-[-0.04em] text-[#0f172a]">Konfigurasi Bobot Nilai</h2>
                <p class="mt-2 max-w-[480px] text-[14px] leading-[1.8] text-[#475569]">
                    Tentukan persentase untuk setiap komponen nilai akademik. Pastikan total akumulasi tepat mencapai 100% untuk validasi sistem.
                </p>

                {{-- Header --}}
                <div class="mt-6 grid grid-cols-[1fr_120px_48px] gap-3 px-1">
                    <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Komponen Nilai</p>
                    <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Bobot (%)</p>
                    <p></p>
                </div>

                {{-- Rows --}}
                <div class="mt-3 space-y-3">
                    @foreach ([
                        ['name' => 'Tugas & Kuis', 'value' => 20],
                        ['name' => 'Ujian Tengah Semester (UTS)', 'value' => 30],
                        ['name' => 'Ujian Akhir Semester (UAS)', 'value' => 40],
                        ['name' => 'Kehadiran', 'value' => 10],
                    ] as $komponen)
                        <div class="grid grid-cols-[1fr_120px_48px] items-center gap-3">
                            <input class="h-[44px] rounded-[8px] border border-[#e2e8f0] bg-[#f8fafc] px-4 text-[14px] font-medium text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="{{ $komponen['name'] }}" type="text">
                            <input class="h-[44px] rounded-[8px] border border-[#e2e8f0] bg-white px-4 text-center text-[18px] font-black text-[#0f172a] outline-none focus:border-[#3b82f6] focus:ring-2 focus:ring-[#3b82f6]/20" value="{{ $komponen['value'] }}" type="number">
                            <button class="flex h-[44px] w-[44px] items-center justify-center rounded-[8px] border border-[#e2e8f0] text-[#94a3b8] transition hover:bg-[#fef2f2] hover:text-[#dc2626] hover:border-[#fecaca]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            </button>
                        </div>
                    @endforeach
                </div>

                {{-- Add component --}}
                <button class="mt-4 flex items-center gap-2 text-[13px] font-bold text-[#64748b] transition hover:text-[#1d4ed8]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"></circle><path d="M12 8v8m-4-4h8" stroke-width="2" stroke-linecap="round"></path></svg>
                    Tambah Komponen Baru
                </button>

                {{-- Validation + Save --}}
                <div class="mt-6 flex items-center justify-between border-t border-[#f1f5f9] pt-5">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-[#059669]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        <span class="text-[13px] font-bold text-[#059669]">Total Akumulasi: 100% (Valid)</span>
                    </div>
                    <button class="flex h-[42px] items-center gap-2 rounded-[8px] bg-[#0f172a] px-6 text-[13px] font-bold text-white transition hover:bg-[#1e293b]">Simpan Konfigurasi</button>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="space-y-4">
                {{-- Preview Kalkulasi --}}
                <div class="rounded-[14px] bg-[#0f172a] p-6 text-white">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#60a5fa]">Preview Kalkulasi</p>
                    <h3 class="mt-2 text-[20px] font-black tracking-[-0.04em]">Simulasi Nilai Akhir</h3>
                    <div class="mt-5 space-y-2.5">
                        @foreach ([
                            ['label' => 'Tugas (20%)', 'calc' => '65 × 0.2 = 17'],
                            ['label' => 'UTS (30%)', 'calc' => '78 × 0.3 = 23.4'],
                            ['label' => 'UAS (40%)', 'calc' => '80 × 0.4 = 32'],
                            ['label' => 'Hadir (10%)', 'calc' => '100 × 0.1 = 10'],
                        ] as $row)
                            <div class="flex items-center justify-between text-[13px]">
                                <span class="text-white/60">{{ $row['label'] }}</span>
                                <span class="font-mono font-bold">{{ $row['calc'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-5 border-t border-white/20 pt-4 text-center">
                        <p class="text-[12px] font-bold uppercase tracking-[0.12em] text-[#60a5fa]">Predikat B+</p>
                        <p class="mt-1 text-[48px] font-black leading-none tracking-[-0.06em]">82.4</p>
                    </div>
                </div>

                {{-- Log Perubahan --}}
                <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-5">
                    <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[#64748b]">Log Perubahan Terakhir</p>
                    <div class="mt-4 space-y-4">
                        <div class="rounded-[8px] bg-[#f8fafc] p-3">
                            <p class="text-[12px] font-bold text-[#0f172a]">Bobot UAS diubah</p>
                            <p class="mt-0.5 text-[11px] text-[#64748b]">Oleh: Admin Utama • 2 Jam lalu</p>
                            <p class="mt-0.5 text-[11px] text-[#64748b]">35% → 40%</p>
                        </div>
                        <div class="rounded-[8px] bg-[#f8fafc] p-3">
                            <p class="text-[12px] font-bold text-[#0f172a]">Komponen Kehadiran ditambahkan</p>
                            <p class="mt-0.5 text-[11px] text-[#64748b]">Oleh: Wakasek Kurikulum • Kemarin</p>
                        </div>
                    </div>
                    <button class="mt-4 flex h-[36px] w-full items-center justify-center rounded-[6px] border border-[#e2e8f0] bg-white text-[11px] font-bold uppercase tracking-[0.08em] text-[#475569] transition hover:bg-[#f1f5f9]">Lihat Riwayat Lengkap</button>
                </div>

                {{-- Blueprint placeholder --}}
                <div class="flex h-[120px] items-center justify-center rounded-[14px] bg-[#f1f5f9] border border-[#e2e8f0]">
                    <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-[#94a3b8]">BLUEPRINT FR-13-B</p>
                </div>
            </div>
        </div>

        {{-- ─── BOTTOM: KKM + PEMBULATAN ────────────────────── --}}
        <div class="grid gap-4 sm:grid-cols-2">
            {{-- KKM --}}
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <h3 class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Nilai Ambang Batas (KKM)</h3>
                <div class="mt-3 flex items-end gap-2">
                    <span class="text-[48px] font-black leading-none tracking-[-0.06em] text-[#0f172a]">75</span>
                    <span class="pb-1 text-[14px] font-medium text-[#64748b]">Skala 100</span>
                </div>
                <p class="mt-4 text-[13px] leading-[1.8] text-[#475569]">
                    Siswa dengan nilai akhir di bawah ambang batas akan secara otomatis masuk ke daftar program remedi.
                </p>
            </div>

            {{-- Pembulatan --}}
            <div class="rounded-[14px] border border-[#e2e8f0] bg-white p-6">
                <h3 class="text-[11px] font-bold uppercase tracking-[0.18em] text-[#64748b]">Pembulatan Nilai</h3>
                <div class="mt-4 space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="pembulatan" checked class="h-4 w-4 accent-[#0f172a]">
                        <span class="text-[14px] font-medium text-[#0f172a]">Terdekat (0.5)</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="pembulatan" class="h-4 w-4 accent-[#0f172a]">
                        <span class="text-[14px] font-medium text-[#64748b]">Ke Atas (Ceiling)</span>
                    </label>
                </div>
            </div>
        </div>

    </div>
</x-admin-shell>
