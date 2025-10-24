<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nilai_pelajarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_record_id')->constrained('siswa_records')->cascadeOnDelete();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->cascadeOnDelete();
            $table->string('nilai', 2)->nullable();
            $table->timestamps();

            $table->unique(['siswa_record_id','mata_pelajaran_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_pelajarans');
    }
};
