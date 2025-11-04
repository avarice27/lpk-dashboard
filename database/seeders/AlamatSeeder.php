<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alamat;
use App\Models\CalonSiswa;

class AlamatSeeder extends Seeder
{
    public function run(): void
    {
        $alamatData = [
            [
                'rumah' => 'Dusun Ciliang, RT/RW 03/01',
                'kelurahan' => 'Mekarmukti',
                'kecamatan' => 'Buahdua',
                'kota' => 'Sumedang',
                'provinsi' => 'Jawa Barat',
                'zip_code' => '45392',
            ],
            [
                'rumah' => 'Tegalrejo, RT/RW 01/01',
                'kelurahan' => 'Paulan',
                'kecamatan' => 'Colomadu',
                'kota' => 'Karanganyar',
                'provinsi' => 'Jawa Tengah',
                'zip_code' => '57175',
            ],
            [
                'rumah' => 'Ds. Nekmese RT/RW 019/010',
                'kelurahan' => 'Amarasi Selatan',
                'kecamatan' => 'Amarasi Selatan',
                'kota' => 'Kupang',
                'provinsi' => 'Nusa Tenggara Timur',
                'zip_code' => '85362',
            ],
            [
                'rumah' => 'Jl. Raya Kupang No. 123',
                'kelurahan' => 'Oebobo',
                'kecamatan' => 'Oebobo',
                'kota' => 'Kupang',
                'provinsi' => 'Nusa Tenggara Timur',
                'zip_code' => '85111',
            ],
            [
                'rumah' => 'Jl. Raya Sukalarang',
                'kelurahan' => 'Sukalarang',
                'kecamatan' => 'Sukalarang',
                'kota' => 'Sukabumi',
                'provinsi' => 'Jawa Barat',
                'zip_code' => '43194',
            ],
            [
                'rumah' => 'Desa Puring',
                'kelurahan' => 'Puring',
                'kecamatan' => 'Puring',
                'kota' => 'Kebumen',
                'provinsi' => 'Jawa Tengah',
                'zip_code' => '54383',
            ],
            [
                'rumah' => 'Jl. Cilandak Tengah',
                'kelurahan' => 'Cilandak Barat',
                'kecamatan' => 'Cilandak',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'zip_code' => '12430',
            ],
            [
                'rumah' => 'Jl. Sukajadi No. 45',
                'kelurahan' => 'Sukagalih',
                'kecamatan' => 'Sukajadi',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'zip_code' => '40162',
            ],
        ];

        $siswas = CalonSiswa::orderBy('id')->get();
        foreach ($siswas as $i => $siswa) {
            if (isset($alamatData[$i])) {
                Alamat::create(array_merge(
                    $alamatData[$i],
                    ['calon_siswa_id' => $siswa->id]
                ));
            }
        }
    }
}
