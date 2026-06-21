<?php

namespace App\Contracts;

use App\Models\Guru;

interface GuruServiceInterface
{
    public function validatePengampuSelection(array $validated): void;

    public function validateKelasWaliSelection(array $validated, ?int $currentGuruId = null): void;

    public function prosesSimpan(array $validated): Guru;

    public function prosesUpdate(Guru $guru, array $validated): Guru;

    public function prosesHapus(Guru $guru): void;

    public function buildGuruRow(Guru $guru): array;
}
