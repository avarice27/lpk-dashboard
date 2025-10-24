<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileResource extends Model
{
    use HasFactory;
    protected $table = 'files';
    protected $fillable = [
        'angkatan_id',
        'mata_pelajaran_id',
        'nama',
        'path',
        'tipe',
    ];

    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function getUrlAttribute() {
        return asset('storage/' . $this->path);
    }
}
