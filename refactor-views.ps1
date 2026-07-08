# ============================================================
# Script: refactor-views.ps1
# Deskripsi: Migrasi massal view lama ke struktur pages/ baru.
#
# Cara kerja:
#   1. Baca file dari folder role lama (admin/, walikelas/, guru/)
#   2. Hapus tag shell komponen lama (<x-*-shell>)
#   3. Tambahkan header @extends + @section baru
#   4. Simpan ke resources/views/pages/<role>/
#
# Jalankan dari root proyek:
#   cd c:\laragon\www\SIRAPI-2
#   .\refactor-views.ps1
# ============================================================

# ─── Fungsi utama refactor per role ──────────────────────────
function Invoke-RefactorRole {
    param(
        [string]   $Role,          # Nama folder role (admin, walikelas, guru)
        [string]   $Layout,        # Nama layout @extends (layouts.admin, dll)
        [string]   $ShellTag,      # Tag shell lama yang dihapus (x-admin-shell, dll)
        [string]   $TitleSuffix,   # Suffix judul di browser tab
        [object[]] $Files          # Array definisi halaman
    )

    $srcBase = "resources\views\$Role\"
    $dstBase = "resources\views\pages\$Role\"

    foreach ($f in $Files) {
        $src = $srcBase + $f.file + ".blade.php"
        $dst = $dstBase + $f.file + ".blade.php"

        if (-not (Test-Path $src)) {
            Write-Host "  SKIP (tidak ditemukan): $src" -ForegroundColor Yellow
            continue
        }

        $content = Get-Content $src -Raw

        # Hapus tag shell lama (opening & closing)
        $content = $content -replace "<$ShellTag[^>]*>", ''
        $content = $content -replace "</$ShellTag>", ''
        $content = $content.Trim()

        # Bangun header layout baru
        $header  = "{{-- Halaman: $($f.file) --- menggunakan layout $Role --}}`r`n"
        $header += "@extends('$Layout')`r`n"
        $header += "@section('title', '$($f.title)')`r`n"
        $header += "@section('subtitle', '$($f.subtitle)')`r`n"
        $header += "@section('active', '$($f.active)')`r`n`r`n"
        $header += "@section('content')`r`n"

        $footer = "`r`n@endsection`r`n"

        $newContent = $header + $content + $footer
        Set-Content -Path $dst -Value $newContent -Encoding UTF8
        Write-Host "  OK: $dst" -ForegroundColor Green
    }
}

# ─── Definisi halaman Admin TU ───────────────────────────────
$adminFiles = @(
    @{file='dashboard-admin';   active='dashboard';        title='Dashboard';           subtitle='Selamat datang di Panel Admin TU'},
    @{file='manajemen-user';    active='manajemen-user';   title='Manajemen User';      subtitle='Kelola pengguna sistem SIRAPI'},
    @{file='data-sekolah';      active='data-sekolah';     title='Data Sekolah';        subtitle='Informasi sekolah yang terdaftar'},
    @{file='guru-tendik';       active='guru';             title='Guru & Tendik';       subtitle='Data guru dan tenaga kependidikan'},
    @{file='data-siswa-admin';  active='data-siswa';       title='Data Siswa';          subtitle='Pengelolaan data siswa'},
    @{file='akademik';          active='akademik';         title='Akademik';            subtitle='Informasi akademik sekolah'},
    @{file='jadwal-pelajaran';  active='jadwal-pelajaran'; title='Jadwal Pelajaran';    subtitle='Jadwal mata pelajaran'},
    @{file='aturan-nilai';      active='aturan-nilai';     title='Aturan Nilai';        subtitle='Konfigurasi aturan penilaian'},
    @{file='mata-pelajaran';    active='mata-pelajaran';   title='Mata Pelajaran';      subtitle='Data mata pelajaran sekolah'},
    @{file='laporan-nilai';     active='laporan-nilai';    title='Laporan Nilai';       subtitle='Laporan rekapitulasi nilai'},
    @{file='rekap-nilai';       active='rekap-nilai';      title='Rekap Nilai';         subtitle='Rekap nilai seluruh kelas'},
    @{file='profil-admin';      active='profil';           title='Profil Admin';        subtitle='Data profil admin TU'}
)

