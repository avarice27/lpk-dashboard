<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class MataPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));
        $items = MataPelajaran::when($q !== '', fn($qq)=>$qq->where('nama','ILIKE',"%{$q}%"))
            ->orderBy('nama')
            ->paginate(10)
            ->appends($request->query());

        return view('pelajaran.index', compact('items','q'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required','string','max:100','unique:mata_pelajarans,nama'],
        ]);
        MataPelajaran::create($data);

        return back()->with('success','Mata pelajaran ditambahkan.');
    }

    public function edit(MataPelajaran $pelajaran)
    {
        return view('pelajaran.edit', compact('pelajaran'));
    }

    public function update(Request $request, MataPelajaran $pelajaran)
    {
        $data = $request->validate([
            'nama' => ['required','string','max:100', Rule::unique('mata_pelajarans','nama')->ignore($pelajaran->id)],
        ]);
        $pelajaran->update($data);

        return redirect()->route('pelajaran.index')->with('success','Perubahan disimpan.');
    }

    public function destroy(MataPelajaran $pelajaran)
    {
        // opsional: cegah hapus jika sudah dipakai nilai
        // if ($pelajaran->nilai()->exists()) return back()->with('error','Tidak bisa dihapus: sudah dipakai.');
        $pelajaran->delete();

        return back()->with('success','Mata pelajaran dihapus.');
    }
}
