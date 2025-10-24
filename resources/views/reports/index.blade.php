<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Reports - LPK Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="h-16 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">&larr; Kembali</a>
                        <img src="images/lpkharini.jpg" alt="LPK Harini Duta Ayu Logo" class="h-8 w-auto">
                        <h1 class="text-xl font-semibold text-gray-900">Reports</h1>
                    </div>
                    <div class="text-sm text-gray-500">Logged in as: {{ auth()->user()->name }}</div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 sm:px-0 space-y-6">

                {{-- FILTER BAR --}}
                <form method="GET" action="{{ route('reports.index') }}" class="bg-white shadow rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        {{-- Range Usia --}}
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Range Usia</label>
                            <select name="filter_usia" class="w-full border rounded px-3 py-2">
                                <option value="">Semua</option>
                                @foreach (['17-20' => '17–20', '21-25' => '21–25', '26-30' => '26–30', '31+' => '31+'] as $val => $label)
                                    <option value="{{ $val }}" @selected($fUsia === $val)>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Provinsi --}}
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Provinsi</label>
                            <select name="filter_provinsi" class="w-full border rounded px-3 py-2">
                                <option value="">Semua</option>
                                @foreach ($provincesList as $prov)
                                    <option value="{{ $prov }}" @selected($fProv === $prov)>{{ $prov }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Job --}}
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Job</label>
                            <select name="filter_job" class="w-full border rounded px-3 py-2">
                                <option value="">Semua</option>
                                @foreach ($jobsList as $job)
                                    <option value="{{ $job }}" @selected($fJob === $job)>{{ $job }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-end gap-2">
                            <button class="bg-blue-600 text-white px-4 py-2 rounded">Apply</button>
                            <a href="{{ route('reports.index') }}" class="px-4 py-2 rounded border">Reset</a>
                        </div>

                        <div class="flex items-end gap-2">
                            <button class="bg-blue-600 text-white px-4 py-2 rounded">Apply</button>

                            {{-- Reset --}}
                            <a href="{{ route('reports.index') }}" class="px-4 py-2 rounded border">Reset</a>

                            {{-- Export: bawa query aktif --}}
                            <a href="{{ route('reports.export', request()->only(['filter_usia', 'filter_provinsi', 'filter_job'])) }}"
                                class="px-4 py-2 rounded bg-green-600 text-white">Export Excel</a>
                        </div>
                    </div>
                </form>

                {{-- Kartu: Range Usia --}}
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold mb-4">Distribusi Range Usia</h2>
                    <div class="h-72">
                        <canvas id="chartRangeUsia"></canvas>
                    </div>
                </div>

                {{-- Nilai Akhir per Calon Siswa --}}
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold">Nilai Akhir per Calon Siswa (Top {{ $finalLimit }})</h2>
                        <span class="text-sm text-gray-500">Sumber: Mata Pelajaran "Nilai Akhir"</span>
                    </div>
                    <div class="h-80">
                        <canvas id="chartNilaiAkhir"></canvas>
                    </div>
                </div>


                {{-- Grid 2 kolom: Histogram Usia & Jobs --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-semibold mb-4">Histogram Usia (per Tahun)</h2>
                        <canvas id="chartUsia" height="120"></canvas>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-semibold mb-4">Distribusi Jobs</h2>
                        <canvas id="chartJobs" height="120"></canvas>
                    </div>
                </div>

                {{-- Kartu: Asal Daerah (Provinsi) --}}
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold">Asal Daerah (Top 15 Provinsi)</h2>
                        <span class="text-sm text-gray-500">Menampilkan maksimal 15 provinsi terbanyak</span>
                    </div>
                    <canvas id="chartProv" height="120"></canvas>
                </div>

            </div>
        </main>
    </div>

    <script>
        // Data dari controller
        const rangeLabels = @json($rangeLabels);
        const rangeCounts = @json($rangeCounts);

        const ageLabels = @json($ageLabels);
        const ageCounts = @json($ageCounts);

        const provLabels = @json($provLabels);
        const provCounts = @json($provCounts);

        const jobLabels = @json($jobLabels);
        const jobCounts = @json($jobCounts);

        const finalLabels = @json($finalLabels);
        const finalGrades = @json($finalGrades);

        // Chart 1: Range Usia (doughnut)
        new Chart(document.getElementById('chartRangeUsia').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: rangeLabels,
                datasets: [{
                    data: rangeCounts
                }]
            },
            options: {
                responsive: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        enabled: true
                    },
                    title: {
                        display: false
                    }
                }
            }
        });


        /** ==== DATA DARI CONTROLLER ==== */
        const finalLabelsRaw = @json(($finalLabels ?? collect())->values());
        const finalGradesRaw = @json(($finalGrades ?? collect())->values());

        /** ==== NORMALISASI NILAI → HURUF A/B/C/D ATAU NULL ==== */
        const normalizeGrade = (g) => {
            if (g == null) return null;
            const ch = String(g).trim().toUpperCase().charAt(0);
            return ['A', 'B', 'C', 'D'].includes(ch) ? ch : null;
        };
        const labels0 = Array.from(finalLabelsRaw || []);
        const grades0 = Array.from(finalGradesRaw || []).map(normalizeGrade);

        /** ==== BUANG ENTRY TANPA NILAI (NULL) AGAR CHART TIDAK KOSONG ==== */
        const labels = [];
        const grades = [];
        for (let i = 0; i < labels0.length; i++) {
            if (grades0[i] != null) {
                labels.push(labels0[i]);
                grades.push(grades0[i]); // 'A' | 'B' | 'C' | 'D'
            }
        }

        /** ==== JIKA TIDAK ADA DATA, TAMPILKAN TEKS KOSONG ==== */
        if (!labels.length) {
            const wrap = document.getElementById('chartNilaiAkhir').parentElement;
            wrap.innerHTML = `
    <div class="h-40 flex items-center justify-center text-gray-500">
      Tidak ada data "Nilai Akhir" untuk filter saat ini.
    </div>`;
        } else {
            /** ==== PEMETAAN HURUF ↔ ANGKA & WARNA ==== */
            const gradeToNum = {
                A: 4,
                B: 3,
                C: 2,
                D: 1
            };
            const numToGrade = {
                1: 'D',
                2: 'C',
                3: 'B',
                4: 'A'
            };
            const dataNum = grades.map(g => gradeToNum[g]);
            const colorFor = g => ({
                A: 'rgba(34,197,94,.75)', // green-500
                B: 'rgba(59,130,246,.75)', // blue-500
                C: 'rgba(234,179,8,.75)', // yellow-500
                D: 'rgba(239,68,68,.75)', // red-500
            } [g] || 'rgba(100,116,139,.6)');
            const barColors = grades.map(colorFor);

            /** ==== RENDER CHART ==== */
            const many = labels.length > 8; // banyak → horizontal
            const valueAxis = many ? 'x' : 'y'; // sumbu nilai 1..4
            const categoryAxis = many ? 'y' : 'x'; // sumbu nama siswa

            const ctx = document.getElementById('chartNilaiAkhir').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Nilai',
                        data: dataNum, // 4/3/2/1
                        backgroundColor: barColors,
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: many ? 'y' : 'x',
                    scales: {
                        [valueAxis]: {
                            min: 0.5,
                            max: 4.5,
                            ticks: {
                                stepSize: 1,
                                callback: v => numToGrade[v] ?? ''
                            },
                            title: {
                                display: true,
                                text: 'Nilai (A terbaik)'
                            },
                            grid: {
                                drawBorder: true
                            }
                        },
                        [categoryAxis]: {
                            ticks: {
                                autoSkip: true,
                                maxTicksLimit: 20
                            },
                            grid: {
                                drawBorder: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => 'Nilai: ' + (grades[ctx.dataIndex] ?? '-')
                            }
                        },
                        datalabels: {
                            formatter: (_v, ctx) => grades[ctx.dataIndex] ?? '',
                            anchor: many ? 'center' : 'end',
                            align: many ? 'right' : 'top',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        }



        // Chart 2: Histogram Usia (bar)
        new Chart(document.getElementById('chartUsia').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ageLabels,
                datasets: [{
                    label: 'Jumlah',
                    data: ageCounts
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Usia (tahun)'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });

        // Chart 3: Jobs (bar horizontal jika label banyak)
        new Chart(document.getElementById('chartJobs').getContext('2d'), {
            type: 'bar',
            data: {
                labels: jobLabels,
                datasets: [{
                    label: 'Jumlah',
                    data: jobCounts
                }]
            },
            options: {
                indexAxis: jobLabels.length > 5 ? 'y' : 'x',
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Job'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });

        // Chart 4: Provinsi (bar horizontal)
        new Chart(document.getElementById('chartProv').getContext('2d'), {
            type: 'bar',
            data: {
                labels: provLabels,
                datasets: [{
                    label: 'Jumlah',
                    data: provCounts
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Provinsi'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    </script>
</body>

</html>
