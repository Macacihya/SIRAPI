<?php

namespace App\Traits;

use App\Models\LogAktivitas;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    // Log otomatis saat data dibuat, diubah, atau dihapus
    protected static function bootLogsActivity()
    {
        static::created(function (Model $model) {
            self::recordActivity($model, 'Membuat');
        });

        static::updated(function (Model $model) {
            self::recordActivity($model, 'Memperbarui');
        });

        static::deleted(function (Model $model) {
            self::recordActivity($model, 'Menghapus');
        });
    }

    // Simpan log, $model bertipe 'Model' agar dinamis menerima model apa saja.
    protected static function recordActivity(Model $model, $action)
    {
        if (!auth()->check()) {
            return;
        }

        $modelName = class_basename($model);
        
        // Mencari nilai pengenal unik secara dinamis dari model yang bersangkutan
        $identifier = $model->nama ??
                      $model->name ??
                      $model->judul ??
                      $model->nama_kelas ??
                      $model->nama_mapel ??
                      $model->nama_komponen ??
                      $model->id;

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'judul' => "{$action} {$modelName}",
            'deskripsi' => "Pengguna telah {$action} data {$modelName} ({$identifier})",
            'waktu' => now(),
        ]);
    }
}
