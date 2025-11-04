<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus constraint lama jika ada
        DB::statement("ALTER TABLE siswa_records DROP CONSTRAINT IF EXISTS chk_siswa_records_status");

        Schema::table('siswa_records', function (Blueprint $table) {
            // Ubah kolom status
            if (Schema::hasColumn('siswa_records', 'status')) {
                $table->string('status', 20)->nullable()->change();
            } else {
                $table->string('status', 20)->nullable()->after('alamat_id');
            }

            // Tambahkan dua kolom tanggal baru
            if (!Schema::hasColumn('siswa_records', 'sign_on_date')) {
                $table->date('sign_on_date')->nullable()->after('status');
            }

            if (!Schema::hasColumn('siswa_records', 'sign_off_date')) {
                $table->date('sign_off_date')->nullable()->after('sign_on_date');
            }
        });

        // Perbaiki data lama sebelum pasang constraint baru
        DB::statement("
            UPDATE siswa_records
            SET status = NULL
            WHERE status NOT IN ('sign_on', 'sign_off') OR status IS NULL
        ");

        // Tambahkan constraint baru untuk validasi status
        DB::statement("
            ALTER TABLE siswa_records
            ADD CONSTRAINT chk_siswa_records_status
            CHECK (status IN ('sign_on', 'sign_off') OR status IS NULL)
        ");
    }

    public function down(): void
    {
         // Drop constraint baru
        DB::statement("ALTER TABLE siswa_records DROP CONSTRAINT IF EXISTS chk_siswa_records_status");

        Schema::table('siswa_records', function (Blueprint $table) {
            // Hapus kolom tanggal baru
            if (Schema::hasColumn('siswa_records', 'sign_on_date')) {
                $table->dropColumn(['sign_on_date', 'sign_off_date']);
            }

            // Kembalikan status ke default lama
            $table->string('status', 20)->default('stand_by')->change();
        });

        // Tambahkan kembali constraint lama
        DB::statement("
            ALTER TABLE siswa_records
            ADD CONSTRAINT chk_siswa_records_status
            CHECK (status IN ('stand_by', 'on_job'))
        ");
    }
};
