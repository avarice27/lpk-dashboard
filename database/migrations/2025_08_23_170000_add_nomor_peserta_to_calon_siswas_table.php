<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calon_siswas', function (Blueprint $table) {
            $table->string('nomor_peserta')->unique()->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('calon_siswas', function (Blueprint $table) {
            $table->dropColumn('nomor_peserta');
        });
    }
};
