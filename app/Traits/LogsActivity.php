<?php

namespace App\Traits;

use App\Models\LogAktivitas;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    /**
     * Boot trait LogsActivity.
     */
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

    /**
     * Record activity into log_aktivitas table.
     */
    protected static function recordActivity(Model $model, $action)
    {
        // Don't log if running from console/seeder without auth
        if (!auth()->check()) {
            return;
        }

        $modelName = class_basename($model);
        
        // Coba dapatkan field unik sebagai pengenal (nama, title, dll)
        $identifier = $model->nama ?? $model->name ?? $model->judul ?? $model->nama_kelas ?? $model->nama_mapel ?? $model->nama_komponen ?? $model->id;

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'judul' => "{$action} {$modelName}",
            'deskripsi' => "Pengguna telah {$action} data {$modelName} ({$identifier})",
            'waktu' => now(),
        ]);
    }
}
