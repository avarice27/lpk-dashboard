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
        Schema::create('angkatans', function (Blueprint $table) {
            $table->id();                     // bigserial
            $table->string('kode')->unique(); // mis. CP31
            $table->string('nama');           // mis. Crash Program 31
            $table->date('mulai')->nullable();
            $table->date('selesai')->nullable();
            $table->integer('total_jam')->nullable()->default(0)
                ->comment('total jam kurikulum angkatan (input manual)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angkatans');
    }
};
