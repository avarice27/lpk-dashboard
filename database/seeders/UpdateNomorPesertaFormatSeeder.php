<?php

namespace Database\Seeders;

use App\Models\CalonSiswa;
use Illuminate\Database\Seeder;

class UpdateNomorPesertaFormatSeeder extends Seeder
{
    public function run(): void
    {
        $calonSiswas = CalonSiswa::where('nomor_peserta', 'like', 'LPK-%')->get();

        foreach ($calonSiswas as $siswa) {
            $nomorBaru = str_replace('LPK-', '', $siswa->nomor_peserta);
            $siswa->update(['nomor_peserta' => $nomorBaru]);
        }

        $this->command->info('Berhasil mengupdate format nomor peserta untuk ' . $calonSiswas->count() . ' data.');
    }
}
