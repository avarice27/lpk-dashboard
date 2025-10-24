<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Angkatan extends Model
{
    use HasFactory;
    protected $table = 'angkatans';
    protected $fillable = ['kode','nama','mulai','selesai','total_jam'];

    protected $casts = [
    'mulai' => 'date',
    'selesai' => 'date',
    ];


    public function calonSiswas()
    {
        return $this->hasMany(CalonSiswa::class, 'angkatan_id');
    }

    public function mataPelajarans()
    {
        return $this->belongsToMany(MataPelajaran::class, 'angkatan_mata_pelajaran')
                    ->withPivot(['durasi_jam', 'urutan'])
                    ->withTimestamps();
    }

    public function files()
    {
        return $this->hasMany(FileResource::class, 'angkatan_id');
    }
}

