<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Mata Pelajaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <div class="max-w-3xl mx-auto p-6">
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:underline">← Kembali</a>
            <h1 class="text-xl font-semibold">Mata Pelajaran</h1>
        </div>

        @if (session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded">
                {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded">{{ session('error') }}</div>
        @endif

        <form method="get" class="mb-4 flex gap-2">
            <input name="q" value="{{ $q }}" placeholder="Cari pelajaran..."
                class="flex-1 px-3 py-2 border rounded">
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Cari</button>
            @if ($q)
                <a href="{{ route('pelajaran.index') }}" class="px-4 py-2 border rounded">Clear</a>
            @endif
        </form>

        <div class="bg-white border rounded p-4 mb-6">
            <h2 class="font-medium mb-3">Tambah Pelajaran</h2>
            <form method="post" action="{{ route('pelajaran.store') }}" class="flex gap-2">
                @csrf
                <input name="nama" class="flex-1 px-3 py-2 border rounded" placeholder="Nama pelajaran" required>
                <button class="px-4 py-2 bg-green-600 text-white rounded">Tambah</button>
            </form>
            @error('nama')
                <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="bg-white border rounded overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold">#</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold">Nama</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $i => $row)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $items->firstItem() + $i }}</td>
                            <td class="px-4 py-2">{{ $row->nama }}</td>
                            <td class="px-4 py-2 text-right">
                                <a href="{{ route('pelajaran.edit', $row) }}"
                                    class="text-blue-600 hover:underline mr-3">Edit</a>
                                <form method="post" action="{{ route('pelajaran.destroy', $row) }}" class="inline"
                                    onsubmit="return confirm('Hapus pelajaran ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="3">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($items->hasPages())
            <div class="mt-4">{{ $items->links() }}</div>
        @endif
    </div>
</body>

</html>
