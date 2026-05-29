<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('raport_sikaps')) {
            Schema::create('raport_sikaps', function (Blueprint $table) {
                $table->id();
                $table->foreignId('raport_id')->constrained('raports')->cascadeOnDelete();
                $table->foreignId('sikap_id')->constrained('sikaps')->cascadeOnDelete();
                $table->enum('predikat', ['A', 'B', 'C', 'D'])->nullable();
                $table->text('deskripsi')->nullable();
                $table->timestamps();

                $table->unique(['raport_id', 'sikap_id']);
            });
        }

        if (Schema::hasTable('sikaps')) {
            foreach (['Spiritual', 'Sosial'] as $namaSikap) {
                DB::table('sikaps')->updateOrInsert(
                    ['nama_sikap' => $namaSikap],
                    ['updated_at' => now(), 'created_at' => now()]
                );
            }
        }

        if (
            Schema::hasTable('nilai_sikaps')
            && Schema::hasTable('raport_sikaps')
            && DB::table('raport_sikaps')->count() === 0
        ) {
            $sosialId = DB::table('sikaps')->where('nama_sikap', 'Sosial')->value('id');

            if ($sosialId) {
                DB::table('nilai_sikaps')->orderBy('id')->get()->each(function ($nilaiSikap) use ($sosialId) {
                    DB::table('raport_sikaps')->updateOrInsert(
                        [
                            'raport_id' => $nilaiSikap->raport_id,
                            'sikap_id' => $sosialId,
                        ],
                        [
                            'predikat' => $nilaiSikap->predikat,
                            'deskripsi' => $nilaiSikap->deskripsi,
                            'created_at' => $nilaiSikap->created_at ?? now(),
                            'updated_at' => $nilaiSikap->updated_at ?? now(),
                        ]
                    );
                });
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('raport_sikaps');
    }
};
