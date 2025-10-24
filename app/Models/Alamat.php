<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    use HasFactory;

    // pakai tabel tunggal
    protected $table = 'alamat';

    protected $fillable = [
        'calon_siswa_id',
        'rumah',      // ganti ke 'jalan' jika di migration namanya 'jalan'
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'zip_code',
    ];

    public function calonSiswa()
    {
        return $this->belongsTo(CalonSiswa::class, 'calon_siswa_id');
    }
}
