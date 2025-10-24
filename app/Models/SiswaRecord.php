<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiswaRecord extends Model
{
    use HasFactory;
    protected $table = 'siswa_records';
    protected $fillable = ['calon_siswa_id','alamat_id','status','photo_path','catatan'];
    protected $casts = ['sign_on'=>'date','sign_off'=>'date'];

    public function calonSiswa() { return $this->belongsTo(CalonSiswa::class); }
    public function alamat()     { return $this->belongsTo(Alamat::class, 'alamat_id'); }
    public function nilai()      { return $this->hasMany(NilaiPelajaran::class); }

    // helper: nilai ter-index by pelajaran_id
    public function nilaiByPelajaran() {
        return $this->nilai->keyBy('mata_pelajaran_id');
    }
}
