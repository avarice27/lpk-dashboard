<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Calon Siswa - LPK Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-xl font-semibold text-gray-900">Edit Siswa</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">
                            {{ auth()->user()->name }}
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

        <main class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">Edit Data Calon Siswa</h3>

                        <form method="POST" action="{{ route('calon-siswa.update', $siswa->id) }}" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="nomor_peserta" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nomor Peserta <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="nomor_peserta" id="nomor_peserta"
                                        value="{{ old('nomor_peserta', $siswa->nomor_peserta) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Contoh: 3501" min="1" max="9999" required>
                                    @error('nomor_peserta')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap"
                                        value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                    @error('nama_lengkap')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Lahir <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                        value="{{ old('tanggal_lahir', $siswa->tanggal_lahir->format('Y-m-d')) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                    @error('tanggal_lahir')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tempat Lahir <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="tempat_lahir" id="tempat_lahir"
                                        value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Contoh: Jakarta"
                                        required>
                                    @error('tempat_lahir')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tinggi_badan" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tinggi Badan (cm) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="tinggi_badan" id="tinggi_badan"
                                        value="{{ old('tinggi_badan', $siswa->tinggi_badan) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        min="100" max="250" required>
                                    @error('tinggi_badan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="berat_badan" class="block text-sm font-medium text-gray-700 mb-2">
                                        Berat Badan (kg) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="berat_badan" id="berat_badan"
                                        value="{{ old('berat_badan', $siswa->berat_badan) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        min="30" max="150" required>
                                    @error('berat_badan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="asal_sekolah" class="block text-sm font-medium text-gray-700 mb-2">
                                        Asal Sekolah <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="asal_sekolah" id="asal_sekolah"
                                        value="{{ old('asal_sekolah', $siswa->asal_sekolah) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                    @error('asal_sekolah')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="no_kontak" class="block text-sm font-medium text-gray-700 mb-2">
                                        No. Kontak <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="no_kontak" id="no_kontak"
                                        value="{{ old('no_kontak', $siswa->no_kontak) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                    @error('no_kontak')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                @php $alamat = $siswa->alamat; @endphp
                                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2">Alamat/Jalan</label>
                                        <input type="text" name="rumah"
                                            value="{{ old('rumah', $alamat->rumah ?? '') }}"
                                            class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelurahan</label>
                                        <input type="text" name="kelurahan"
                                            value="{{ old('kelurahan', $alamat->kelurahan ?? '') }}"
                                            class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                                        <input type="text" name="kecamatan"
                                            value="{{ old('kecamatan', $alamat->kecamatan ?? '') }}"
                                            class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Kota</label>
                                        <input type="text" name="kota"
                                            value="{{ old('kota', $alamat->kota ?? '') }}"
                                            class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                                        <input type="text" name="provinsi"
                                            value="{{ old('provinsi', $alamat->provinsi ?? '') }}"
                                            class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">ZIP</label>
                                        <input type="text" name="zip_code"
                                            value="{{ old('zip_code', $alamat->zip_code ?? '') }}"
                                            class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                </div>


                                <div>
                                    <label for="nama_ayah" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Ayah <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_ayah" id="nama_ayah"
                                        value="{{ old('nama_ayah', $namaAyahNow) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Contoh: Ahmad Sutrisno"
                                        required>
                                    @error('nama_ayah')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="nama_ibu" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Ibu <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_ibu" id="nama_ibu"
                                        value="{{ old('nama_ibu', $namaIbuNow) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Contoh: Siti Aminah"
                                        required>
                                    @error('nama_ibu')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="nomor_orang_tua" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nomor Orang Tua
                                    </label>
                                    <input type="text" name="nomor_orang_tua" id="nomor_orang_tua"
                                        value="{{ old('nomor_orang_tua', $siswa->nomor_orang_tua) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Contoh: 08123456789">
                                    @error('nomor_orang_tua')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="pengalaman_berlayar"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        Pengalaman Berlayar <span class="text-red-500">*</span>
                                    </label>
                                    {{-- Lokasi --}}
                                    <select name="pengalaman_lokasi" class="...">
                                        @foreach (['lokal' => 'Lokal', 'internasional' => 'Internasional', 'tidak ada pengalaman' => 'Tidak ada pengalaman'] as $val => $label)
                                            <option value="{{ $val }}" @selected($lokasiNow === $val)>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>

                                    {{-- Jenis --}}
                                    <select name="pengalaman_jenis" class="...">
                                        @foreach (['purse_seine' => 'Purse Seine', 'long_line' => 'Long Line', 'pole_line' => 'Pole & Line', 'handline' => 'Handline', 'trawl' => 'Trawl', 'tidak ada' => 'Tidak ada'] as $val => $label)
                                            <option value="{{ $val }}" @selected($jenisNow === $val)>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>

                                    {{-- Durasi (bulan) --}}
                                    <select name="pengalaman_durasi_bulan" class="...">
                                        @for ($i = 0; $i <= 24; $i++)
                                            <option value="{{ $i }}" @selected((string) $durNow === (string) $i)>
                                                {{ $i }} bulan</option>
                                        @endfor
                                    </select>


                                    <div>
                                        <label for="job" class="block text-sm font-medium text-gray-700 mb-2">
                                            Job <span class="text-red-500">*</span>
                                        </label>
                                        <select name="job" id="job"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                            required>
                                            <option value="">Pilih job</option>
                                            <option value="Cook"
                                                {{ old('job', $siswa->job) === 'Cook' ? 'selected' : '' }}>Cook
                                            </option>
                                            <option value="Deck"
                                                {{ old('job', $siswa->job) === 'Deck' ? 'selected' : '' }}>Deck
                                            </option>
                                            <option value="Engine"
                                                {{ old('job', $siswa->job) === 'Engine' ? 'selected' : '' }}>Engine
                                            </option>
                                            <option value="No Job"
                                                {{ old('job', $siswa->job) === 'No Job' ? 'selected' : '' }}>No Job
                                            </option>
                                        </select>
                                        @error('job')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                                            Catatan
                                        </label>
                                        <textarea name="catatan" id="catatan" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('catatan', $siswa->catatan) }}</textarea>
                                        @error('catatan')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                                    <!-- Tombol Batal -->
                                    <a href="{{ route('dashboard') }}"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                                        <i class="fas fa-times text-gray-500"></i>
                                        <span>Batal</span>
                                    </a>

                                    <!-- Tombol Simpan -->
                                    <button type="submit"
                                        class="inline-flex items-center gap-3 px-2 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md border border-transparent hover:bg-blue-700 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                                        <i class="fas fa-save"></i>
                                        <span>Simpan Perubahan</span>
                                    </button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
