<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calon_siswas', function (Blueprint $table) {
            $table->enum('job', ['Cook', 'Deck', 'Engine', 'No Job'])->default('No Job')->after('pengalaman_berlayar');
        });
    }

    public function down(): void
    {
        Schema::table('calon_siswas', function (Blueprint $table) {
            $table->dropColumn('job');
        });
    }
};

