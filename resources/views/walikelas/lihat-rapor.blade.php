<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Lihat Rapor - SIRAPI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Print Styles */
        @media print {
            body { background: white !important; }
            .no-print { display: none !important; }
            .rapor-page {
                box-shadow: none !important;
                margin: 0 !important;
                border-radius: 0 !important;
                max-width: 100% !important;
            }
            .print-container {
                padding: 0 !important;
                min-height: auto !important;
            }
        }

        /* Table borders for rapor */
        .rapor-table th,
        .rapor-table td {
            border: 1.5px solid #1e293b;
            padding: 8px 12px;
            text-align: left;
            font-size: 12px;
            line-height: 1.5;
            vertical-align: top;
        }
        .rapor-table th {
            background: #f8fafc;
            font-weight: 700;
            text-align: center;
            font-size: 11px;
        }
        .rapor-table {
            border-collapse: collapse;
            width: 100%;
        }

        .sikap-box {
            border: 1.5px solid #1e293b;
            padding: 12px 14px;
        }

        .kehadiran-table th,
        .kehadiran-table td {
            border: 1.5px solid #1e293b;
            padding: 6px 12px;
            font-size: 12px;
        }
        .kehadiran-table {
            border-collapse: collapse;
        }
        .kehadiran-table th {
            background: #f8fafc;
            font-weight: 600;
        }

        .catatan-box {
            border: 1.5px solid #1e293b;
            padding: 16px 20px;
        }
    </style>
</head>

