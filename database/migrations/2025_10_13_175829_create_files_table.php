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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('angkatan_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->string('path');
            $table->string('tipe')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
