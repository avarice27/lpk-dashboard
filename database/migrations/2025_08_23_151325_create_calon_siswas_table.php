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
        Schema::create('calon_siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->date('tanggal_lahir');
            $table->integer('tinggi_badan')->comment('dalam cm');
            $table->integer('berat_badan')->comment('dalam kg');
            $table->string('asal_sekolah');
            $table->string('no_kontak');
            $table->text('alamat_lengkap');
            $table->string('nama_orang_tua');
            $table->string('pengalaman_berlayar')->nullable();
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Indexes untuk optimasi pencarian
            $table->index('nama_lengkap');
            $table->index('asal_sekolah');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calon_siswas');
    }
};
