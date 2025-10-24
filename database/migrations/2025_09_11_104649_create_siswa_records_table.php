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
        Schema::create('siswa_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calon_siswa_id')->constrained('calon_siswas')->cascadeOnDelete();
            $table->foreignId('alamat_id')->nullable()->constrained('alamat')->cascadeOnDelete();
            $table->date('sign_on')->nullable();
            $table->date('sign_off')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamps();

            $table->index(['sign_on', 'sign_off']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_records');
    }
};
