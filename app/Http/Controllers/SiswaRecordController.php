<?php

namespace App\Http\Controllers;

use App\Models\CalonSiswa;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

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
        'status'    => ['required', Rule::in(['sign_on', 'sign_off'])],
        'sign_on_date'    => ['nullable','date'],
        'sign_off_date'   => ['nullable','date'],
        'catatan'   => ['nullable','string'],
        'photo'     => ['nullable','image','max:2048'],
        'scores'    => ['sometimes','array'],
            // ✅ tiap itemnya boleh A/B/C/D atau plus/minus (A+, B-, dst), case-insensitive
            'scores.*'  => ['nullable','regex:/^[A-D](\+|-)?$/i'],
    ]);

    $data['scores'] = collect($data['scores'] ?? [])
    ->map(fn($v) => $v ? strtoupper($v) : $v)
    ->toArray();

    // 🔄 Tentukan tanggal otomatis kalau belum diisi
        if (($data['status'] ?? null) === 'sign_on' && empty($data['sign_on_date'])) {
            $data['sign_on_date'] = Carbon::now();
        }

        if (($data['status'] ?? null) === 'sign_off' && empty($data['sign_off_date'])) {
            $data['sign_off_date'] = Carbon::now();
        }

    // upload foto
    // simpan/update record dulu (tanpa foto)
    if (!$record) {
        $record = $siswa->records()->create([
            'alamat_id'  => $data['alamat_id'] ?? ($siswa->alamat->id ?? null),
            'status'     => $data['status'],
            'sign_on_date'   => $data['sign_on_date'] ?? null,
            'sign_off_date'  => $data['sign_off_date'] ?? null,
            //'catatan'    => $data['catatan'] ?? null,
        ]);
    } else {
        $record->update([
            'alamat_id'  => $data['alamat_id'] ?? $record->alamat_id,
            'status'     => $data['status'],
            'sign_on_date'   => $data['sign_on_date'] ?? $record->sign_on_date,
            'sign_off_date'  => $data['sign_off_date'] ?? $record->sign_off_date,
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
