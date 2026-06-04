<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CapaianKompetensi;
use App\Models\Nilai;

class CapaianKompetensiSeeder extends Seeder
{
    public function run(): void
    {
        $nilais = Nilai::with('mataPelajaran')->get();

        $deskripsiTemplates = [
            'BIN'  => 'Menunjukkan pemahaman yang sangat baik dalam menganalisis informasi, membaca teks sastra, serta menulis karya kreatif.',
            'MTK'  => 'Mampu memahami konsep dasar perhitungan matematika, pemecahan masalah bilangan bulat, serta geometri sederhana.',
            'IPA'  => 'Menunjukkan ketertarikan dan pemahaman tinggi dalam mengamati makhluk hidup, hukum alam, serta ekosistem sekitar.',
            'IPS'  => 'Mampu mengidentifikasi keragaman sosial budaya di wilayah setempat dan memahami sejarah dasar kemerdekaan.',
            'BING' => 'Sangat aktif dalam percakapan sehari-hari menggunakan bahasa Inggris dan menguasai kosakata dasar sekolah.',
            'PKN'  => 'Menunjukkan pengamalan nilai-nilai Pancasila, kebersamaan, toleransi, serta tata tertib di sekolah dengan sangat baik.',
            'PAI'  => 'Mampu melafalkan ayat-ayat pilihan dengan baik, memahami rukun iman, serta berperilaku terpuji terhadap sesama.',
            'PJOK' => 'Menunjukkan keterampilan gerak dasar atletik, kebugaran jasmani, serta sportivitas dalam olahraga tim.',
            'SBK'  => 'Sangat kreatif dalam membuat prakarya dari bahan bekas, menggambar dekoratif, serta menyanyikan lagu daerah.',
        ];

        foreach ($nilais as $nilai) {
            $code = $nilai->mapel_id;
            $deskripsi = $deskripsiTemplates[$code] ?? 'Menunjukkan pencapaian kompetensi yang baik dan memuaskan pada mata pelajaran ini.';

            CapaianKompetensi::updateOrCreate(
                ['nilai_id' => $nilai->id],
                [
                    'deskripsi' => $deskripsi,
                ]
            );
        }
    }
}