# ─── Definisi halaman Guru Mata Pelajaran ────────────────────
$guruFiles = @(
    @{file='dashboard';           active='dashboard';          title='Dashboard';          subtitle='Selamat datang di Panel Guru'},
    @{file='jadwal-mengajar';     active='jadwal-mengajar';    title='Jadwal Mengajar';    subtitle='Jadwal mengajar per kelas'},
    @{file='data-siswa';          active='data-siswa';         title='Data Siswa';         subtitle='Data siswa yang diajar'},
    @{file='penilaian';           active='penilaian';          title='Penilaian';          subtitle='Input dan kelola nilai siswa'},
    @{file='capaian-kompetensi';  active='capaian-kompetensi'; title='Capaian Kompetensi'; subtitle='Deskripsi capaian kompetensi siswa'},
    @{file='laporan-nilai';       active='laporan-nilai';      title='Laporan Nilai';      subtitle='Laporan nilai per mata pelajaran'},
    @{file='rekap-nilai';         active='rekap-nilai';        title='Rekap Nilai Kelas';  subtitle='Rekap nilai seluruh kelas'},
    @{file='profil';              active='profil';             title='Profil Saya';        subtitle='Data profil guru mata pelajaran'}
)

# ─── Definisi halaman Wali Kelas ─────────────────────────────
$wkFiles = @(
    @{file='dashboard-walikelas'; active='dashboard';    title='Dashboard';       subtitle='Selamat datang di Panel Wali Kelas'},
    @{file='profil-kelas';        active='profil-kelas'; title='Profil Kelas';    subtitle='Informasi kelas yang diampu'},
    @{file='jadwal-kelas';        active='jadwal-kelas'; title='Jadwal Kelas';    subtitle='Jadwal mata pelajaran kelas'},
    @{file='kehadiran';           active='kehadiran';    title='Kehadiran Siswa'; subtitle='Rekap kehadiran siswa'},
    @{file='penilaian';           active='penilaian';    title='Penilaian Kelas'; subtitle='Input dan kelola nilai siswa'},
    @{file='rapor';               active='rapor';        title='Rapor Siswa';     subtitle='Cetak dan kelola rapor'},
    @{file='lihat-rapor';         active='lihat-rapor';  title='Lihat Rapor';     subtitle='Pratinjau rapor siswa'},
    @{file='laporan-nilai';       active='laporan-nilai';title='Laporan Nilai';   subtitle='Laporan rekapitulasi nilai kelas'},
    @{file='rekap-nilai';         active='rekap-nilai';  title='Rekap Nilai';     subtitle='Rekap nilai seluruh siswa'},
    @{file='profil-user';         active='profil';       title='Profil User';     subtitle='Data profil wali kelas'}
)

# ─── Jalankan migrasi ────────────────────────────────────────
Write-Host "`n[ADMIN TU]" -ForegroundColor Cyan
Invoke-RefactorRole -Role "admin" -Layout "layouts.admin" -ShellTag "x-admin-shell" -Files $adminFiles

Write-Host "`n[WALI KELAS]" -ForegroundColor Cyan
Invoke-RefactorRole -Role "walikelas" -Layout "layouts.walikelas" -ShellTag "x-walikelas-shell" -Files $wkFiles

Write-Host "`n[GURU MATA PELAJARAN]" -ForegroundColor Cyan
Invoke-RefactorRole -Role "guru" -Layout "layouts.guru" -ShellTag "x-guru-shell" -Files $guruFiles

Write-Host "`nDONE: Semua halaman berhasil direfaktor!" -ForegroundColor Green
