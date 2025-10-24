<?php

namespace App\Http\Controllers;

use App\Models\Angkatan;
use Illuminate\Http\Request;

class AngkatanController extends Controller
{
    public function create()
    {
        return view('angkatan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode'      => ['required','string','max:50','unique:angkatans,kode'],
            'nama'      => ['required','string','max:255'],
            'mulai'     => ['nullable','date'],
            'selesai'   => ['nullable','date','after_or_equal:mulai'],
            'total_jam' => ['nullable','integer','min:0','max:10000'],
        ]);

        Angkatan::create($data);
        return redirect()->route('angkatan.index')->with('success','Angkatan dibuat.');
    }

    public function edit(Angkatan $angkatan)
    {
        return view('angkatan.edit', compact('angkatan'));
    }

    public function update(Request $request, Angkatan $angkatan)
    {
        $data = $request->validate([
            'kode'      => ['required','string','max:50',"unique:angkatans,kode,{$angkatan->id}"],
            'nama'      => ['required','string','max:255'],
            'mulai'     => ['nullable','date'],
            'selesai'   => ['nullable','date','after_or_equal:mulai'],
            'total_jam' => ['nullable','integer','min:0','max:10000'],
        ]);

        $angkatan->update($data);
        return redirect()->route('angkatan.index')->with('success','Angkatan diperbarui.');
    }
}
