<?php

namespace Database\Seeders;

use App\Models\CalonSiswa;
use Illuminate\Database\Seeder;

class UpdateNomorPesertaSeeder extends Seeder
{
    public function run(): void
    {
        $calonSiswas = CalonSiswa::whereNull('nomor_peserta')->orderBy('id')->get();

        $nomorPeserta = 3501;
        foreach ($calonSiswas as $siswa) {
            $siswa->update(['nomor_peserta' => $nomorPeserta]);
            $nomorPeserta++;
        }

        $this->command->info('Berhasil mengupdate ' . $calonSiswas->count() . ' data dengan nomor peserta.');
    }
}
