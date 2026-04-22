<?php

namespace App\Helpers;

class NilaiHelper
{
    /**
     * Hitung nilai akhir berdasarkan persentase
     */
    public static function hitungNilaiAkhir($nilaiTugas, $nilaiUTS, $nilaiUAS)
    {
        return ($nilaiTugas * 0.3) + ($nilaiUTS * 0.3) + ($nilaiUAS * 0.4);
    }

    /**
     * Tentukan predikat huruf berdasarkan nilai akhir
     */
    public static function tentukanPredikat($nilaiAkhir)
    {
        if ($nilaiAkhir >= 85) return 'A';
        if ($nilaiAkhir >= 70) return 'B';
        if ($nilaiAkhir >= 60) return 'C';
        if ($nilaiAkhir >= 50) return 'D';
        return 'E';
    }
}
