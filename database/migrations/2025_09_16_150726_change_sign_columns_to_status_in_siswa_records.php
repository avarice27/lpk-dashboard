<?php

//use Illuminate\Container\Attributes\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('siswa_records', function (Blueprint $table) {
            $table->dropColumn(['sign_on', 'sign_off']);
        });

        Schema::table('siswa_records', function (Blueprint $table) {
            $table->string('status', 20)->default('stand_by')->after('alamat_id');
    });

    // add 2 constraint option
    DB::statement("
        ALTER TABLE siswa_records
        ADD CONSTRAINT chk_siswa_records_status
        CHECK (status IN ('stand_by', 'on_job'))
    ");
}
    public function down(): void
    {
        Schema::table('siswa_records', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->date('sign_on')->nullable();
            $table->date('sign_off')->nullable();
        });
    }
};
