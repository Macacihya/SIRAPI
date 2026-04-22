<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Helpers\NilaiHelper;

class KalkulasiMataPelajaranTest extends TestCase
{
    /**
     * Memastikan fungsi hitung nilai akhir berjalan dengan kalkulasi persentase yang benar.
     */
    public function test_hitung_nilai_akhir_berjalan_dengan_benar(): void
    {
        // Simulasi Nilai: Tugas=80, UTS=80, UAS=90
        // (80*0.3) + (80*0.3) + (90*0.4) = 24 + 24 + 36 = 84
        $nilaiAkhir = NilaiHelper::hitungNilaiAkhir(80, 80, 90);
        
        $this->assertEquals(84, $nilaiAkhir);
    }

    /**
     * Memastikan predikat huruf dikonversi dengan benar.
     */
    public function test_tentukan_predikat_mengembalikan_grade_yang_tepat(): void
    {
        // >= 85 -> A
        $this->assertEquals('A', NilaiHelper::tentukanPredikat(85));
        
        // 70 - 84 -> B
        $this->assertEquals('B', NilaiHelper::tentukanPredikat(84));
        $this->assertEquals('B', NilaiHelper::tentukanPredikat(70));
        
        // 60 - 69 -> C
        $this->assertEquals('C', NilaiHelper::tentukanPredikat(65));
        
        // < 50 -> E
        $this->assertEquals('E', NilaiHelper::tentukanPredikat(40));
    }
}
