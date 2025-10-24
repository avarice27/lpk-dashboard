<?php

namespace App\Http\Controllers;

use App\Models\CalonSiswa;
use Carbon\Carbon;

class CalonSiswaController extends Controller
{
    public function summary($id)
    {
        $siswa = CalonSiswa::with(['alamat','records.nilai.mataPelajaran'])->findOrFail($id);

        $record = $siswa->records->sortByDesc('id')->first();

        $nilai = [];
        if ($record) {
            $nilai = $record->nilai->map(fn($n) => [
                'pelajaran' => $n->mataPelajaran?->nama ?? '-',
                'nilai'     => $n->nilai,
            ])->sortBy('pelajaran')->values();
        }

        $age = $siswa->tanggal_lahir ? Carbon::parse($siswa->tanggal_lahir)->age : null;
        $bmi = null;
        if ($siswa->tinggi_badan && $siswa->berat_badan) {
            $m = max(0.01, $siswa->tinggi_badan) / 100;
            $bmi = round($siswa->berat_badan / ($m * $m), 1);
        }

        return response()->json([
            'id'            => $siswa->id,
            'nomor_peserta' => $siswa->nomor_peserta,
            'nama_lengkap'  => $siswa->nama_lengkap,
            'usia'          => $age,
            'tanggal_lahir' => optional($siswa->tanggal_lahir)->format('Y-m-d'),
            'tinggi_badan'  => $siswa->tinggi_badan,
            'berat_badan'   => $siswa->berat_badan,
            'bmi'           => $bmi,
            'asal_sekolah'  => $siswa->asal_sekolah,
            'no_kontak'     => $siswa->no_kontak,
            'nama_orang_tua'=> $siswa->nama_orang_tua,
            'pengalaman'    => $siswa->pengalaman_berlayar,
            'job'           => $siswa->job,
            'alamat' => [
                'rumah'     => $siswa->alamat->rumah ?? null,
                'kelurahan' => $siswa->alamat->kelurahan ?? null,
                'kecamatan' => $siswa->alamat->kecamatan ?? null,
                'kota'      => $siswa->alamat->kota ?? null,
                'provinsi'  => $siswa->alamat->provinsi ?? null,
                'zip'       => $siswa->alamat->zip_code ?? null,
                'teks'      => $siswa->alamat->alamat_teks ?? null,
            ],
            'record' => $record ? [
                'status'     => $record->status,
                'photo_url'  => $record->photo_path ? asset('storage/'.$record->photo_path) : null,
                'catatan'    => $record->catatan,
                'updated_at' => optional($record->updated_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ] : null,
            'nilai' => $nilai,
        ]);
    }

    public function destroy($id)
{
    $siswa = CalonSiswa::findOrFail($id);

    // Hapus alamat terkait (jika ada)
    if ($siswa->alamat) {
        $siswa->alamat->delete();
    }

    // Hapus record siswa
    $siswa->delete();

    return redirect()
        ->route('dashboard')
        ->with('success', 'Data calon siswa berhasil dihapus.');
}

}
