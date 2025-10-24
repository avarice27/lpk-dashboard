<?php

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
        // Ambil semua siswa
        $siswas = DB::table('calon_siswas')->select('id','alamat_lengkap')->get();

        foreach ($siswas as $siswa) {
            if ($siswa->alamat_lengkap) {
                // Parsing sederhana: isi semua ke kolom 'jalan', provinsi kosong dulu
                DB::table('alamat')->insert([
                    'calon_siswa_id' => $siswa->id,
                    'rumah'          => $siswa->alamat_lengkap,
                    'kelurahan'      => null,
                    'kecamatan'      => null,
                    'kota'           => null,
                    'provinsi'       => null,
                    'zip_code'       => null,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('alamat')->delete();
    }
};
