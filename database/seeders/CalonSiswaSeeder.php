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
                'tanggal_lahir' => '2000-03-04',
                'tinggi_badan' => 165,
                'berat_badan' => 60,
                'asal_sekolah' => 'SMK Pemuda, Sumedang',
                'no_kontak' => '0895417895956',
                'alamat_lengkap' => 'Dusun Ciliang, RT/RW. 03/01, Ds.Mekarmukti, Kec. Buahdua, Kab. Sumedang, Jawa barat.',
                'nama_orang_tua' => 'Wiwi - Hasanah',
                'pengalaman_berlayar' => 'Non pengalaman',
                'job' => 'No Job',
            ],
            [
                'nama_lengkap' => 'YOGA KURNIAWAN',
                'tanggal_lahir' => '2003-08-22',
                'tinggi_badan' => 170,
                'berat_badan' => 65,
                'asal_sekolah' => 'SMK Adi Sumarmo Colomadu',
                'no_kontak' => '0882007039455',
                'alamat_lengkap' => 'Tegalrejo, RT / RW 01/01 Paulan, Colomadu, Karanganyar. Jawa Tengah',
                'nama_orang_tua' => 'Muhdar - Maiya',
                'pengalaman_berlayar' => 'Purse seine lokal ( 6-12 bln )',
                'job' => 'Deck',
            ],
            [
                'nama_lengkap' => 'MARWAN MUHDAR',
                'tanggal_lahir' => '2005-04-20',
                'tinggi_badan' => 168,
                'berat_badan' => 58,
                'asal_sekolah' => 'SMA Negeri 4 Lakudo, Buton',
                'no_kontak' => '82123049927',
                'alamat_lengkap' => 'Ds. Nekmese RT/RT 019/010 Kec. Amarasi Selatan, Kab. Kupang, Nusa Tenggara Timur',
                'nama_orang_tua' => 'Jamaludin - Nulfa',
                'pengalaman_berlayar' => 'Lokal ( -6 Bulan )',
                'job' => 'Engine',
            ],
            [
                'nama_lengkap' => 'CASMARI',
                'tanggal_lahir' => '1999-12-15',
                'tinggi_badan' => 172,
                'berat_badan' => 70,
                'asal_sekolah' => 'Poltek KP Kupang',
                'no_kontak' => '081234567890',
                'alamat_lengkap' => 'Jl. Raya Kupang No. 123, Kupang, NTT',
                'nama_orang_tua' => 'Maria Mgdalena - Mau Malesi',
                'pengalaman_berlayar' => 'Pole & Line (6-12 Bulan)',
                'job' => 'Cook',
            ],
            [
                'nama_lengkap' => 'RESTU ADI PERMANA',
                'tanggal_lahir' => '2002-07-10',
                'tinggi_badan' => 160,
                'berat_badan' => 55,
                'asal_sekolah' => 'SMPN 1Sukalarang',
                'no_kontak' => '087654321098',
                'alamat_lengkap' => 'Sukalarang, Sukabumi, Jawa Barat',
                'nama_orang_tua' => 'Ahmad - Siti',
                'pengalaman_berlayar' => 'Long Line (6-12 Bulan)',
                'job' => 'Deck',
            ],
            [
                'nama_lengkap' => 'TETEN JUNIAWAN',
                'tanggal_lahir' => '2001-05-25',
                'tinggi_badan' => 175,
                'berat_badan' => 72,
                'asal_sekolah' => 'SMK N 1 PURING',
                'no_kontak' => '089876543210',
                'alamat_lengkap' => 'Puring, Kebumen, Jawa Tengah',
                'nama_orang_tua' => 'Budi - Rini',
                'pengalaman_berlayar' => 'Diatas 12 Bulan',
                'job' => 'Engine',
            ],
            [
                'nama_lengkap' => 'ARYA DWI SAPUTRA',
                'tanggal_lahir' => '2004-11-08',
                'tinggi_badan' => 162,
                'berat_badan' => 52,
                'asal_sekolah' => 'SMK Negeri 1 Jakarta',
                'no_kontak' => '081112223334',
                'alamat_lengkap' => 'Jakarta Selatan, DKI Jakarta',
                'nama_orang_tua' => 'Dedi - Yuni',
                'pengalaman_berlayar' => 'Non pengalaman',
                'job' => 'No Job',
            ],
            [
                'nama_lengkap' => 'MOH RIAN',
                'tanggal_lahir' => '2000-09-12',
                'tinggi_badan' => 169,
                'berat_badan' => 63,
                'asal_sekolah' => 'SMA Negeri 2 Bandung',
                'no_kontak' => '085556667778',
                'alamat_lengkap' => 'Bandung, Jawa Barat',
                'nama_orang_tua' => 'Roni - Sari',
                'pengalaman_berlayar' => 'Long line Taiwan 24 Bulan',
                'job' => 'Deck',
            ],
        ];

        $nomorPeserta = 3501;
        foreach ($data as $item) {
            $item['nomor_peserta'] = $nomorPeserta; // Hapus 'LPK-'
            CalonSiswa::create($item);
            $nomorPeserta++;
        }
    }
}
