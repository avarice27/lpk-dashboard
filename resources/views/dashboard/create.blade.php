<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa - LPK Harini Duta Ayu Dashboard</title>
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
                        <h1 class="text-xl font-semibold text-gray-900">Tambah Siswa</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">
                            {{ auth()->user()->name }}
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ auth()->user()->isAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ auth()->user()->isAdmin() ? 'Admin' : 'User' }}
                            </span>
                        </span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
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
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">Tambah Data Siswa Baru</h3>
                        @if (session('success'))
<div id="notifSuccess"
     class="fixed top-5 right-5 bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center space-x-2 z-50 animate-slide-in">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<style>
@keyframes slide-in {
  from {opacity: 0; transform: translateY(-10px);}
  to {opacity: 1; transform: translateY(0);}
}
.animate-slide-in { animation: slide-in 0.3s ease-out; }
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const notif = document.getElementById("notifSuccess");
  if (notif) {
    setTimeout(() => {
      notif.classList.add("opacity-0", "transition", "duration-500");
      setTimeout(() => notif.remove(), 500);
    }, 4000);
  }
});
</script>

                        <form method="POST" action="{{ route('calon-siswa.store') }}" class="space-y-6">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="nomor_peserta" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nomor Peserta <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="nomor_peserta" id="nomor_peserta"
                                           value="{{ old('nomor_peserta') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="Contoh: 3501"
                                           min="1" max="9999" required>
                                    @error('nomor_peserta')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap"
                                           value="{{ old('nama_lengkap') }}"
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
                                           value="{{ old('tanggal_lahir') }}"
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
                                           value="{{ old('tempat_lahir') }}"
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
                                           value="{{ old('tinggi_badan') }}"
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
                                           value="{{ old('berat_badan') }}"
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
                                           value="{{ old('asal_sekolah') }}"
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
                                           value="{{ old('no_kontak') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                           required>
                                    @error('no_kontak')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat/Jalan</label>
                                        <input type="text" name="rumah" value="{{ old('rumah') }}" class="w-full px-3 py-2 border rounded-md">
                                        @error('rumah') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelurahan</label>
                                        <input type="text" name="kelurahan" value="{{ old('kelurahan') }}" class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                                        <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Kota</label>
                                        <input type="text" name="kota" value="{{ old('kota') }}" class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                                        <input type="text" name="provinsi" value="{{ old('provinsi') }}" class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">ZIP</label>
                                        <input type="text" name="zip_code" value="{{ old('zip_code') }}" class="w-full px-3 py-2 border rounded-md">
                                    </div>
                                </div>


                                <div>
                                    <label for="nama_ayah" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Ayah <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_ayah" id="nama_ayah"
                                           value="{{ old('nama_ayah') }}"
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
                                           value="{{ old('nama_ibu') }}"
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
                                           value="{{ old('nomor_orang_tua') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="Contoh: 08123456789">
                                    @error('nomor_orang_tua')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- <div>
                                    <label for="pengalaman_berlayar" class="block text-sm font-medium text-gray-700 mb-2">
                                        Pengalaman Berlayar <span class="text-red-500">*</span>
                                    </label>
                                    <select name="pengalaman_berlayar" id="pengalaman_berlayar"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                            required>
                                        <option value="">Pilih pengalaman</option>
                                        <option value="Non pengalaman" {{ old('pengalaman_berlayar') === 'Non pengalaman' ? 'selected' : '' }}>Non pengalaman</option>
                                        <option value="Lokal ( -6 Bulan )" {{ old('pengalaman_berlayar') === 'Lokal ( -6 Bulan )' ? 'selected' : '' }}>Lokal ( -6 Bulan )</option>
                                        <option value="Purse seine lokal ( 6-12 bln )" {{ old('pengalaman_berlayar') === 'Purse seine lokal ( 6-12 bln )' ? 'selected' : '' }}>Purse seine lokal ( 6-12 bln )</option>
                                        <option value="Long Line (6-12 Bulan)" {{ old('pengalaman_berlayar') === 'Long Line (6-12 Bulan)' ? 'selected' : '' }}>Long Line (6-12 Bulan)</option>
                                        <option value="Pole & Line (6-12 Bulan)" {{ old('pengalaman_berlayar') === 'Pole & Line (6-12 Bulan)' ? 'selected' : '' }}>Pole & Line (6-12 Bulan)</option>
                                        <option value="Diatas 12 Bulan" {{ old('pengalaman_berlayar') === 'Diatas 12 Bulan' ? 'selected' : '' }}>Diatas 12 Bulan</option>
                                        <option value="Long line Taiwan 24 Bulan" {{ old('pengalaman_berlayar') === 'Long line Taiwan 24 Bulan' ? 'selected' : '' }}>Long line Taiwan 24 Bulan</option>
                                    </select>
                                    @error('pengalaman_berlayar')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div> --}}
                                {{-- ========== PENGALAMAN BERLAYAR (UI 3 field) ========== --}}
