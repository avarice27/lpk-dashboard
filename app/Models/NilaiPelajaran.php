<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NilaiPelajaran extends Model
{
    use HasFactory;
    protected $table = 'nilai_pelajarans';
    protected $fillable = ['siswa_record_id','mata_pelajaran_id','nilai'];

    public function record()        { return $this->belongsTo(SiswaRecord::class, 'siswa_record_id'); }
    public function mataPelajaran() { return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id'); }
}
