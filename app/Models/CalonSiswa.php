<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CalonSiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_peserta',
        'nama_lengkap',
        'tanggal_lahir',
        'tempat_lahir',
        'tinggi_badan',
        'berat_badan',
        'asal_sekolah',
        'no_kontak',
        'nama_orang_tua',
        'nomor_orang_tua',
        'pengalaman_berlayar',
        'job',
        'catatan',
        'angkatan_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tinggi_badan'  => 'integer',
        'berat_badan'   => 'integer',
    ];

    // 🔧 perbarui: jangan cari di alamat_lengkap lagi
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('asal_sekolah', 'like', "%{$search}%")
              ->orWhere('no_kontak', 'like', "%{$search}%")
              ->orWhere('nama_orang_tua', 'like', "%{$search}%")
              ->orWhere('pengalaman_berlayar', 'like', "%{$search}%")
              ->orWhere('job', 'like', "%{$search}%");
        })
        // cari di relasi alamat juga
        ->orWhereHas('alamat', function($qa) use ($search) {
            $qa->where('rumah',      'like', "%{$search}%")   // ganti ke 'jalan' jika kolommu 'jalan'
               ->orWhere('kelurahan','like', "%{$search}%")
               ->orWhere('kecamatan','like', "%{$search}%")
               ->orWhere('kota',     'like', "%{$search}%")
               ->orWhere('provinsi', 'like', "%{$search}%")
               ->orWhere('zip_code', 'like', "%{$search}%");
        });
    }

    public function getUmurAttribute()
    {
        return $this->tanggal_lahir->age;
    }

    public function getBmiAttribute()
    {
        if ($this->tinggi_badan > 0) {
            $m = $this->tinggi_badan / 100;
            return round($this->berat_badan / ($m * $m), 1);
        }
        return null;
    }

    public function alamat()
    {
        return $this->hasOne(\App\Models\Alamat::class, 'calon_siswa_id');
    }

    // ✅ accessor untuk menampilkan alamat gabungan
    public function getAlamatTeksAttribute(): ?string
    {
        $a = $this->alamat;
        if (!$a) return null;

        return collect([
            $a->rumah,       // ganti ke $a->jalan jika nama kolommu 'jalan'
            $a->kelurahan,
            $a->kecamatan,
            $a->kota,
            $a->provinsi,
            $a->zip_code,
        ])->filter()->implode(', ');
    }
    public function getPengalamanPartsAttribute(): array
    {
        $raw = (string) $this->pengalaman_berlayar;

        // Data baru: format "lokasi|jenis|durasi"
        if (str_contains($raw, '|')) {
            [$lok, $jenis, $dur] = array_pad(explode('|', $raw, 3), 3, null);

            $lok = $lok ? strtolower(trim($lok)) : null;
            $jenis = $jenis ? strtolower(trim($jenis)) : null;
            $dur = is_numeric($dur) ? (int) $dur : null;

            $lokLabels = [
                'lokal' => 'Lokal',
                'internasional' => 'Internasional',
                'tidak ada pengalaman' => 'Tidak ada pengalaman',
            ];
            $jenisLabels = [
                'purse_seine' => 'Purse Seine',
                'long_line'   => 'Long Line',
                'pole_line'   => 'Pole & Line',
                'handline'    => 'Handline',
                'trawl'       => 'Trawl',
                'tidak ada'   => 'Tidak ada',
                'none'        => 'Tidak ada',
            ];

            return [
                'mode'          => 'structured',
                'lokasi'        => $lok,
                'lokasi_label'  => $lok ? ($lokLabels[$lok] ?? ucfirst($lok)) : null,
                'jenis'         => $jenis,
                'jenis_label'   => $jenis ? ($jenisLabels[$jenis] ?? ucwords(str_replace('_',' ', $jenis))) : null,
                'durasi'        => $dur,
                'durasi_label'  => !is_null($dur) ? $dur.' bln' : null,
                'raw'           => $raw,
            ];
        }

        // Data lama (tanpa '|'): langsung pakai raw
        return [
            'mode'          => 'raw',
            'lokasi'        => null,
            'lokasi_label'  => null,
            'jenis'         => null,
            'jenis_label'   => null,
            'durasi'        => null,
            'durasi_label'  => null,
            'raw'           => trim($raw) ?: null,
        ];
    }

    /**
     * Satu string siap tampil sebagai fallback (mis. tooltip).
     */
    public function getPengalamanLabelAttribute(): string
    {
        $p = $this->pengalaman_parts;
        if ($p['mode'] === 'structured') {
            return collect([$p['lokasi_label'], $p['jenis_label'], $p['durasi_label']])
                ->filter()->implode(' • ');
        }
        return $p['raw'] ?? '-';
    }

    public function records()
    {
        return $this->hasMany(\App\Models\SiswaRecord::class, 'calon_siswa_id');
    }

    public function angkatan() { return $this->belongsTo(Angkatan::class, ); }
}
