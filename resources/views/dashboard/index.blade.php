<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LPK Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-gray-900">LPK Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">
                            Welcome, {{ auth()->user()->name }}
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ auth()->user()->isAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ auth()->user()->isAdmin() ? 'Admin' : 'User' }}
                            </span>
                        </span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-sign-out-alt mr-1"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Siswa</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ $totalCalonSiswa }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-database text-green-600 text-2xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Database</dt>
                                        <dd class="text-lg font-medium text-gray-900">PostgreSQL</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clock text-orange-600 text-2xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Last Updated</dt>
                                        <dd class="text-lg font-medium text-gray-900">
                                            {{ $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->timezone('Asia/Jakarta')->format('H:i') : now()->timezone('Asia/Jakarta')->format('H:i') }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Section -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-4 py-5 sm:p-6">
                        <form method="GET" action="{{ route('dashboard') }}" class="space-y-4">
                            {{-- Filter Angkatan --}}
                            <div>
                                <label class="text-sm text-gray-600 block mb-1">Filter Angkatan</label>
                                <select name="filter_angkatan" class="border rounded px-3 py-2">
                                    <option value="">Semua Angkatan</option>
                                    @foreach ($angkatans as $a)
                                        <option value="{{ $a->id }}"
                                            {{ request('filter_angkatan') == $a->id ? 'selected' : '' }}>
                                            {{ $a->kode }} — {{ $a->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Optional prefix filter --}}
                            <div>
                                <label class="text-sm text-gray-600 block mb-1">Prefix Nomor</label>
                                <input type="text" name="prefix" value="{{ request('prefix') }}"
                                    class="border rounded px-3 py-2 w-24" placeholder="35">
                            </div>

                            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Terapkan</button>
                            <a href="{{ route('dashboard') }}" class="text-gray-600 ml-2 hover:underline">Reset</a>

                            <!-- Search Bar -->
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari
                                        Calon Siswa</label>
                                    <input type="text" name="search" id="search" value="{{ $search }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Nama, sekolah, kontak, pengalaman...">
                                </div>
                                <div class="flex items-end">
                                    <button type="submit"
                                        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <i class="fas fa-search mr-2"></i>Cari
                                    </button>
                                </div>
                            </div>

                            <!-- Filter Dropdowns -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Filter Usia -->
                                <div>
                                    <label for="filter_usia" class="block text-sm font-medium text-gray-700 mb-2">Filter
                                        Usia</label>
                                    <select name="filter_usia" id="filter_usia"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Semua Usia</option>
                                        <option value="17-20" {{ $filterUsia === '17-20' ? 'selected' : '' }}>17-20
                                            tahun</option>
                                        <option value="21-25" {{ $filterUsia === '21-25' ? 'selected' : '' }}>21-25
                                            tahun</option>
                                        <option value="26-30" {{ $filterUsia === '26-30' ? 'selected' : '' }}>26-30
                                            tahun</option>
                                        <option value="31+" {{ $filterUsia === '31+' ? 'selected' : '' }}>31+ tahun
                                        </option>
                                    </select>
                                </div>

                                <!-- Filter Asal Daerah -->
                                <div>
                                    <label for="filter_daerah"
                                        class="block text-sm font-medium text-gray-700 mb-2">Filter Asal Daerah</label>
                                    <select name="filter_daerah" id="filter_daerah"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Semua Daerah</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province }}"
                                                {{ $filterDaerah === $province ? 'selected' : '' }}>
                                                {{ $province }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Job -->
                            <div>
                                <label for="filter_job" class="block text-sm font-medium text-gray-700 mb-2">Filter
                                    Job</label>
                                <select name="filter_job" id="filter_job"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Semua Job</option>
                                    @foreach ($availableJobs as $job)
                                        <option value="{{ $job }}"
                                            {{ $filterJob === $job ? 'selected' : '' }}>
                                            {{ $job }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                    </div>

                    <!-- Clear Filters Button -->
                    @if ($search || $filterUsia || $filterDaerah || $filterJob)
                        <div class="flex justify-end">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-times mr-2"></i>Clear Filters
                            </a>
                        </div>
                    @endif
                    </form>
                </div>
            </div>

            <!-- Calon Siswa Table -->
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Data Siswa LPK</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Daftar siswa yang mendaftar ke LPK
                            </p>
                        </div>
                        @if ($isAdmin)
                            <a href="{{ route('calon-siswa.create') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i>Tambah Siswa
                            </a>
                        @endif
                    </div>
                </div>
                <div class="overflow-x-auto relative">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nomor Peserta</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Lengkap</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    TTL</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Umur</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    TB/BB</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Asal Sekolah</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kontak</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Orang Tua</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nomor Orang Tua</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Alamat Lengkap</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pengalaman Berlayar</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Job</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky right-0 bg-white shadow-md z-20">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($calonSiswas as $index => $siswa)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $calonSiswas->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $siswa->nomor_peserta ?? 'Belum ada' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button type="button"
                                            class="open-siswa-modal text-blue-600 hover:underline font-semibold"
                                            data-id="{{ $siswa->id }}">
                                            {{ $siswa->nama_lengkap }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d-m-Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $siswa->umur }} tahun
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $siswa->tinggi_badan }}cm / {{ $siswa->berat_badan }}kg
                                        @if ($siswa->bmi)
                                            <div class="text-xs text-gray-500">BMI: {{ $siswa->bmi }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $siswa->asal_sekolah }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $siswa->no_kontak }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if (preg_match('/Ayah: (.*) \| Ibu: (.*)/', $siswa->nama_orang_tua, $matches))
                                            <div>Ayah: {{ $matches[1] }}</div>
                                            <div>Ibu: {{ $matches[2] }}</div>
                                        @else
                                            {{ $siswa->nama_orang_tua }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $siswa->nomor_orang_tua ?? '-' }}</td>
                                    @php
                                        // pakai accessor dari model CalonSiswa
                                        $alamatText = $siswa->alamat_teks;
                                    @endphp
                                    <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                                        <div class="truncate" title="{{ $alamatText ?? '-' }}">
                                            {{ \Illuminate\Support\Str::limit($alamatText ?? '-', 50) }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $siswa->pengalaman_label ?? ($siswa->pengalaman_berlayar ?? '-') }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if ($siswa->job)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $siswa->job === 'Cook'
                                                    ? 'bg-red-100 text-red-800'
                                                    : ($siswa->job === 'Deck'
                                                        ? 'bg-blue-100 text-blue-800'
                                                        : ($siswa->job === 'Engine'
                                                            ? 'bg-green-100 text-green-800'
                                                            : 'bg-gray-100 text-gray-800')) }}">
                                                {{ $siswa->job }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium sticky right-0 bg-white shadow-md z-10">
                                        @if ($isAdmin)
                                            <a href="{{ route('calon-siswa.edit', $siswa->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('records.manage', $siswa->id) }}"
                                                class="text-blue-600 hover:text-blue-900 mr-3" title="Record & Nilai">
                                                <i class="fas fa-book-open"></i>
                                            </a>
                                            <form action="{{ route('calon-siswa.destroy', $siswa->id) }}"
                                                method="POST" class="inline delete-form"
                                                onsubmit="return confirm('Yakin ingin menghapus data siswa ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="text-blue-600 hover:text-blue-900" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="px-6 py-4 text-center text-sm text-gray-500">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Belum ada data calon siswa. @if ($isAdmin)
                                            Silakan tambah data baru.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($calonSiswas->hasPages())
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{ $calonSiswas->appends(request()->query())->links() }}
                    </div>
                @endif
                {{-- Modal Detail Calon Siswa --}}
                <div id="siswaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
                    <div class="bg-white w-11/12 md:w-3/4 lg:w-1/2 rounded-xl shadow-xl relative">
                        <button id="siswaModalClose"
                            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl"
                            aria-label="Close">&times;</button>

                        <div class="p-6">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 bg-gray-100 rounded overflow-hidden" id="mPhotoWrap">
                                    <img id="mPhoto" alt="Foto siswa" class="w-16 h-16 object-cover hidden">
                                    <div id="mPhotoPh"
                                        class="w-16 h-16 flex items-center justify-center text-gray-400">
                                        <i class="fa-solid fa-user text-2xl"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 id="mNama" class="text-lg font-semibold">-</h3>
                                    <p class="text-sm text-gray-500">
                                        <span id="mNomor">-</span> • <span id="mUsia">-</span> th
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div class="space-y-1">
                                    <div><span class="text-gray-500">Tempat lahir:</span> <span
                                            id="mTempat">-</span></div>
                                    <div><span class="text-gray-500">Tanggal lahir:</span> <span
                                            id="mTgl">-</span></div>
                                    <div><span class="text-gray-500">Tinggi/Berat:</span> <span
                                            id="mTbBb">-</span></div>
                                    <div><span class="text-gray-500">BMI:</span> <span id="mBmi">-</span></div>
                                    <div><span class="text-gray-500">Kontak:</span> <span id="mKontak">-</span>
                                    </div>
                                    <div><span class="text-gray-500">Orang tua:</span> <span id="mOrtu">-</span>
                                    </div>
                                    <div><span class="text-gray-500">Nomor orang tua:</span> <span id="mNomorOrtu">-</span>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <div><span class="text-gray-500">Job:</span> <span id="mJob">-</span></div>
                                    <div><span class="text-gray-500">Asal sekolah:</span> <span
                                            id="mSekolah">-</span></div>
                                    <div><span class="text-gray-500">Pengalaman:</span> <span
                                            id="mPengalaman">-</span></div>
                                    <div><span class="text-gray-500">Status record:</span> <span
                                            id="mStatus">-</span></div>
                                    <div class="text-gray-500">Alamat:</div>
                                    <div id="mAlamat" class="text-gray-700"></div>
                                </div>
                            </div>

                            <div class="mt-5">
                                <h4 class="font-semibold mb-2">Nilai</h4>
                                <div id="mNilaiWrap" class="overflow-hidden rounded border border-gray-200">
                                    <table class="min-w-full text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="text-left px-3 py-2">Mata Pelajaran</th>
                                                <th class="text-left px-3 py-2">Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody id="mNilaiBody">
                                            <tr>
                                                <td class="px-3 py-2 text-gray-500" colspan="2">Tidak ada nilai.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <p id="mUpdated" class="mt-2 text-xs text-gray-400"></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @if ($isAdmin)
                            <a href="{{ route('calon-siswa.create') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Siswa
                            </a>
                            <a href="{{ route('pelajaran.index') }}"
                                class="inline-flex items-center px-4 py-2 border text-sm rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-book mr-2"></i> Kelola Mata Pelajaran
                            </a>
                            <button
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-download mr-2"></i>
                                Export Data
                            </button>
                            <a href="{{ route('kurikulum.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-book-open mr-2"></i>Kurikulum
                            </a>
                            <button
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-cog mr-2"></i>
                                Settings
                            </button>
                        @endif
                        <a href="{{ route('reports.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Reports
                        </a>
                    </div>
                </div>
            </div>
    </div>
    </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('LPK Dashboard loaded successfully!');
            console.log('Connected to PostgreSQL database');
        });
    </script>
    <script>
        (function() {
            const modal = document.getElementById('siswaModal');
            const closeBtn = document.getElementById('siswaModalClose');

            // elemen yang akan diisi
            const el = {
                photo: document.getElementById('mPhoto'),
                photoPh: document.getElementById('mPhotoPh'),
                nama: document.getElementById('mNama'),
                nomor: document.getElementById('mNomor'),
                usia: document.getElementById('mUsia'),
                tempat: document.getElementById('mTempat'),
                tgl: document.getElementById('mTgl'),
                tbBb: document.getElementById('mTbBb'),
                bmi: document.getElementById('mBmi'),
                kontak: document.getElementById('mKontak'),
                ortu: document.getElementById('mOrtu'),
                nomorOrtu: document.getElementById('mNomorOrtu'),
                job: document.getElementById('mJob'),
                sekolah: document.getElementById('mSekolah'),
                pengalaman: document.getElementById('mPengalaman'),
                status: document.getElementById('mStatus'),
                alamat: document.getElementById('mAlamat'),
                nilaiBody: document.getElementById('mNilaiBody'),
                updated: document.getElementById('mUpdated'),
            };

            function show() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function hide() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            closeBtn.addEventListener('click', hide);
            modal.addEventListener('click', (e) => {
                if (e.target === modal) hide();
            });

            // klik di nama
            document.querySelectorAll('.open-siswa-modal').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const id = btn.dataset.id;
                    show();
                    // loading awal
                    el.nama.textContent = 'Loading...';
                    el.nilaiBody.innerHTML =
                        `<tr><td class="px-3 py-2 text-gray-500" colspan="2">Memuat...</td></tr>`;
                    el.photo.classList.add('hidden');
                    el.photoPh.classList.remove('hidden');

                    try {
                        const res = await fetch(`{{ route('calon-siswa.summary', ':id') }}`
                            .replace(':id', id));
                        if (!res.ok) throw new Error('HTTP ' + res.status);
                        const d = await res.json();

                        // header
                        el.nama.textContent = d.nama_lengkap ?? '-';
                        el.nomor.textContent = d.nomor_peserta ?? '-';
                        el.usia.textContent = d.usia ?? '-';

                        // info kiri/kanan
                        el.tempat.textContent = d.tempat_lahir ?? '-';
                        el.tgl.textContent = d.tanggal_lahir ?? '-';
                        el.tbBb.textContent = (d.tinggi_badan && d.berat_badan) ?
                            `${d.tinggi_badan} cm / ${d.berat_badan} kg` : '-';
                        el.bmi.textContent = d.bmi ?? '-';
                        el.kontak.textContent = d.no_kontak ?? '-';
                        el.ortu.textContent = d.nama_orang_tua ?? '-';
                        el.nomorOrtu.textContent = d.nomor_orang_tua ?? '-';
                        el.job.textContent = d.job ?? '-';
                        el.sekolah.textContent = d.asal_sekolah ?? '-';
                        if (d.pengalaman) {
                            const parts = d.pengalaman.split('|');
                            const lokasi = parts[0] ?
                                parts[0].charAt(0).toUpperCase() + parts[0].slice(1) :
                                '-';
                            const jenis = parts[1] ?
                                parts[1]
                                .replace(/_/g, ' ') // ubah _ jadi spasi
                                .split(' ') // pisah jadi array kata
                                .map(w => w.charAt(0).toUpperCase() + w.slice(
                                1)) // kapital tiap kata
                                .join(' ') // gabung lagi
                                :
                                '-';
                            const durasi = parts[2] ? parts[2] + ' bulan' : '-';
                            el.pengalaman.textContent = `${lokasi} — ${jenis} (${durasi})`;
                        } else {
                            el.pengalaman.textContent = '-';
                        }


                        el.status.textContent = d.record?.status ? d.record.status.replace('_',
                            ' ') : '-';

                        // alamat
                        const a = d.alamat ?? {};
                        el.alamat.textContent = a.teks ?? [a.rumah, a.kelurahan, a.kecamatan, a
                                .kota, a.provinsi, a.zip
                            ].filter(
                                Boolean).join(', ') ??
                            '-';

                        // foto
                        if (d.record?.photo_url) {
                            el.photo.src = d.record.photo_url;
                            el.photo.classList.remove('hidden');
                            el.photoPh.classList.add('hidden');
                        }

                        // nilai
                        const list = Array.isArray(d.nilai) ? d.nilai : [];
                        if (list.length) {
                            el.nilaiBody.innerHTML = list.map(n => `
                    <tr class="odd:bg-white even:bg-gray-50">
                      <td class="px-3 py-2">${n.pelajaran ?? '-'}</td>
                      <td class="px-3 py-2 font-medium">${n.nilai ?? '-'}</td>
                    </tr>
                  `).join('');
                        } else {
                            el.nilaiBody.innerHTML =
                                `<tr><td class="px-3 py-2 text-gray-500" colspan="2">Tidak ada nilai.</td></tr>`;
                        }

                        el.updated.textContent = d.record?.updated_at ? ('Diupdate: ' + d.record
                            .updated_at) : '';
                    } catch (err) {
                        el.nama.textContent = 'Gagal memuat';
                        el.nilaiBody.innerHTML =
                            `<tr><td class="px-3 py-2 text-red-600" colspan="2">Terjadi kesalahan saat mengambil data.</td></tr>`;
                        console.error(err);
                    }
                });
            });
        })();
    </script>

</body>

</html>
