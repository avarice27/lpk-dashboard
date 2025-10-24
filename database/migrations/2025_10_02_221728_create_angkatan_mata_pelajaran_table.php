<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('angkatan_mata_pelajaran', function (Blueprint $t) {
      $t->id();
      $t->foreignId('angkatan_id')->constrained('angkatans')->cascadeOnDelete();
      $t->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->cascadeOnDelete();
      $t->integer('durasi_jam')->default(0); // total jam utk pelajaran ini di angkatan tsb (editable manual)
      $t->integer('urutan')->nullable();     // opsional: urutan tampil
      $t->timestamps();
      $t->unique(['angkatan_id','mata_pelajaran_id']);
    });
  }
  public function down(): void {
    Schema::dropIfExists('angkatan_mata_pelajaran');
  }
};
