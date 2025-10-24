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
        Schema::create('alamat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calon_siswa_id')
            ->constrained('calon_siswas')
            ->cascadeOnDelete();
            $table->string('rumah')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->timestamps();

            // index untuk pencarian cepat
            $table->index(['provinsi', 'kota', 'kecamatan', 'kelurahan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alamat');
    }
};
