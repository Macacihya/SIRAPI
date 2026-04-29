<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Rapor - {{ request('name', 'Siswa') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .header-font { font-family: 'Poppins', sans-serif; }

        /* Print Styles */
        @media print {
            @page { 
                size: A4; 
                margin: 0; 
            }
            body { 
                background: white !important; 
                margin: 0 !important;
                padding: 0 !important;
            }
            .no-print { display: none !important; }
            .rapor-page {
                box-shadow: none !important;
                margin: 0 !important;
                border-radius: 0 !important;
                width: 210mm !important;
                min-height: 297mm !important;
                padding: 15mm 20mm !important;
                page-break-after: always;
                display: flex;
                flex-direction: column;
            }
            .print-container {
                padding: 0 !important;
                margin: 0 !important;
                width: 210mm !important;
                max-width: none !important;
            }
            .page-break {
                clear: both;
                break-before: page;
            }
        }

        /* Screen only adjustments for A4 preview */
        @media screen {
            .rapor-page {
                width: 210mm;
                min-height: 297mm;
                margin-left: auto;
                margin-right: auto;
                display: flex;
                flex-direction: column;
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

        /* Identity Alignment Helpers */
        .id-row { display: flex; gap: 8px; }
        .id-label { width: 100px; flex-shrink: 0; color: #475569; }
        .id-val { font-weight: 600; color: #0f172a; }
        .id-val-main { font-weight: 800; text-transform: uppercase; }
    </style>
</head>

@php
    // 1. Ambil data dari request atau dummy
    $studentId = request('id', 1);
    $studentName = strtoupper(request('name', 'ACHMAD ALBAR'));
    $studentFirstName = explode(' ', trim($studentName))[0];
    $studentNis = request('nis', '12001');
    $studentNisn = request('nisn', '0012345601');

    // 2. Data Nilai Dinamis
    $mapel = [
        ['no' => 1, 'nama' => 'Pendidikan Agama', 'nilai' => 85, 'capaian' => 'Sangat baik dalam memahami kisah keteladanan Nabi Muhammad SAW.'],
        ['no' => 2, 'nama' => 'Pendidikan Pancasila', 'nilai' => 87, 'capaian' => 'Sangat baik dalam mengidentifikasi simbol-sila Pancasila.'],
        ['no' => 3, 'nama' => 'Bahasa Indonesia', 'nilai' => 84, 'capaian' => 'Mampu menceritakan kembali isi teks dengan runtut dan jelas.'],
        ['no' => 4, 'nama' => 'Matematika', 'nilai' => 80, 'capaian' => 'Sangat mahir dalam operasi perkalian, perlu bimbingan pada pembagian.'],
        ['no' => 5, 'nama' => 'IPAS', 'nilai' => 82, 'capaian' => 'Menunjukkan rasa ingin tahu yang tinggi terhadap siklus hidup makhluk hidup.'],
        ['no' => 6, 'nama' => 'PJOK', 'nilai'  => 88, 'capaian' => 'Memiliki koordinasi gerak yang sangat baik saat bermain bola.'],
        ['no' => 7, 'nama' => 'Seni Budaya', 'nilai' => 86, 'capaian' => 'Sangat kreatif dalam eksplorasi warna pada karya seni rupa.'],
    ];

    // 3. Data Tambahan (Eskul)
    $eskul = [
        ['nama' => 'Pramuka', 'ket' => 'Sangat aktif dalam kegiatan perkemahan dan memiliki kedisiplinan yang tinggi.'],
        ['nama' => 'Seni Tari', 'ket' => 'Mampu memperagakan gerak dasar tari daerah dengan cukup luwes.'],
        ['nama' => 'Karate', 'ket' => 'Memiliki ketangkasan gerak yang baik dan disiplin dalam berlatih.'],
    ];

    $rataRata = round(array_sum(array_column($mapel, 'nilai')) / count($mapel), 2);
@endphp

<body class="min-h-screen bg-[#e2e8f0]">

    {{-- ═══ TOP BAR ═══ --}}
    <header class="no-print sticky top-0 z-50 flex h-[56px] items-center justify-between border-b border-[#e2e8f0] bg-white px-4 shadow-sm sm:px-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('rapor') }}" class="flex items-center gap-2 text-[13px] font-semibold text-[#475569] transition hover:text-[#0f172a]">
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
            <a href="{{ route('profil') }}" class="ml-1 flex h-9 w-9 items-center justify-center rounded-lg bg-[#1e40af] text-[11px] font-bold text-white">
                @php
                    $user = auth()->user();
                    $initials = collect(explode(' ', trim($user->nama ?? 'SIRAPI')))
                        ->filter()->take(2)
                        ->map(fn ($p) => strtoupper(substr($p, 0, 1)))
                        ->implode('');
                @endphp
                {{ $initials }}
            </a>
            <a href="{{ route('profil') }}" class="flex h-9 w-9 items-center justify-center rounded-lg border border-[#e2e8f0] text-[#475569] transition hover:bg-[#f1f5f9]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke-width="2"/></svg>
            </a>
        </div>
    </header>

    {{-- ═══ RAPOR CONTENT ═══ --}}
    <div class="print-container mx-auto py-8">
        
        {{-- ──── HALAMAN 1 ──── --}}
        <div class="rapor-page mb-8 rounded-lg bg-white p-8 shadow-xl sm:p-12 md:p-16">

            {{-- ──── KOP SURAT (OFFICIAL SCHOOL ADMINISTRATION STANDARD) ──── --}}
            <div class="header-font flex items-center justify-between border-b-[3px] border-double border-[#000] pb-3 mb-6">
                <!-- Logo Kiri: Logo Daerah (Tut Wuri / Pemko) -->
                <div class="flex-shrink-0 flex items-center">
                    <img src="{{ asset('images/tutwuri.png') }}" alt="Logo Tut Wuri" class="h-[85px] w-auto">
                </div>

                <!-- Teks Tengah: Hirarki Administrasi -->
                <div class="flex-1 text-center px-4">
                    <h3 class="text-[15px] font-bold uppercase leading-tight">Pemerintah Kota Batam</h3>
                    <h3 class="text-[15px] font-bold uppercase leading-tight">Dinas Pendidikan</h3>
                    <h2 class="text-[20px] font-black uppercase tracking-wide mt-1">SD NEGERI 01 INDONESIA</h2>
                    <div class="mt-2 text-[10px] leading-tight font-medium">
                        <p>Alamat: Jl. Imajinasi No. 42, Kel. Digital, Kec. Batam Kota, Batam, 29461</p>
                        <p>Telepon: (0778) 469856, Faks: (0778) 463620</p>
                        <p>Laman: www.sdn01indonesia.sch.id, Surel: info@sdn01indonesia.sch.id</p>
                    </div>
                </div>

                <!-- Logo Kanan: Logo Sekolah / Sertifikasi -->
                <div class="flex-shrink-0 flex items-center">
                    <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo Sekolah" class="h-[85px] w-auto">
                </div>
            </div>

            {{-- ──── JUDUL DOKUMEN ──── --}}
            <div class="text-center mb-8">
                <h2 class="text-[18px] font-black uppercase tracking-[0.05em] text-[#0f172a]">Laporan Hasil Belajar (Rapor)</h2>
            </div>

            {{-- ──── IDENTITAS SISWA (DATA DINAMIS) ──── --}}
            <div class="grid grid-cols-2 gap-x-12 text-[12px]">
                <div class="space-y-1">
                    <div class="id-row">
                        <span class="id-label">Nama Sekolah</span>
                        <span class="id-val">: SD NEGERI 01 INDONESIA</span>
                    </div>
                    <div class="id-row">
                        <span class="id-label">Alamat</span>
                        <span class="id-val">: Jl. Imajinasi No. 42, Batam</span>
                    </div>
                    <div class="id-row">
                        <span class="id-label">Nama Siswa</span>
                        <span class="id-val">: {{ $studentName }}</span>
                    </div>
                    <div class="id-row">
                        <span class="id-label">NIS / NISN</span>
                        <span class="id-val">: {{ $studentNis }} / {{ $studentNisn }}</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <div class="id-row">
                        <span class="id-label">Kelas</span>
                        <span class="id-val">: IV / Empat</span>
                    </div>
                    <div class="id-row">
                        <span class="id-label">Fase</span>
                        <span class="id-val">: Fase B</span>
                    </div>
                    <div class="id-row">
                        <span class="id-label">Semester</span>
                        <span class="id-val">: 1 (Ganjil)</span>
                    </div>
                    <div class="id-row">
                        <span class="id-label">Tahun Ajaran</span>
                        <span class="id-val">: 2025/2026</span>
                    </div>
                </div>
            </div>

            {{-- ──── A. PENGETAHUAN & KETERAMPILAN ──── --}}
            <div class="mt-10">
                <h3 class="text-[13px] font-black uppercase text-[#0f172a]">A. Pengetahuan & Keterampilan</h3>
                <div class="mt-3 overflow-x-auto">
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
                                    <td class="text-[11px] leading-[1.6] text-[#334155]">
                                        {{ $m['capaian'] }}
                                    </td>
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
                        <span class="font-black text-[#0f172a]">3</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-[#475569]">Jumlah Siswa:</span>
                        <span class="font-black text-[#0f172a]">32</span>
                    </div>
                </div>
            </div>

            {{-- ──── FOOTER HALAMAN 1 ──── --}}
            <div class="mt-auto border-t border-[#e2e8f0] pt-4 text-center">
                <p class="text-[10px] text-[#94a3b8]">Halaman 1 dari 2</p>
            </div>
        </div>

        {{-- ──── HALAMAN 2 ──── --}}
        <div class="rapor-page page-break rounded-lg bg-white p-8 shadow-xl sm:p-12 md:p-16">

            {{-- ──── B. EKSTRAKURIKULER ──── --}}
            <div class="mb-10">
                <h3 class="text-[13px] font-black uppercase text-[#0f172a]">B. Ekstrakurikuler</h3>
                <div class="mt-3">
                    <table class="rapor-table">
                        <thead>
                            <tr>
                                <th class="w-[40px]">No</th>
                                <th class="w-[200px]">Kegiatan Ekstrakurikuler</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($eskul as $index => $e)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="font-semibold">{{ $e['nama'] }}</td>
                                    <td class="text-[11px]">{{ $e['ket'] ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center italic text-[#64748b]">Tidak Mengikuti</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ──── C. SIKAP & D. KEHADIRAN ──── --}}
            <div class="grid gap-6 sm:grid-cols-2">
                {{-- C. Sikap --}}
                <div>
                    <h3 class="text-[13px] font-black uppercase text-[#0f172a]">C. Sikap</h3>
                    <div class="mt-3 space-y-3">
                        {{-- Sikap Spiritual --}}
                        <div class="sikap-box rounded">
                            <div class="flex items-center justify-between">
                                <p class="text-[11px] font-bold text-[#475569]">1. SIKAP SPIRITUAL</p>
                                <p class="text-[11px] font-black text-[#0f172a]">A (SANGAT BAIK)</p>
                            </div>
                            <p class="mt-2 text-[11px] italic leading-[1.7] text-[#334155]">"Ananda <strong>{{ $studentFirstName }}</strong> sangat baik dalam ketaatan beribadah, berperilaku syukur, dan selalu berdoa."</p>
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

                {{-- D. Kehadiran --}}
                <div>
                    <h3 class="text-[13px] font-black uppercase text-[#0f172a]">D. Kehadiran</h3>
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

            {{-- ──── E. CATATAN WALI KELAS ──── --}}
            <div class="mt-10">
                <h3 class="text-[13px] font-black uppercase text-[#0f172a]">E. Catatan Wali Kelas</h3>
                <div class="catatan-box mt-3 rounded">
                    <p class="text-[12px] italic leading-[1.8] text-[#334155]">"Selamat <strong>{{ $studentFirstName }}</strong>! Pertahankan semangat belajarmu, terutama pada mata pelajaran Matematika. Sikap kemandirianmu di kelas patut dicontoh oleh teman-teman lainnya."</p>
                </div>
            </div>

            {{-- ──── TANDA TANGAN (Triangular Layout) ──── --}}
            <div class="mt-6 pt-4">
                <div class="grid grid-cols-2 gap-8 text-center text-[11px]">
                    {{-- Baris 1: Kiri & Kanan --}}
                    <div>
                        <p class="text-[#475569]">Mengetahui</p>
                        <p class="font-bold text-[#475569]">Orang Tua/Wali,</p>
                        <div class="mt-12 border-b border-[#1e293b] mx-auto w-[160px]"></div>
                    </div>

                    <div>
                        <p class="text-[#475569]">Kota Digital, {{ now()->translatedFormat('d F Y') }}</p>
                        <p class="font-bold text-[#475569]">Wali Kelas,</p>
                        <div class="mt-10">
                            <p class="text-[13px] font-bold text-[#0f172a] underline underline-offset-4">{{ auth()->user()->nama ?? 'Heryanto Pratama, S.Pd.' }}</p>
                            <p class="mt-0.5 text-[9px] text-[#64748b]">NIP. 198501012010012001</p>
                        </div>
                    </div>
                </div>

                {{-- Baris 2: Tengah (Kepala Sekolah) --}}
                <div class="mt-8 text-center text-[11px]">
                    <p class="text-[#475569]">Mengetahui,</p>
                    <p class="font-bold text-[#475569]">Kepala Sekolah,</p>
                    <div class="mt-12">
                        <p class="text-[13px] font-bold text-[#0f172a] underline underline-offset-4">Dr. Ahmad Hidayat, M.Pd.</p>
                        <p class="mt-0.5 text-[9px] text-[#64748b]">NIP. 197003121995031002</p>
                    </div>
                </div>
            </div>

            {{-- ──── FOOTER HALAMAN 2 ──── --}}
            <div class="mt-auto border-t border-[#e2e8f0] pt-4 text-center">
                <p class="text-[10px] text-[#94a3b8]">Halaman 2 dari 2</p>
            </div>
        </div>
    </div>


</body>
</html>