<div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
    {{-- Lokasi --}}
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">
        Lokasi Pengalaman <span class="text-red-500">*</span>
      </label>
      <select name="pengalaman_lokasi" class="w-full px-3 py-2 border rounded" required>
        @php $lokasiOpts = ['lokal' => 'Lokal','internasional' => 'Internasional','tidak ada pengalaman' => 'Tidak ada pengalaman']; @endphp
        <option value="">Pilih lokasi</option>
        @foreach($lokasiOpts as $val => $label)
          <option value="{{ $val }}" @selected(old('pengalaman_lokasi')===$val)>{{ $label }}</option>
        @endforeach
      </select>
      @error('pengalaman_lokasi')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Jenis --}}
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">
        Jenis Penangkapan <span class="text-red-500">*</span>
      </label>
      <select name="pengalaman_jenis" class="w-full px-3 py-2 border rounded" required>
        @php
          $jenisOpts = [
            'purse_seine' => 'Purse Seine',
            'long_line'  => 'Long Line',
            'pole_line'  => 'Pole & Line',
            'handline'   => 'Handline',
            'trawl'      => 'Trawl',
            'lainnya'    => 'Lainnya',
            'tidak ada'  => 'Tidak ada',
          ];
        @endphp
        <option value="">Pilih jenis</option>
        @foreach($jenisOpts as $val => $label)
          <option value="{{ $val }}" @selected(old('pengalaman_jenis')===$val)>{{ $label }}</option>
        @endforeach
      </select>
      @error('pengalaman_jenis')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Durasi (bulan) --}}
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">
        Durasi (bulan) <span class="text-red-500">*</span>
      </label>
      <select name="pengalaman_durasi_bulan" class="w-full px-3 py-2 border rounded" required>
        <option value="">Pilih durasi</option>
        @for($i=0;$i<=24;$i++)
          <option value="{{ $i }}" @selected(old('pengalaman_durasi_bulan')==$i)>{{ $i }} bulan</option>
        @endfor
      </select>
      @error('pengalaman_durasi_bulan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
  </div>

                                <div>
                                    <label for="job" class="block text-sm font-medium text-gray-700 mb-2">
                                        Job <span class="text-red-500">*</span>
                                    </label>
                                    <select name="job" id="job"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                            required>
                                        <option value="">Pilih job</option>
                                        <option value="Cook" {{ old('job') === 'Cook' ? 'selected' : '' }}>Cook</option>
                                        <option value="Deck" {{ old('job') === 'Deck' ? 'selected' : '' }}>Deck</option>
                                        <option value="Engine" {{ old('job') === 'Engine' ? 'selected' : '' }}>Engine</option>
                                        <option value="No Job" {{ old('job') === 'No Job' ? 'selected' : '' }}>No Job</option>
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
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('catatan') }}</textarea>
                                    @error('catatan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                                <a href="{{ route('dashboard') }}"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <i class="fas fa-times mr-2"></i>Batal
                                </a>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <i class="fas fa-save mr-2"></i>Simpan Data
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
