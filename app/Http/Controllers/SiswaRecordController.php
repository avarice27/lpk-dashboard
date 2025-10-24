<?php

namespace App\Http\Controllers;

use App\Models\CalonSiswa;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SiswaRecordController extends Controller
{
    public function manage($calonSiswaId)
{
    $siswa = CalonSiswa::with(['alamat','records.nilai'])->findOrFail($calonSiswaId);

    // ambil record terakhir (atau null jika belum ada)
    $record = $siswa->records->sortByDesc('id')->first();

    $pelajaran = MataPelajaran::orderBy('nama')->get();
    $nilaiMap  = $record ? $record->nilai->keyBy('mata_pelajaran_id') : collect();

    return view('records.manage', compact('siswa','record','pelajaran','nilaiMap'));
}

public function save(Request $request, $calonSiswaId)
{
    $siswa = CalonSiswa::with('alamat','records.nilai')->findOrFail($calonSiswaId);

    // jika ada record terakhir → update, kalau tidak → create
    $record = $siswa->records()->latest('id')->first();

    // NOTE: kalau nilai pakai huruf (A/B/C/D), ganti rule numeric jadi string/in:...
    $data = $request->validate([
        'alamat_id' => ['nullable','exists:alamat,id'],
        'status'    => ['required', Rule::in(['stand_by', 'on_job'])],
        'catatan'   => ['nullable','string'],
        'photo'     => ['nullable','image','max:2048'],
        // 'scores'    => ['nullable', 'regex:/^[A-D](\+|-)?$/i'],
        'scores'    => ['sometimes','array'],
            // ✅ tiap itemnya boleh A/B/C/D atau plus/minus (A+, B-, dst), case-insensitive
            'scores.*'  => ['nullable','regex:/^[A-D](\+|-)?$/i'],
    ]);

    $data['scores'] = collect($data['scores'] ?? [])
    ->map(fn($v) => $v ? strtoupper($v) : $v)
    ->toArray();

    // upload foto
    // simpan/update record dulu (tanpa foto)
    if (!$record) {
        $record = $siswa->records()->create([
            'alamat_id'  => $data['alamat_id'] ?? ($siswa->alamat->id ?? null),
            'status'     => $data['status'],
            //'catatan'    => $data['catatan'] ?? null,
        ]);
    } else {
        $record->update([
            'alamat_id'  => $data['alamat_id'] ?? $record->alamat_id,
            'status'     => $data['status'],
            //'catatan'    => $data['catatan'] ?? $record->catatan,
        ]);
    }

    // ===== Upload foto (opsi A: disk 'public') =====
    if ($request->hasFile('photo')) {
        // hapus foto lama HANYA kalau ada
        $old = $record->photo_path;
        if (is_string($old) && $old !== '') {
            Storage::disk('public')->delete($old);
        }

        // simpan foto baru
        $newPath = $request->file('photo')->store('photos/calon_siswa', 'public'); // storage/app/public/...
        $record->update(['photo_path' => $newPath]);
    }

    // upsert nilai per pelajaran
    foreach (($data['scores'] ?? []) as $pelajaranId => $nilai) {
        if ($nilai === null || $nilai === '') continue;
        $record->nilai()->updateOrCreate(
            ['mata_pelajaran_id' => (int)$pelajaranId],
            ['nilai' => $nilai]
        );
    }

    return redirect()
        ->route('records.manage', $siswa->id)
        ->with('success', 'Record & nilai tersimpan.');
}

}