<body class="min-h-screen bg-[#e2e8f0]">

    {{-- ═══ TOP BAR ═══ --}}
    <header class="no-print sticky top-0 z-50 flex h-[56px] items-center justify-between border-b border-[#e2e8f0] bg-white px-4 shadow-sm sm:px-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('walikelas.rapor') }}" class="flex items-center gap-2 text-[13px] font-semibold text-[#475569] transition hover:text-[#0f172a]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Kembali
            </a>
            <div class="h-6 w-px bg-[#e2e8f0]"></div>
            <h1 class="text-[16px] font-black tracking-[-0.04em] text-[#0f172a]">SIRAPI</h1>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="flex items-center gap-2 rounded-lg border border-[#e2e8f0] bg-white px-4 py-2 text-[12px] font-bold text-[#475569] transition hover:bg-[#f1f5f9]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Unduh PDF
            </button>
            <button onclick="window.print()" class="flex items-center gap-2 rounded-lg bg-[#0f172a] px-4 py-2 text-[12px] font-bold text-white transition hover:bg-[#1e293b]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Cetak
            </button>
            <a href="{{ route('walikelas.profil') }}" class="ml-1 flex h-9 w-9 items-center justify-center rounded-lg bg-[#1e40af] text-[11px] font-bold text-white">
                @php
                    $user = auth()->user();
                    $initials = collect(explode(' ', trim($user->name ?? 'SIRAPI')))
                        ->filter()->take(2)
                        ->map(fn ($p) => strtoupper(substr($p, 0, 1)))
                        ->implode('');
                @endphp
                {{ $initials }}
            </a>
            <a href="{{ route('walikelas.profil') }}" class="flex h-9 w-9 items-center justify-center rounded-lg border border-[#e2e8f0] text-[#475569] transition hover:bg-[#f1f5f9]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke-width="2"/></svg>
            </a>
        </div>
    </header>

    {{-- ═══ RAPOR CONTENT ═══ --}}
    <div class="print-container mx-auto max-w-[800px] px-4 py-8 sm:px-6">
        <div class="rapor-page rounded-lg bg-white p-8 shadow-xl sm:p-12 md:p-16">

            {{-- ──── TITLE ──── --}}
            <div class="text-center">
                <h2 class="text-[20px] font-black uppercase tracking-[0.02em] text-[#0f172a]">Laporan Hasil Belajar</h2>
                <p class="text-[14px] font-bold uppercase text-[#0f172a]">(Rapor)</p>
            </div>

            {{-- ──── HEADER INFO ──── --}}
            <div class="mt-8 grid grid-cols-2 gap-x-8 gap-y-2 text-[12px]">
                <div class="flex gap-2">
                    <span class="w-[100px] shrink-0 text-[#475569]">Nama Sekolah</span>
                    <span class="font-semibold text-[#0f172a]">: SD 01 Indonesia</span>
                </div>
                <div class="flex gap-2">
                    <span class="w-[80px] shrink-0 text-[#475569]">Kelas</span>
                    <span class="font-semibold text-[#0f172a]">: IV (Empat)</span>
                </div>
                <div class="flex gap-2">
                    <span class="w-[100px] shrink-0 text-[#475569]">Alamat</span>
                    <span class="font-semibold text-[#0f172a]">: Jl. Imajinasi No. 42, Kota Digital</span>
                </div>
                <div class="flex gap-2">
                    <span class="w-[80px] shrink-0 text-[#475569]">Fase</span>
                    <span class="font-semibold text-[#0f172a]">: B</span>
                </div>
                <div class="flex gap-2">
                    <span class="w-[100px] shrink-0 text-[#475569]">Nama Siswa</span>
                    <span class="font-bold uppercase text-[#0f172a]">: {{ request('name', 'BUDI SANTOSO') }}</span>
                </div>
                <div class="flex gap-2">
                    <span class="w-[80px] shrink-0 text-[#475569]">Semester</span>
                    <span class="font-semibold text-[#0f172a]">: 1 (Ganjil)</span>
                </div>
                <div class="flex gap-2">
                    <span class="w-[100px] shrink-0 text-[#475569]">NISN</span>
                    <span class="font-semibold text-[#0f172a]">: {{ request('nis', '00123456789') }}</span>
                </div>
                <div class="flex gap-2">
                    <span class="w-[80px] shrink-0 text-[#475569]">Tahun Ajaran</span>
                    <span class="font-semibold text-[#0f172a]">: 2026/2027</span>
                </div>
            </div>

            {{-- ──── A. PENGETAHUAN & KETERAMPILAN ──── --}}
            <div class="mt-10">
                <h3 class="text-[13px] font-black uppercase text-[#0f172a]">A. Pengetahuan & Keterampilan</h3>
                <div class="mt-3 overflow-x-auto">
                    @php
                        $mapel = [
                            ['no' => 1, 'nama' => 'Pendidikan Agama', 'nilai' => 88, 'capaian' => 'Menunjukkan penguasaan yang sangat baik dalam memahami makna rukun iman dan rukun islam.'],
                            ['no' => 2, 'nama' => 'Pancasila', 'nilai' => 90, 'capaian' => 'Sangat baik dalam mengidentifikasi simbol-simbol sila Pancasila dalam lambang negara.'],
                            ['no' => 3, 'nama' => 'Bahasa Indonesia', 'nilai' => 85, 'capaian' => 'Baik dalam memahami instruksi lisan dan tulisan serta mampu menceritakan kembali isi teks.'],
                            ['no' => 4, 'nama' => 'Matematika', 'nilai' => 78, 'capaian' => 'Mampu melakukan operasi hitung perkalian dan pembagian, perlu bimbingan dalam materi pecahan.'],
                            ['no' => 5, 'nama' => 'IPAS', 'nilai' => 82, 'capaian' => 'Menunjukkan pemahaman yang baik tentang siklus hidup makhluk hidup di lingkungan sekitar.'],
                            ['no' => 6, 'nama' => 'PJOK', 'nilai' => 92, 'capaian' => 'Sangat terampil dalam mempraktikkan variasi gerak dasar lokomotor dan non lokomotor.'],
                            ['no' => 7, 'nama' => 'Seni Budaya', 'nilai' => 86, 'capaian' => 'Kreatif dalam membuat karya seni rupa dari bahan alam di lingkungan sekolah.'],
                        ];
                        $totalNilai = array_sum(array_column($mapel, 'nilai'));
                        $rataRata = round($totalNilai / count($mapel), 2);
                    @endphp
                    <table class="rapor-table">
                        <thead>
                            <tr>
                                <th class="w-[40px]">No</th>
                                <th class="w-[140px]">Mata Pelajaran</th>
                                <th class="w-[55px]">Nilai</th>
                                <th>Capaian Kompetensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mapel as $m)
                                <tr>
                                    <td class="text-center font-semibold">{{ $m['no'] }}</td>
                                    <td class="font-semibold">{{ $m['nama'] }}</td>
                                    <td class="text-center font-black text-[14px]">{{ $m['nilai'] }}</td>
                                    <td class="text-[11px] leading-[1.6] text-[#334155]">{{ $m['capaian'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-right font-bold">Nilai Rata-rata</td>
                                <td class="text-center font-black text-[14px]">{{ number_format($rataRata, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Peringkat & Jumlah Siswa --}}
                <div class="mt-2 flex items-center gap-6 rounded bg-[#f8fafc] px-4 py-2.5 text-[12px] ring-1 ring-[#1e293b]">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-[#475569]">Peringkat:</span>
                        <span class="font-black text-[#0f172a]">5</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-[#475569]">Jumlah Siswa:</span>
                        <span class="font-black text-[#0f172a]">32</span>
                    </div>
                </div>
            </div>

            {{-- ──── B. SIKAP & C. KEHADIRAN ──── --}}
            <div class="mt-10 grid gap-6 sm:grid-cols-2">
                {{-- B. Sikap --}}
                <div>
                    <h3 class="text-[13px] font-black uppercase text-[#0f172a]">B. Sikap</h3>
                    <div class="mt-3 space-y-3">
                        {{-- Sikap Spiritual --}}
                        <div class="sikap-box rounded">
                            <div class="flex items-center justify-between">
                                <p class="text-[11px] font-bold text-[#475569]">1. SIKAP SPIRITUAL</p>
                                <p class="text-[11px] font-black text-[#0f172a]">A (SANGAT BAIK)</p>
                            </div>
                            <p class="mt-2 text-[11px] italic leading-[1.7] text-[#334155]">"Ananda Budi sangat baik dalam ketaatan beribadah, berperilaku syukur, dan selalu berdoa."</p>
                        </div>

                        {{-- Sikap Sosial --}}
                        <div class="sikap-box rounded">
                            <div class="flex items-center justify-between">
                                <p class="text-[11px] font-bold text-[#475569]">2. SIKAP SOSIAL</p>
                                <p class="text-[11px] font-black text-[#0f172a]">A (SANGAT BAIK)</p>
                            </div>
                            <p class="mt-2 text-[11px] italic leading-[1.7] text-[#334155]">"Menunjukkan sikap jujur, disiplin, dan tanggung jawab yang sangat baik dalam berinteraksi."</p>
                        </div>
                    </div>
                </div>

                {{-- C. Kehadiran --}}
                <div>
                    <h3 class="text-[13px] font-black uppercase text-[#0f172a]">C. Kehadiran</h3>
                    <div class="mt-3">
                        <table class="kehadiran-table w-full">
                            <tbody>
                                <tr>
                                    <td class="font-semibold text-[#475569]">Sakit</td>
                                    <td class="text-center font-black text-[#0f172a] w-[80px]">1 Hari</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold text-[#475569]">Izin</td>
                                    <td class="text-center font-black text-[#0f172a]">0 Hari</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold text-[#475569]">Tanpa Keterangan</td>
                                    <td class="text-center font-black text-[#0f172a]">0 Hari</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ──── D. CATATAN WALI KELAS ──── --}}
            <div class="mt-10">
                <h3 class="text-[13px] font-black uppercase text-[#0f172a]">D. Catatan Wali Kelas</h3>
                <div class="catatan-box mt-3 rounded">
                    <p class="text-[12px] italic leading-[1.8] text-[#334155]">"Selamat Budi! Pertahankan semangat belajarmu, terutama pada mata pelajaran Matematika. Sikap kemandirianmu di kelas patut dicontoh oleh teman-teman lainnya."</p>
                </div>
            </div>

            {{-- ──── TANDA TANGAN ──── --}}
            <div class="mt-14">
                <div class="grid grid-cols-2 gap-8 text-center text-[12px]">
                    {{-- Orang Tua / Wali --}}
                    <div>
                        <p class="text-[#475569]">Mengetahui</p>
                        <p class="font-semibold text-[#475569]">Orang Tua/Wali,</p>
                        <div class="mt-16 border-b border-[#1e293b] mx-auto w-[160px]"></div>
                    </div>

                    {{-- Wali Kelas --}}
                    <div>
                        <p class="text-[#475569]">Kota Digital, 22 Desember 2023</p>
                        <p class="font-semibold text-[#475569]">Wali Kelas,</p>
                        <div class="mt-12">
                            <p class="text-[14px] font-bold text-[#0f172a] underline underline-offset-4">Budi Santoso, S.Pd.</p>
                            <p class="mt-0.5 text-[10px] text-[#64748b]">NIP. 198501012010012001</p>
                        </div>
                    </div>
                </div>

                {{-- Kepala Sekolah --}}
                <div class="mt-10 text-center text-[12px]">
                    <p class="text-[#475569]">Mengetahui,</p>
                    <p class="font-semibold text-[#475569]">Kepala Sekolah,</p>
                    <div class="mt-12">
                        <p class="text-[14px] font-bold text-[#0f172a] underline underline-offset-4">Dr. Ahmad Hidayat, M.Pd.</p>
                        <p class="mt-0.5 text-[10px] text-[#64748b]">NIP. 197003121995031002</p>
                    </div>
                </div>
            </div>

            {{-- ──── FOOTER ──── --}}
            <div class="mt-12 border-t border-[#e2e8f0] pt-4 text-center">
                <p class="text-[10px] text-[#94a3b8]">Halaman 1 dari 1 — SIRAPI (Sistem Rapor Digital)</p>
            </div>

        </div>
    </div>

</body>
</html>
