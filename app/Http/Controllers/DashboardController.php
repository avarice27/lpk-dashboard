<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alamat;
use App\Models\CalonSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $search       = trim((string) $request->get('search'));
        $filterUsia   = $request->get('filter_usia');
        $filterDaerah = $request->get('filter_daerah'); // bisa provinsi/kota/dll
        $filterJob    = $request->get('filter_job');
        $filterAngkatan = $request->get('filter_angkatan');

        $like = $this->likeOperator();

        // preload relasi alamat
        $base = CalonSiswa::query()->with('alamat');

        // SEARCH: kolom calon_siswas + kolom alamat (rumah/kelurahan/kecamatan/kota/provinsi/zip)
        $base->when($search !== '', function ($q) use ($search, $like) {
            $needle = '%' . $search . '%';

            $q->where(function ($qq) use ($needle, $like) {
                $qq->where('nama_lengkap', $like, $needle)
                    ->orWhere('asal_sekolah', $like, $needle)
                    ->orWhere('no_kontak', $like, $needle)
                    ->orWhere('nama_orang_tua', $like, $needle)
                    ->orWhere('pengalaman_berlayar', $like, $needle)
                    ->orWhere('job', $like, $needle);
            })->orWhereHas('alamat', function ($qa) use ($needle, $like) {
                $qa->where('rumah',      $like, $needle)   // ganti ke 'jalan' jika perlu
                    ->orWhere('kelurahan', $like, $needle)
                    ->orWhere('kecamatan', $like, $needle)
                    ->orWhere('kota',      $like, $needle)
                    ->orWhere('provinsi',  $like, $needle)
                    ->orWhere('zip_code',  $like, $needle);
            });
        });

          // 🆕 FILTER ANGKATAN
        $base->when(!empty($filterAngkatan), function ($q) use ($filterAngkatan) {
        // Filter by angkatan_id directly (jika sudah ada relasi)
        $q->where('angkatan_id', $filterAngkatan);
        });

        // Optional: prefix filter based on nomor_peserta
    if ($request->filled('prefix')) {
        $prefix = preg_replace('/\D/', '', $request->prefix);
        $base->where('nomor_peserta', 'like', $prefix . '%');
    }

        // FILTER USIA
        $base->when($filterUsia, function ($q) use ($filterUsia) {
            $today = now()->startOfDay();
            $ranges = [
                '17-20' => [17, 20],
                '21-25' => [21, 25],
                '26-30' => [26, 30],
                '31+'   => [31, 120],
            ];
            if (isset($ranges[$filterUsia])) {
                [$min, $max] = $ranges[$filterUsia];
                $maxBirth = $today->copy()->subYears($min);               // termuda
                $minBirth = $today->copy()->subYears($max + 1)->addDay(); // tertua
                $q->whereBetween('tanggal_lahir', [$minBirth, $maxBirth]);
            }
        });

        // FILTER DAERAH (cari di tabel alamat)
        $base->when(!empty($filterDaerah), function ($q) use ($filterDaerah) {
            $q->whereHas('alamat', function ($qa) use ($filterDaerah) {
                $qa->whereRaw('LOWER(provinsi) = LOWER(?)', [$filterDaerah]);
            });
        });


        // FILTER JOB
        $base->when($filterJob, fn($q) => $q->where('job', $filterJob));

        // hitung total hasil setelah filter (untuk info UI)
        $filteredCount = (clone $base)->count();

        // urutkan (nomor_peserta kamu di migration STRING → pakai orderBy biasa)
        $calonSiswas = $base
            ->orderBy('nomor_peserta') // kalau integer, tetap aman
            ->paginate(10)
            ->appends($request->query());

        $users   = User::all();
        $isAdmin = Auth::check() && Auth::user()->role === 'admin';

        // total keseluruhan
        $totalCalonSiswa = CalonSiswa::count();

        // last updated
        $lastUpdated = session('lastUpdated', CalonSiswa::latest('updated_at')->value('updated_at') ?? now());

        // dropdown sumber
        $provinces     = $this->getAvailableProvinces(); // sudah dari tabel alamat
        $availableJobs = $this->getAvailableJobs();
        $angkatans = \App\Models\Angkatan::orderBy('kode')->get();

        return view('dashboard.index', compact(
            'calonSiswas',
            'users',
            'isAdmin',
            'search',
            'filterUsia',
            'filterDaerah',
            'filterJob',
            'filterAngkatan',
            'angkatans',
            'provinces',
            'availableJobs',
            'totalCalonSiswa',
            'filteredCount',
            'lastUpdated'
        ));


    }

    public function create()
    {
        return view('dashboard.create');
    }

    public function store(Request $request)
    {
        // nomor_peserta di migration kamu STRING → pakai rule string
        $data = $request->validate([
            'nomor_peserta'      => ['required', 'string', 'max:20', 'unique:calon_siswas,nomor_peserta'],
            'nama_lengkap'       => ['required', 'string', 'max:255'],
            'tanggal_lahir'      => ['required', 'date'],
            'tinggi_badan'       => ['required', 'integer', 'min:100', 'max:250'],
            'berat_badan'        => ['required', 'integer', 'min:30', 'max:150'],
            'asal_sekolah'       => ['required', 'string', 'max:255'],
            'no_kontak'          => ['required', 'string', 'max:20'],
            'nama_orang_tua'     => ['required', 'string', 'max:255'],
            // 'pengalaman_berlayar' => ['required', 'string', 'max:255'],
            'pengalaman_lokasi'       => ['required', Rule::in(['lokal','internasional', 'tidak ada pengalaman'])],
            'pengalaman_jenis'        => ['required', Rule::in(['purse_seine','long_line','pole_line','handline','trawl','lainnya'])],
            'pengalaman_durasi_bulan' => ['required','integer','min:0','max:24'],
            'job'                => ['required', Rule::in(['Cook', 'Deck', 'Engine', 'No Job'])],
            'catatan'            => ['nullable', 'string'],

            // alamat terstruktur:
            'rumah'      => ['nullable', 'string', 'max:255'], // ganti ke 'jalan' jika perlu
            'kelurahan'  => ['nullable', 'string', 'max:255'],
            'kecamatan'  => ['nullable', 'string', 'max:255'],
            'kota'       => ['nullable', 'string', 'max:255'],
            'provinsi'   => ['nullable', 'string', 'max:255'],
            'zip_code'   => ['nullable', 'string', 'max:10'],
        ]);

        $data['pengalaman_berlayar'] = implode('|', [
        $data['pengalaman_lokasi'],
        $data['pengalaman_jenis'],
        (int)$data['pengalaman_durasi_bulan'],
        ]);


        $calonSiswa = CalonSiswa::create(collect($data)->only([
            'nomor_peserta',
            'nama_lengkap',
            'tanggal_lahir',
            'tinggi_badan',
            'berat_badan',
            'asal_sekolah',
            'no_kontak',
            'nama_orang_tua',
            'pengalaman_berlayar',
            'job',
            'catatan'
        ])->toArray());

        // simpan alamat terkait
        $calonSiswa->alamat()->create(collect($data)->only([
            'rumah',
            'kelurahan',
            'kecamatan',
            'kota',
            'provinsi',
            'zip_code'
        ])->toArray());

        $lastUpdated = $calonSiswa->updated_at ?? now();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Data calon siswa berhasil ditambahkan!')
            ->with('lastUpdated', $lastUpdated);
    }

    public function edit($id)
    {
        $siswa = CalonSiswa::with('alamat')->findOrFail($id);
        // Selalu definisikan dulu
    $lokasiNow = null;
    $jenisNow  = null;
    $durNow    = null;

    // Parse "lokasi|jenis|durasi" menjadi 3 nilai (kalau kosong diisi null)
    [$lokasiNow, $jenisNow, $durNow] = array_pad(
        explode('|', (string) $siswa->pengalaman_berlayar, 3),
        3,
        null
    );
    $lokasiNow = old('pengalaman_lokasi', $lokasiNow);
    $jenisNow  = old('pengalaman_jenis',  $jenisNow);
    $durNow    = old('pengalaman_durasi_bulan', $durNow);
        return view('dashboard.edit', compact('siswa', 'lokasiNow', 'jenisNow', 'durNow'));
    }

    public function update(Request $request, $id)
    {
        $siswa = CalonSiswa::with('alamat')->findOrFail($id);

        $data = $request->validate([
            'nomor_peserta'      => [
                'required',
                'string',
                'max:20',
                Rule::unique('calon_siswas', 'nomor_peserta')->ignore($siswa->getKey())
            ],
            'nama_lengkap'       => ['required', 'string', 'max:255'],
            'tanggal_lahir'      => ['required', 'date'],
            'tinggi_badan'       => ['required', 'integer', 'min:100', 'max:250'],
            'berat_badan'        => ['required', 'integer', 'min:30', 'max:150'],
            'asal_sekolah'       => ['required', 'string', 'max:255'],
            'no_kontak'          => ['required', 'string', 'max:20'],
            'nama_orang_tua'     => ['required', 'string', 'max:255'],
            // 'pengalaman_berlayar' => ['required', 'string', 'max:255'],
            'pengalaman_lokasi'       => ['required', Rule::in(['lokal','internasional'])],
            'pengalaman_jenis'        => ['required', Rule::in(['purse_seine','long_line','pole_line','handline','trawl','tidak ada'])],
            'pengalaman_durasi_bulan' => ['required','integer','min:0','max:24'],

            'job'                => ['required', Rule::in(['Cook', 'Deck', 'Engine', 'No Job'])],
            'catatan'            => ['nullable', 'string'],

            'rumah'      => ['nullable', 'string', 'max:255'],
            'kelurahan'  => ['nullable', 'string', 'max:255'],
            'kecamatan'  => ['nullable', 'string', 'max:255'],
            'kota'       => ['nullable', 'string', 'max:255'],
            'provinsi'   => ['nullable', 'string', 'max:255'],
            'zip_code'   => ['nullable', 'string', 'max:10'],
        ]);
         // gabungkan tiga input ke 1 string
    $data['pengalaman_berlayar'] = implode('|', [
        $data['pengalaman_lokasi'],
        $data['pengalaman_jenis'],
        (int)$data['pengalaman_durasi_bulan'],
    ]);
        // update calon siswa
        $siswa->update(collect($data)->only([
            'nomor_peserta',
            'nama_lengkap',
            'tanggal_lahir',
            'tinggi_badan',
            'berat_badan',
            'asal_sekolah',
            'no_kontak',
            'nama_orang_tua',
            'pengalaman_berlayar',
            'job',
            'catatan'
        ])->toArray());

        // upsert alamat
        $alamatPayload = collect($data)->only([
            'rumah',
            'kelurahan',
            'kecamatan',
            'kota',
            'provinsi',
            'zip_code'
        ])->toArray();

        if ($siswa->alamat) {
            $siswa->alamat->update($alamatPayload);
        } else {
            $siswa->alamat()->create($alamatPayload);
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Data calon siswa berhasil diperbarui!')
            ->with('lastUpdated', $siswa->updated_at ?? now());
    }

    private function getAvailableProvinces()
    {
        // sudah ambil dari tabel alamat, bukan dari alamat_lengkap
        return Cache::remember('calon_siswas_provinces', 600, function () {
            return Alamat::whereNotNull('provinsi')
                ->distinct()
                ->orderBy('provinsi')
                ->pluck('provinsi');
        });
    }

    private function getAvailableJobs()
    {
        return Cache::remember('calon_siswas_jobs', 600, function () {
            return CalonSiswa::whereNotNull('job')
                ->distinct()
                ->orderBy('job')
                ->pluck('job');
        });
    }

    /**
     * Gunakan ILIKE untuk Postgres, LIKE untuk driver lain.
     */
    private function likeOperator(): string
    {
        return DB::connection()->getDriverName() === 'pgsql' ? 'ILIKE' : 'LIKE';
    }
}
