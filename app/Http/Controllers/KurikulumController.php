<?php
namespace App\Http\Controllers;

use App\Models\Angkatan;
use App\Models\CalonSiswa;
use App\Models\FileResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
class KurikulumController extends Controller
{
  public function index()
  {
    // list semua angkatan + ringkasannya
    $angkatans = Angkatan::withCount('calonSiswas')
      ->with(['mataPelajarans' => function($q){ $q->select('mata_pelajarans.id'); }])
      ->get()
      ->map(function ($a) {
        $a->mapel_count = $a->mataPelajarans->count();
        $a->total_jam_pivot = $a->mataPelajarans->sum(fn($m) => (int)$m->pivot->durasi_jam);
        return $a;
      });

    return view('kurikulum.index', compact('angkatans'));
  }

  public function show(Angkatan $angkatan)
  {
    $angkatan->load([
      'calonSiswas' => fn($q) => $q->orderBy('nomor_peserta'),
        'mataPelajarans' => fn($q) => $q
      ->orderBy('angkatan_mata_pelajaran.urutan')
      ->orderBy('mata_pelajarans.nama'),
      'files.mataPelajaran'
    ])->findOrFail($angkatan->id);

    // total jam: bisa pakai kolom angkatan.total_jam atau hitung dari pivot
    $totalJamPivot = $angkatan->mataPelajarans->sum(fn($m) => (int)$m->pivot->durasi_jam);
    $totalJamManual = $angkatan->total_jam; // jika kamu mau tetap pakai input manual
    $semuaMapel = \App\Models\MataPelajaran::orderBy('nama')->get();

    return view('kurikulum.show', compact('angkatan', 'totalJamPivot', 'totalJamManual', 'semuaMapel'));
  }

  public function attachMapel(Angkatan $angkatan)
{
    $data = request()->validate([
        'mata_pelajaran_id' => ['required','exists:mata_pelajarans,id'],
        'durasi_jam'        => ['required','integer','min:0','max:2000'],
        'urutan'            => ['nullable','integer','min:0','max:999'],
    ]);

    $angkatan->mataPelajarans()->syncWithoutDetaching([
        $data['mata_pelajaran_id'] => [
            'durasi_jam' => $data['durasi_jam'],
            'urutan'     => $data['urutan'] ?? 0,
        ],
    ]);

    return back()->with('success','Mata pelajaran ditambahkan.');
}

public function updateMapel(Angkatan $angkatan, \App\Models\MataPelajaran $mapel)
{
    $data = request()->validate([
        'durasi_jam' => ['required','integer','min:0','max:2000'],
        'urutan'     => ['nullable','integer','min:0','max:999'],
    ]);

    $angkatan->mataPelajarans()->updateExistingPivot($mapel->id, [
        'durasi_jam' => $data['durasi_jam'],
        'urutan'     => $data['urutan'] ?? 0,
    ]);

    return back()->with('success','Durasi/urutan diperbarui.');
}

public function detachMapel(Angkatan $angkatan, \App\Models\MataPelajaran $mapel)
{
    $angkatan->mataPelajarans()->detach($mapel->id);
    return back()->with('success','Mata pelajaran dihapus dari angkatan.');
}

  public function syncSiswaByNomor()
{
    $siswas = CalonSiswa::whereNotNull('nomor_peserta')->get();
    $angkatanCache = [];
    $updated = 0;

    foreach ($siswas as $siswa) {
        $nomor = preg_replace('/\D/', '', $siswa->nomor_peserta);
        if (!$nomor) continue;
        $prefix = substr($nomor, 0, 2);
        $kode = 'CP' . $prefix;

        if(!isset($angkatanCache[$kode])) {
            $angkatan = Angkatan::firstOrCreate(
                ['kode' => $kode],
                ['nama' => "Crash Program {$prefix}"]
            );
            $angkatanCache[$kode] = $angkatan;
        } else {
            $angkatan = $angkatanCache[$kode];
        }

        if ($siswa->angkatan_id !== $angkatan->id) {
            $siswa->angkatan_id = $angkatan->id;
            $siswa->save();
            $updated++;
            }
        }

        return redirect()->route('kurikulum.index')->with('success', 'Sinkronisasi Selesai - {$updated} ');
    }

    public function updatePeriode(Angkatan $angkatan)
{
    $data = request()->validate([
        'mulai' => 'nullable|date',
        'selesai' => 'nullable|date|after_or_equal:mulai',
    ]);

    $angkatan->update($data);

    return back()->with('success', 'Periode angkatan diperbarui.');
}


public function uploadFile(Request $request, Angkatan $angkatan)
{
    $data = $request->validate([
        'file' => 'required|file|max:51200|mimes:pdf,xls,xlsx,png,jpg,jpeg',
        'mata_pelajaran_id' => 'nullable|exists:mata_pelajarans,id',
    ]);

    $file = $request->file('file');
    $path = $file->store('materi', 'public');

    FileResource::create([
        'angkatan_id' => $angkatan->id,
        'mata_pelajaran_id' => $data['mata_pelajaran_id'] ?? null,
        'nama' => $file->getClientOriginalName(),
        'path' => $path,
        'tipe' => $file->getClientOriginalExtension(),
    ]);

    return back()->with('success', 'File berhasil diupload!');
}

public function deleteFile(Angkatan $angkatan, FileResource $file)
{
    Storage::disk('public')->delete($file->path);
    $file->delete();

    return back()->with('success', 'File berhasil dihapus.');
}

}
