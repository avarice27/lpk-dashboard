<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Edit Pelajaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <div class="max-w-lg mx-auto p-6">
        <a href="{{ route('pelajaran.index') }}" class="text-gray-600 hover:underline">← Kembali</a>
        <h1 class="text-xl font-semibold mt-3 mb-4">Edit Mata Pelajaran</h1>

        <form method="post" action="{{ route('pelajaran.update', $pelajaran) }}" class="bg-white border rounded p-4">
            @csrf @method('PUT')
            <label class="block text-sm mb-1">Nama</label>
            <input name="nama" value="{{ old('nama', $pelajaran->nama) }}" class="w-full px-3 py-2 border rounded"
                required>
            @error('nama')
                <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
            @enderror

            <div class="mt-4 flex justify-end gap-2">
                <a href="{{ route('pelajaran.index') }}" class="px-4 py-2 border rounded">Batal</a>
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
</body>

</html>
