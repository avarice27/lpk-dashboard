<?php

namespace Database\Seeders;

use App\Models\CalonSiswa;
use Illuminate\Database\Seeder;

class CalonSiswaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama_lengkap' => 'AGUNG NUROCHMAN',
                'tempat_lahir' => 'Sumedang',
                'tanggal_lahir' => '2000-03-04',
                'tinggi_badan' => 165,
                'berat_badan' => 60,
                'asal_sekolah' => 'SMK Pemuda, Sumedang',
                'no_kontak' => '0895417895956',
                'nomor_orang_tua' => '085712345678',
                'nama_orang_tua' => 'Wiwi - Hasanah',
                'pengalaman_berlayar' => 'Non pengalaman',
                'job' => 'No Job',
            ],
            [
                'nama_lengkap' => 'YOGA KURNIAWAN',
                'tempat_lahir' => 'Karanganyar',
                'tanggal_lahir' => '2003-08-22',
                'tinggi_badan' => 170,
                'berat_badan' => 65,
                'asal_sekolah' => 'SMK Adi Sumarmo Colomadu',
                'no_kontak' => '0882007039455',
                'nomor_orang_tua' => '085867894321',
                'nama_orang_tua' => 'Muhdar - Maiya',
                'pengalaman_berlayar' => 'Purse seine lokal (6-12 bulan)',
                'job' => 'Deck',
            ],
            [
                'nama_lengkap' => 'MARWAN MUHDAR',
                'tempat_lahir' => 'Kupang',
                'tanggal_lahir' => '2005-04-20',
                'tinggi_badan' => 168,
                'berat_badan' => 58,
                'asal_sekolah' => 'SMA Negeri 4 Lakudo, Buton',
                'no_kontak' => '082123049927',
                'nomor_orang_tua' => '082198765432',
                'nama_orang_tua' => 'Jamaludin - Nulfa',
                'pengalaman_berlayar' => 'Lokal (-6 Bulan)',
                'job' => 'Engine',
            ],
            [
                'nama_lengkap' => 'CASMARI',
                'tempat_lahir' => 'Kupang',
                'tanggal_lahir' => '1999-12-15',
                'tinggi_badan' => 172,
                'berat_badan' => 70,
                'asal_sekolah' => 'Poltek KP Kupang',
                'no_kontak' => '081234567890',
                'nomor_orang_tua' => '081322112233',
                'nama_orang_tua' => 'Maria Magdalena - Mau Malesi',
                'pengalaman_berlayar' => 'Pole & Line (6-12 Bulan)',
                'job' => 'Cook',
            ],
            [
                'nama_lengkap' => 'RESTU ADI PERMANA',
                'tempat_lahir' => 'Sukabumi',
                'tanggal_lahir' => '2002-07-10',
                'tinggi_badan' => 160,
                'berat_badan' => 55,
                'asal_sekolah' => 'SMPN 1 Sukalarang',
                'no_kontak' => '087654321098',
                'nomor_orang_tua' => '087812345678',
                'nama_orang_tua' => 'Ahmad - Siti',
                'pengalaman_berlayar' => 'Long Line (6-12 Bulan)',
                'job' => 'Deck',
            ],
            [
                'nama_lengkap' => 'TETEN JUNIAWAN',
                'tempat_lahir' => 'Kebumen',
                'tanggal_lahir' => '2001-05-25',
                'tinggi_badan' => 175,
                'berat_badan' => 72,
                'asal_sekolah' => 'SMK N 1 PURING',
                'no_kontak' => '089876543210',
                'nomor_orang_tua' => '089998887777',
                'nama_orang_tua' => 'Budi - Rini',
                'pengalaman_berlayar' => 'Di atas 12 Bulan',
                'job' => 'Engine',
            ],
            [
                'nama_lengkap' => 'ARYA DWI SAPUTRA',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2004-11-08',
                'tinggi_badan' => 162,
                'berat_badan' => 52,
                'asal_sekolah' => 'SMK Negeri 1 Jakarta',
                'no_kontak' => '081112223334',
                'nomor_orang_tua' => '081334455667',
                'nama_orang_tua' => 'Dedi - Yuni',
                'pengalaman_berlayar' => 'Non pengalaman',
                'job' => 'No Job',
            ],
            [
                'nama_lengkap' => 'MOH RIAN',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2000-09-12',
                'tinggi_badan' => 169,
                'berat_badan' => 63,
                'asal_sekolah' => 'SMA Negeri 2 Bandung',
                'no_kontak' => '085556667778',
                'nomor_orang_tua' => '085877889900',
                'nama_orang_tua' => 'Roni - Sari',
                'pengalaman_berlayar' => 'Long Line Taiwan 24 Bulan',
                'job' => 'Deck',
            ],
        ];

        $nomorPeserta = 3501;

        foreach ($data as $item) {
            $item['nomor_peserta'] = $nomorPeserta;
            CalonSiswa::create($item);
            $nomorPeserta++;
        }
    }
}
