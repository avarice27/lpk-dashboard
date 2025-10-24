<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record & Nilai - {{ $siswa->nama_lengkap }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="h-16 flex items-center justify-between">
                    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <h1 class="text-lg font-semibold">Record & Nilai — {{ $siswa->nama_lengkap }}</h1>
                    <div></div>
                </div>
            </div>
        </nav>

        <main class="max-w-6xl mx-auto py-6 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('records.save', $siswa->id) }}" enctype="multipart/form-data"
                class="bg-white shadow rounded p-6 space-y-6">
                @csrf

                {{-- Header Siswa --}}
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <div class="text-sm text-gray-600">Nomor Peserta</div>
                        <div class="text-lg font-semibold mb-2">{{ $siswa->nomor_peserta ?? '-' }}</div>

                        <div class="text-sm text-gray-600">Alamat</div>
                        <div class="text-gray-900">
                            {{ optional($siswa->alamat)->rumah }}
                            @php
                                $a = $siswa->alamat;
                                $full = collect([
                                    $a?->kelurahan,
                                    $a?->kecamatan,
                                    $a?->kota,
                                    $a?->provinsi,
                                    $a?->zip_code,
                                ])
                                    ->filter()
                                    ->implode(', ');
                            @endphp
                            @if ($full)
                                <div class="text-gray-500 text-sm">{{ $full }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- Foto --}}
                    <div class="flex gap-4 items-start">
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Foto</div>
                            @if (optional($record)->photo_path)
                                <img src="{{ asset('storage/' . $record->photo_path) }}"
                                    class="w-28 h-28 object-cover rounded-lg border" alt="Foto">
                            @else
                                <div
                                    class="w-28 h-28 bg-gray-100 rounded-lg border flex items-center justify-center text-gray-400">
                                    No Photo</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm text-gray-700 mb-1">Ganti / Upload Foto</label>
                            <input type="file" name="photo" accept="image/*"
                                class="w-full px-3 py-2 border rounded">
                            @error('photo')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full border rounded px-3 py-2">
                            <option value="stand_by" @selected(old('status', $record->status ?? '')==='stand_by')>Stand By</option>
                            <option value="on_job" @selected(old('status', $record->status ?? '')==='on_job')>On Job</option>
                        </select>

                    </div>
                </div>

                {{-- Nilai Pelajaran --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-md font-semibold">Nilai Pelajaran</h2>
                        <a href="{{ route('records.manage', $siswa->id) }}"
                            class="text-sm text-gray-500 hover:text-gray-700">
                            Refresh
                        </a>
                    </div>

                    <div class="overflow-x-auto border rounded">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Mata
                                        Pelajaran</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nilai
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($pelajaran as $i => $p)
                                    @php
                                        $existing = $nilaiMap->get($p->id)?->nilai ?? '';
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm text-gray-600">{{ $i + 1 }}</td>
                                        <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ $p->nama }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{-- jika pakai nilai huruf A-D, ganti input text dengan select di bawah --}}
                                            <input type="text" name="scores[{{ $p->id }}]"
                                                value="{{ old('scores.' . $p->id, $existing) }}"
                                                class="w-32 px-3 py-2 border rounded" placeholder="A/B/C/D">
                                            {{-- contoh select:
                    <select name="scores[{{ $p->id }}]" class="w-32 px-3 py-2 border rounded">
                      <option value=""></option>
                      @foreach (['A', 'B', 'C', 'D'] as $g)
                        <option value="{{ $g }}" {{ $existing===$g?'selected':'' }}>{{ $g }}</option>
                      @endforeach
                    </select>
                    --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 border rounded text-gray-700 bg-white hover:bg-gray-50">Batal</a>
                    <button class="px-4 py-2 rounded text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                </div>
            </form>
        </main>
    </div>
</body>

</html>
