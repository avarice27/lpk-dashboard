<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;
    protected $table = 'mata_pelajarans';
    protected $fillable = ['nama'];

    public function nilai() {
        return $this->hasMany(NilaiPelajaran::class, 'mata_pelajaran_id');
    }

    // relasi ke angkatan melalui pivot kurikulum
    public function angkatans()
    {
        return $this->belongsToMany(Angkatan::class, 'angkatan_mata_pelajaran')
                    ->withPivot(['durasi_jam', 'urutan'])
                    ->withTimestamps();
    }
}
