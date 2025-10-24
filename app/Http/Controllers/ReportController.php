<?php

namespace App\Http\Controllers;

use App\Models\CalonSiswa;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\CalonSiswaFilteredExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // ===== Master untuk dropdown =====
        $provincesList = CalonSiswa::join('alamat', 'alamat.calon_siswa_id', '=', 'calon_siswas.id')
            ->selectRaw("DISTINCT COALESCE(NULLIF(TRIM(alamat.provinsi),''),'Tidak diketahui') as provinsi")
            ->orderBy('provinsi')->pluck('provinsi');

        $jobsList = CalonSiswa::whereNotNull('job')
            ->selectRaw("DISTINCT job")->orderBy('job')->pluck('job');

        // ===== Filter =====
        $fProv = trim((string)$request->get('filter_provinsi', ''));
        $fJob  = trim((string)$request->get('filter_job', ''));
        $fUsia = trim((string)$request->get('filter_usia', ''));   // '17-20' | '21-25' | '26-30' | '31+'
        $finalLimit = (int) $request->get('final_limit', 20);
        if ($finalLimit <= 0) $finalLimit = 20;

        // Base query Eloquent untuk agregasi lain
        $base = CalonSiswa::query()
            ->join('alamat', 'alamat.calon_siswa_id', '=', 'calon_siswas.id');

        if ($fProv !== '') {
            $base->where('alamat.provinsi', 'ILIKE', $fProv);
        }
        if ($fJob !== '') {
            $base->where('calon_siswas.job', $fJob);
        }
        if ($fUsia !== '') {
            [$min, $max] = match ($fUsia) {
                '17-20' => [17, 20],
                '21-25' => [21, 25],
                '26-30' => [26, 30],
                '31+'   => [31, 120],
                default => [null, null],
            };
            if ($min !== null) {
                $today    = now()->startOfDay();
                $maxBirth = $today->copy()->subYears($min);               // paling muda
                $minBirth = $today->copy()->subYears($max + 1)->addDay(); // paling tua
                $base->whereBetween('calon_siswas.tanggal_lahir', [$minBirth, $maxBirth]);
            }
        }

        // ===== Dataset: Histogram Usia (per tahun) =====
        $ages = (clone $base)
            ->selectRaw("CAST(FLOOR(DATE_PART('year', AGE(current_date, calon_siswas.tanggal_lahir))) AS INT) AS age, COUNT(*) AS total")
            ->groupBy('age')->orderBy('age')->get();
        $ageLabels = $ages->pluck('age')->map(fn($v) => (int)$v)->values();
        $ageCounts = $ages->pluck('total')->map(fn($v) => (int)$v)->values();

        // ===== Dataset: Range Usia =====
        $range = (clone $base)->selectRaw("
            SUM(CASE WHEN DATE_PART('year', AGE(current_date, calon_siswas.tanggal_lahir)) BETWEEN 17 AND 20 THEN 1 ELSE 0 END) AS r_17_20,
            SUM(CASE WHEN DATE_PART('year', AGE(current_date, calon_siswas.tanggal_lahir)) BETWEEN 21 AND 25 THEN 1 ELSE 0 END) AS r_21_25,
            SUM(CASE WHEN DATE_PART('year', AGE(current_date, calon_siswas.tanggal_lahir)) BETWEEN 26 AND 30 THEN 1 ELSE 0 END) AS r_26_30,
            SUM(CASE WHEN DATE_PART('year', AGE(current_date, calon_siswas.tanggal_lahir)) >= 31 THEN 1 ELSE 0 END)           AS r_31_plus
        ")->first();

        $rangeLabels = ['17–20', '21–25', '26–30', '31+'];
        $rangeCounts = [
            (int)($range->r_17_20 ?? 0),
            (int)($range->r_21_25 ?? 0),
            (int)($range->r_26_30 ?? 0),
            (int)($range->r_31_plus ?? 0),
        ];

        // ===== Dataset: Provinsi (Top 15) =====
        $provinces = (clone $base)->selectRaw("
            COALESCE(NULLIF(TRIM(alamat.provinsi),''),'Tidak diketahui') AS provinsi, COUNT(*) AS total
        ")->groupBy('provinsi')->orderBy('total', 'desc')->limit(15)->get();
        $provLabels = $provinces->pluck('provinsi');
        $provCounts = $provinces->pluck('total')->map(fn($v) => (int)$v);

        // ===== Dataset: Jobs =====
        $jobs = (clone $base)->selectRaw("
            COALESCE(NULLIF(calon_siswas.job,''),'Tidak diketahui') AS job, COUNT(*) AS total
        ")->groupBy('job')->orderBy('total', 'desc')->get();
        $jobLabels = $jobs->pluck('job');
        $jobCounts = $jobs->pluck('total')->map(fn($v) => (int)$v);

        // ===============  Dataset: Nilai Akhir per Calon Siswa  ==============
        $finalLabels = collect();
        $finalGrades = collect();

        $finalSubjectId = MataPelajaran::where('nama', 'ILIKE', 'Nilai Akhir')->value('id');

        if ($finalSubjectId) {
            $qFinal = DB::table('calon_siswas')
                ->join('alamat', 'alamat.calon_siswa_id', '=', 'calon_siswas.id');

            if ($fProv !== '') $qFinal->where('alamat.provinsi', 'ILIKE', $fProv);
            if ($fJob  !== '') $qFinal->where('calon_siswas.job', $fJob);
            if ($fUsia !== '') {
                [$min, $max] = match ($fUsia) {
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
                    $qFinal->whereBetween('calon_siswas.tanggal_lahir', [$minBirth, $maxBirth]);
                }
            }

            $latestRecordSub = DB::table('siswa_records as sr')
                ->selectRaw('MAX(sr.id)')
                ->whereColumn('sr.calon_siswa_id', 'calon_siswas.id');

            $rows = $qFinal
                ->leftJoin('siswa_records', 'siswa_records.id', '=', DB::raw("({$latestRecordSub->toSql()})"))
                ->mergeBindings($latestRecordSub)
                ->leftJoin('nilai_pelajarans as np', function ($j) use ($finalSubjectId) {
                    $j->on('np.siswa_record_id', '=', 'siswa_records.id')
                        ->where('np.mata_pelajaran_id', '=', $finalSubjectId);
                })
                ->select([
                    'calon_siswas.nomor_peserta',
                    'calon_siswas.nama_lengkap',
                    'np.nilai as final_grade',
                    // HANYA satu alias, sudah dinormalkan: trim + upper + ambil huruf pertama
                    DB::raw("SUBSTRING(TRIM(UPPER(np.nilai)) FROM 1 FOR 1) AS final_grade"),
                    DB::raw("
                        CASE SUBSTRING(TRIM(UPPER(np.nilai)) FROM 1 FOR 1)
                    WHEN 'A' THEN 4
                    WHEN 'B' THEN 3
                    WHEN 'C' THEN 2
                    WHEN 'D' THEN 1
                    ELSE 0
                END AS final_rank
            "),
                ])
                ->orderByDesc('final_rank')
                ->limit($finalLimit)
                ->get();

            $finalLabels = $rows->map(fn($r) => ($r->nomor_peserta ? "{$r->nomor_peserta} – " : '') . $r->nama_lengkap);
            $finalGrades = $rows->pluck('final_grade');
        }

        // kirim ke view:
        return view('reports.index', compact(
            'ageLabels',
            'ageCounts',
            'rangeLabels',
            'rangeCounts',
            'provLabels',
            'provCounts',
            'jobLabels',
            'jobCounts',
            'provincesList',
            'jobsList',
            'fProv',
            'fJob',
            'fUsia',
            'finalLabels',
            'finalGrades',
            'finalLimit'
        ));
    }

    public function export(Request $request)
    {
        $fProv = trim((string)$request->get('filter_provinsi', ''));
        $fJob  = trim((string)$request->get('filter_job', ''));
        $fUsia = trim((string)$request->get('filter_usia', ''));

        $fProv = $fProv === '' ? null : $fProv;
        $fJob  = $fJob  === '' ? null : $fJob;
        $fUsia = $fUsia === '' ? null : $fUsia;

        return (new CalonSiswaFilteredExport($fProv, $fJob, $fUsia))
            ->download('laporan-calon-siswa.xlsx');
    }
}
