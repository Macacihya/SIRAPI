$adminFiles = @(
    @{file='dashboard-admin'; active='dashboard'; title='Dashboard'; subtitle='Selamat datang di Panel Admin TU'},
    @{file='manajemen-user'; active='manajemen-user'; title='Manajemen User'; subtitle='Kelola pengguna sistem SIRAPI'},
    @{file='data-sekolah'; active='data-sekolah'; title='Data Sekolah'; subtitle='Informasi sekolah yang terdaftar'},
    @{file='guru-tendik'; active='guru'; title='Guru'; subtitle='Data guru dan tenaga kependidikan'},
    @{file='data-siswa-admin'; active='data-siswa'; title='Data Siswa'; subtitle='Pengelolaan data siswa'},
    @{file='akademik'; active='akademik'; title='Akademik'; subtitle='Informasi akademik sekolah'},
    @{file='jadwal-pelajaran'; active='jadwal-pelajaran'; title='Jadwal Pelajaran'; subtitle='Jadwal mata pelajaran'},
    @{file='aturan-nilai'; active='aturan-nilai'; title='Aturan Nilai'; subtitle='Konfigurasi aturan penilaian'},
    @{file='profil-admin'; active='profil'; title='Profil Admin'; subtitle='Data profil admin TU'}
)

foreach ($f in $adminFiles) {
    $src = "resources\views\admin\" + $f.file + ".blade.php"
    $dst = "resources\views\pages\admin\" + $f.file + ".blade.php"
    $content = Get-Content $src -Raw
    
    $content = $content -replace '<x-admin-shell[^>]*>', ''
    $content = $content -replace '</x-admin-shell>', ''
    $content = $content.Trim()
    
    $header = "{{-- Halaman: " + $f.file + " --- menggunakan layout admin --}}`r`n"
    $header += "@extends('layouts.admin')`r`n"
    $header += "@section('title', '" + $f.title + "')`r`n"
    $header += "@section('subtitle', '" + $f.subtitle + "')`r`n"
    $header += "@section('active', '" + $f.active + "')`r`n`r`n"
    $header += "@section('content')`r`n"
    
    $footer = "`r`n@endsection`r`n"
    
    $newContent = $header + $content + $footer
    Set-Content -Path $dst -Value $newContent -Encoding UTF8
    Write-Host "Created: $dst"
}

# Now do walikelas files
$wkFiles = @(
    @{file='profil-kelas'; active='profil-kelas'; title='Profil Kelas'; subtitle='Selamat datang di Panel Wali Kelas'},
    @{file='jadwal-kelas'; active='jadwal-kelas'; title='Jadwal Kelas'; subtitle='Jadwal mata pelajaran kelas'},
    @{file='kehadiran'; active='kehadiran'; title='Kehadiran Siswa'; subtitle='Rekap kehadiran siswa'},
    @{file='penilaian'; active='penilaian'; title='Penilaian Kelas'; subtitle='Input dan kelola nilai siswa'},
    @{file='rapor'; active='rapor'; title='Rapor Siswa'; subtitle='Cetak dan kelola rapor'},
    @{file='profil-user'; active='profil'; title='Profil User'; subtitle='Data profil wali kelas'},
    @{file='dashboard-walikelas'; active='dashboard'; title='Dashboard'; subtitle='Selamat datang di Panel Wali Kelas'}
)

foreach ($f in $wkFiles) {
    $src = "resources\views\walikelas\" + $f.file + ".blade.php"
    if (-not (Test-Path $src)) { Write-Host "SKIP (not found): $src"; continue }
    $dst = "resources\views\pages\walikelas\" + $f.file + ".blade.php"
    $content = Get-Content $src -Raw
    
    $content = $content -replace '<x-walikelas-shell[^>]*>', ''
    $content = $content -replace '</x-walikelas-shell>', ''
    $content = $content.Trim()
    
    $header = "{{-- Halaman: " + $f.file + " --- menggunakan layout walikelas --}}`r`n"
    $header += "@extends('layouts.walikelas')`r`n"
    $header += "@section('title', '" + $f.title + "')`r`n"
    $header += "@section('subtitle', '" + $f.subtitle + "')`r`n"
    $header += "@section('active', '" + $f.active + "')`r`n`r`n"
    $header += "@section('content')`r`n"
    
    $footer = "`r`n@endsection`r`n"
    
    $newContent = $header + $content + $footer
    Set-Content -Path $dst -Value $newContent -Encoding UTF8
    Write-Host "Created: $dst"
}

Write-Host "DONE: All admin and walikelas pages refactored!"
