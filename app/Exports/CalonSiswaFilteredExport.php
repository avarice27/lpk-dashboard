<?php
// app/Exports/CalonSiswaFilteredExport.php
namespace App\Exports;

use App\Models\CalonSiswa;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Excel;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\DB;

class CalonSiswaFilteredExport implements FromQuery, WithHeadings, WithMapping, Responsable
{
    use \Maatwebsite\Excel\Concerns\Exportable;

    public $fileName = 'laporan-calon-siswa.xlsx';
    private string $writerType = Excel::XLSX;

    public function __construct(
        public ?string $filterProv = null,
        public ?string $filterJob  = null,
        public ?string $filterUsia = null,
    ) {}

    public function query()
    {
        $q = CalonSiswa::query()
            ->select([
                'calon_siswas.nomor_peserta',
                'calon_siswas.nama_lengkap',
                'calon_siswas.tanggal_lahir',
                'calon_siswas.tinggi_badan',
                'calon_siswas.berat_badan',
                'calon_siswas.asal_sekolah',
                'calon_siswas.no_kontak',
                'calon_siswas.nama_orang_tua',
                'calon_siswas.pengalaman_berlayar',
                'calon_siswas.job',
                'alamat.provinsi',
                'alamat.kota',
                'alamat.kecamatan',
                'alamat.kelurahan',
                'calon_siswas.updated_at',
                DB::raw('UPPER(np.nilai) as nilai_akhir'),
            ])
            ->join('alamat', 'alamat.calon_siswa_id', '=', 'calon_siswas.id');
        // cari id pelajaran "Nilai Akhir"
        $finalSubjectId = MataPelajaran::where('nama', 'ILIKE', 'Nilai Akhir')->value('id');

        if ($finalSubjectId) {
            // subquery record terakhir
            $latestRecordSub = DB::table('siswa_records as sr')
                ->selectRaw('MAX(sr.id)')
                ->whereColumn('sr.calon_siswa_id', 'calon_siswas.id');

            $q->leftJoin('siswa_records', 'siswa_records.id', '=', DB::raw("({$latestRecordSub->toSql()})"))
                ->mergeBindings($latestRecordSub)
                ->leftJoin('nilai_pelajarans as np', function ($join) use ($finalSubjectId) {
                    $join->on('np.siswa_record_id', '=', 'siswa_records.id')
                        ->where('np.mata_pelajaran_id', '=', $finalSubjectId);
                });
        }

        if ($this->filterProv) {
            $q->where('alamat.provinsi', 'ILIKE', $this->filterProv);
        }
        if ($this->filterJob) {
            $q->where('calon_siswas.job', $this->filterJob);
        }
        if ($this->filterUsia) {
            [$min, $max] = match ($this->filterUsia) {
                '17-20' => [17, 20],
                '21-25' => [21, 25],
                '26-30' => [26, 30],
                '31+'   => [31, 120],
                default => [null, null],
            };
            if ($min !== null) {
                $today    = now()->startOfDay();
                $maxBirth = $today->copy()->subYears($min);
                $minBirth = $today->copy()->subYears($max + 1)->addDay();
                $q->whereBetween('calon_siswas.tanggal_lahir', [$minBirth, $maxBirth]);
            }
        }

        return $q->orderBy('calon_siswas.nomor_peserta');
    }

    public function headings(): array
    {
        return [
            'Nomor Peserta',
            'Nama Lengkap',
            'Usia',
            'Tanggal Lahir',
            'TB (cm)',
            'BB (kg)',
            'Asal Sekolah',
            'No Kontak',
            'Nama Orang Tua',
            'Pengalaman Berlayar',
            'Job',
            'Provinsi',
            'Kota/Kab',
            'Kecamatan',
            'Kelurahan',
            'Nilai Akhir',
            'Last Updated (WIB)',
        ];
    }

    public function map($row): array
    {
        $age = Carbon::parse($row->tanggal_lahir)->age;
        return [
            $row->nomor_peserta,
            $row->nama_lengkap,
            $age,
            optional($row->tanggal_lahir)->format('Y-m-d'),
            $row->tinggi_badan,
            $row->berat_badan,
            $row->asal_sekolah,
            $row->no_kontak,
            $row->nama_orang_tua,
            $row->pengalaman_berlayar,
            $row->job,
            $row->provinsi ?: 'Tidak diketahui',
            $row->kota,
            $row->kecamatan,
            $row->kelurahan,
            $row->nilai_akhir ?: '-',
            optional($row->updated_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
        ];
    }
}
