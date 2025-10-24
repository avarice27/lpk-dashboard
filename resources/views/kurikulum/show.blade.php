@extends('layouts.app')

@section('content')
    <script src="https://cdn.tailwindcss.com"></script>
    <div class="max-w-6xl mx-auto p-6 space-y-6">
        <div>
            <h1 class="text-3xl font-bold">{{ $angkatan->kode }} — {{ $angkatan->nama }}</h1>
            <p class="text-gray-600">
                Periode: {{ optional($angkatan->mulai)->format('d M Y') ?? '-' }} –
                {{ optional($angkatan->selesai)->format('d M Y') ?? '-' }}
            </p>
        </div>

        {{-- --- Bagian siswa --- --}}
        <div class="bg-white shadow rounded-lg p-5">
            <h2 class="text-lg font-semibold mb-3">👨‍🎓 Siswa ({{ $angkatan->calonSiswas->count() }})</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border">
                    <thead class="bg-gray-50 text-sm">
                        <tr>
                            <th class="px-3 py-2 border">Nomor</th>
                            <th class="px-3 py-2 border text-left">Nama Lengkap</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($angkatan->calonSiswas as $s)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 border text-center">{{ $s->nomor_peserta }}</td>
                                <td class="px-3 py-2 border">{{ $s->nama_lengkap }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="p-3 text-gray-500 text-center">Belum ada siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded shadow mb-6">
            <div class="p-4 border-b flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        📅 Periode Angkatan
                    </h2>
                    <p class="text-sm text-gray-500">Atur tanggal mulai dan selesai angkatan ini</p>
                </div>
            </div>

            <form method="POST" action="{{ route('kurikulum.updatePeriode', $angkatan) }}"
                class="p-4 flex gap-4 items-end">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-sm text-gray-700">Mulai</label>
                    <input type="date" name="mulai" value="{{ $angkatan->mulai }}" class="border rounded px-3 py-1">
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Selesai</label>
                    <input type="date" name="selesai" value="{{ $angkatan->selesai }}" class="border rounded px-3 py-1">
                </div>
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            </form>
        </div>


        {{-- --- Bagian mata pelajaran --- --}}
        <div class="bg-white shadow rounded-lg p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">📘 Mata Pelajaran</h2>
                <p class="text-sm text-gray-600">
                    Total Jam (pivot): <b>{{ $totalJamPivot }}</b> jam
                    @if (!is_null($totalJamManual))
                        | Manual: <b>{{ $totalJamManual }}</b> jam
                    @endif
                </p>
            </div>

            {{-- Form Tambah Mapel --}}
            <form class="flex flex-wrap gap-3 items-end mb-6" method="POST"
                action="{{ route('kurikulum.attachMapel', $angkatan) }}">
                @csrf
                <div>
                    <label class="block text-sm text-gray-600">Mata Pelajaran</label>
                    <select name="mata_pelajaran_id" class="border rounded px-3 py-2">
                        @foreach ($semuaMapel as $m)
                            <option value="{{ $m->id }}">{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600">Durasi (Jam)</label>
                    <input type="number" name="durasi_jam" class="border rounded px-3 py-2 w-24" min="0"
                        value="0">
                </div>
                <div>
                    <label class="block text-sm text-gray-600">Urutan</label>
                    <input type="number" name="urutan" class="border rounded px-3 py-2 w-20" min="0"
                        value="0">
                </div>
                <button class="px-4 py-2 border bg-blue-600 text-blue rounded hover:bg-blue-700">Tambah</button>
            </form>

            {{-- Tabel Mata Pelajaran --}}
            <div class="overflow-x-auto">
                <table class="min-w-full border">
                    <thead class="bg-gray-50 text-sm">
                        <tr>
                            <th class="px-3 py-2 border">#</th>
                            <th class="px-3 py-2 border text-left">Nama</th>
                            <th class="px-3 py-2 border text-center">Durasi</th>
                            <th class="px-3 py-2 border text-center">Urutan</th>
                            <th class="px-3 py-2 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($angkatan->mataPelajarans as $i => $m)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 border text-center">{{ $i + 1 }}</td>
                                <td class="px-3 py-2 border">{{ $m->nama }}</td>
                                <td class="px-3 py-2 border text-center">
                                    <form method="POST" action="{{ route('kurikulum.updateMapel', [$angkatan, $m]) }}"
                                        class="flex gap-2 justify-center">
                                        @csrf @method('PATCH')
                                        <input type="number" name="durasi_jam"
                                            class="border rounded px-2 py-1 w-20 text-center"
                                            value="{{ $m->pivot->durasi_jam }}" min="0">
                                </td>
                                <td class="px-3 py-2 border text-center">
                                    <input type="number" name="urutan" class="border rounded px-2 py-1 w-16 text-center"
                                        value="{{ $m->pivot->urutan }}" min="0">
                                </td>
                                <td class="px-3 py-2 border text-center">
                                    <button
                                        class="px-2 py-1 bg-gray-800 text-white rounded text-xs hover:bg-gray-700">Simpan</button>
                                    </form>
                                    <form method="POST" action="{{ route('kurikulum.detachMapel', [$angkatan, $m]) }}"
                                        onsubmit="return confirm('Hapus mapel dari angkatan ini?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button
                                            class="px-2 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-3 text-gray-500 text-center">Belum ada mata pelajaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- --- Bagian Materi / Silabus --- --}}
            <div class="bg-white shadow rounded-lg p-5 mt-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">📂 Materi & Silabus</h2>
                    <p class="text-sm text-gray-600">Upload file PDF, Excel, atau Gambar untuk setiap mata pelajaran</p>
                </div>

                {{-- Upload Form --}}
                <form method="POST" action="{{ route('kurikulum.uploadFile', $angkatan) }}" enctype="multipart/form-data"
                    class="flex flex-wrap gap-3 items-end mb-6">
                    @csrf
                    <div>
                        <label class="block text-sm text-gray-600">Mata Pelajaran</label>
                        <select name="mata_pelajaran_id" class="border rounded px-3 py-2">
                            <option value="">Umum (Tanpa mapel)</option>
                            @foreach ($angkatan->mataPelajarans as $m)
                                <option value="{{ $m->id }}">{{ $m->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Pilih File</label>
                        <input type="file" name="file" class="border rounded px-3 py-2 w-64" required>
                    </div>
                    <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Upload</button>
                </form>

                {{-- Daftar File --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full border text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 border">#</th>
                                <th class="px-3 py-2 border text-left">Nama File</th>
                                <th class="px-3 py-2 border text-left">Mata Pelajaran</th>
                                <th class="px-3 py-2 border text-center">Tipe</th>
                                <th class="px-3 py-2 border text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($angkatan->files as $i => $f)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 border text-center">{{ $i + 1 }}</td>
                                    <td class="px-3 py-2 border">{{ $f->nama }}</td>
                                    <td class="px-3 py-2 border">{{ $f->mataPelajaran->nama ?? '-' }}</td>
                                    <td class="px-3 py-2 border text-center uppercase">{{ $f->tipe }}</td>
                                    <td class="px-3 py-2 border text-center space-x-2">
                                        <a href="{{ $f->url }}" target="_blank"
                                            class="px-2 py-1 bg-indigo-600 text-white rounded text-xs hover:bg-indigo-700">
                                            View
                                        </a>
                                        <a href="{{ $f->url }}" target="_blank"
                                            class="px-2 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">
                                            Download
                                        </a>
                                        @if (auth()->user()->isAdmin())
                                            <form action="{{ route('kurikulum.deleteFile', [$angkatan, $f]) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Hapus file ini?')">
                                                @csrf @method('DELETE')
                                                <button
                                                    class="px-2 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">Hapus</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center p-3 text-gray-500">Belum ada file diunggah.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
