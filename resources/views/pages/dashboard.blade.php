<x-layouts.app title="Dashboard - Edelweiss Detection">
    <x-slot:header>Dashboard</x-slot:header>

    {{-- Inline script (must come BEFORE x-data) supaya dashboardCharts() & data tersedia saat Alpine init --}}
{{-- Pass data PHP → JS via JSON --}}
    <script>
        window._dashboardData = {
            trend7: @json($chartTrend7 ?? []),
            trend30: @json($chartTrend30 ?? []),
            trend90: @json($chartTrend90 ?? []),
            hourly: @json($chartHourly ?? []),
            byClass: @json($byClass ?? []),
            source: @json($chartSource ?? ['admin' => 0, 'guest' => 0]),
        };

        window.dashboardCharts = function () {
            return {
                trendPeriod: 30,
                trendChartInstance: null,
                distributionChartInstance: null,
                hourlyChartInstance: null,
                sourceChartInstance: null,

                init() {
                    // Tunggu Alpine selesai render + 2x RAF untuk memastikan
                    // browser layout fully committed sebelum Chart.js init.
                    this.$nextTick(() => {
                        requestAnimationFrame(() => {
                            requestAnimationFrame(() => {
                                this.renderAllCharts();
                            });
                        });
                    });
                },

                renderAllCharts() {
                    this.safeRender(() => this.renderTrendChart(), 'trend');
                    this.safeRender(() => this.renderDistributionChart(), 'distribution');
                    this.safeRender(() => this.renderHourlyChart(), 'hourly');
                    this.safeRender(() => this.renderSourceChart(), 'source');
                },

                /**
                 * Wrap render dengan try-catch — kalau ada Chart.js internal error,
                 * log saja, jangan break halaman lain.
                 */
                safeRender(fn, name) {
                    try {
                        fn();
                    } catch (err) {
                        console.warn(`[Chart] gagal render ${name}:`, err.message);
                    }
                },

                setTrendPeriod(days) {
                    this.trendPeriod = days;
                    requestAnimationFrame(() => this.safeRender(() => this.renderTrendChart(), 'trend'));
                },

                getTrendData() {
                    if (this.trendPeriod === 7) return window._dashboardData.trend7;
                    if (this.trendPeriod === 90) return window._dashboardData.trend90;
                    return window._dashboardData.trend30;
                },

                renderTrendChart() {
                    const canvas = this.$refs.trendChart;
                    if (!canvas) return;
                    if (canvas.clientWidth === 0 || canvas.clientHeight === 0) return;

                    const data = this.getTrendData();
                    if (!data.length) return;

                    // Destroy previous
                    // Destroy chart lama yang attached ke canvas ini (kalau ada)
                    const existing = window.Chart.getChart(canvas);
                    if (existing) existing.destroy();
                    if (this.trendChartInstance) {
                        this.trendChartInstance.destroy();
                        this.trendChartInstance = null;
                    }

                    const ctx = this.$refs.trendChart.getContext('2d');
                    const gradient = ctx.createLinearGradient(0, 0, 0, 250);
                    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
                    gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

                    this.trendChartInstance = new window.Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.map(d => d.label),
                            datasets: [{
                                label: 'Deteksi',
                                data: data.map(d => d.count),
                                borderColor: '#10b981',
                                backgroundColor: gradient,
                                borderWidth: 2,
                                tension: 0.35,
                                fill: true,
                                pointRadius: this.trendPeriod === 7 ? 4 : 2,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#10b981',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 400,
                                easing: 'easeOutCubic',
                            },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    displayColors: false,
                                    callbacks: {
                                        label: (ctx) => `${ctx.parsed.y} deteksi`
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: {
                                        maxTicksLimit: this.trendPeriod === 90 ? 10 : 15,
                                        autoSkip: true,
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: { precision: 0 }
                                }
                            }
                        }
                    });
                },

                renderDistributionChart() {
                    const canvas = this.$refs.distributionChart;
                    if (!canvas) return;
                    if (canvas.clientWidth === 0 || canvas.clientHeight === 0) return;

                    const byClass = window._dashboardData.byClass;
                    const total = (byClass.Mekar || 0) + (byClass.Sangat_Mekar || 0) + (byClass.Penyemaian || 0);
                    if (total === 0) return;

                    const existing = window.Chart.getChart(canvas);
                    if (existing) existing.destroy();
                    if (this.distributionChartInstance) {
                        this.distributionChartInstance.destroy();
                        this.distributionChartInstance = null;
                    }

                    this.distributionChartInstance = new window.Chart(this.$refs.distributionChart.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: ['Mekar', 'Sangat Mekar', 'Penyemaian'],
                            datasets: [{
                                data: [byClass.Mekar || 0, byClass.Sangat_Mekar || 0, byClass.Penyemaian || 0],
                                backgroundColor: ['#f43f5e', '#ec4899', '#059669'],
                                borderWidth: 0,
                                hoverOffset: 8,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 400,
                                easing: 'easeOutCubic',
                            },
                            cutout: '65%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { padding: 12, font: { size: 11 } }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    callbacks: {
                                        label: (ctx) => {
                                            const pct = ((ctx.parsed / total) * 100).toFixed(1);
                                            return ` ${ctx.parsed} objek (${pct}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                },

                renderHourlyChart() {
                    const canvas = this.$refs.hourlyChart;
                    if (!canvas) return;
                    if (canvas.clientWidth === 0 || canvas.clientHeight === 0) return;

                    const data = window._dashboardData.hourly;
                    if (!data.length) return;

                    const existing = window.Chart.getChart(canvas);
                    if (existing) existing.destroy();
                    if (this.hourlyChartInstance) {
                        this.hourlyChartInstance.destroy();
                        this.hourlyChartInstance = null;
                    }

                    this.hourlyChartInstance = new window.Chart(this.$refs.hourlyChart.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: data.map(d => d.label),
                            datasets: [{
                                label: 'Deteksi',
                                data: data.map(d => d.count),
                                backgroundColor: 'rgba(16, 185, 129, 0.7)',
                                hoverBackgroundColor: '#10b981',
                                borderRadius: 4,
                                borderSkipped: false,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 400,
                                easing: 'easeOutCubic',
                            },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    callbacks: {
                                        title: (items) => `Jam ${items[0].label}`,
                                        label: (ctx) => `${ctx.parsed.y} deteksi`
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: {
                                        maxTicksLimit: 12,
                                        autoSkip: true,
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: { precision: 0 }
                                }
                            }
                        }
                    });
                },

                renderSourceChart() {
                    const canvas = this.$refs.sourceChart;
                    if (!canvas) return;
                    if (canvas.clientWidth === 0 || canvas.clientHeight === 0) return;

                    const source = window._dashboardData.source;
                    const total = (source.admin || 0) + (source.guest || 0);
                    if (total === 0) return;

                    const existing = window.Chart.getChart(canvas);
                    if (existing) existing.destroy();
                    if (this.sourceChartInstance) {
                        this.sourceChartInstance.destroy();
                        this.sourceChartInstance = null;
                    }

                    this.sourceChartInstance = new window.Chart(this.$refs.sourceChart.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: ['Admin/User', 'Pengunjung'],
                            datasets: [{
                                data: [source.admin || 0, source.guest || 0],
                                backgroundColor: ['#10b981', '#f59e0b'],
                                borderWidth: 0,
                                hoverOffset: 8,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 400,
                                easing: 'easeOutCubic',
                            },
                            cutout: '65%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { padding: 12, font: { size: 11 } }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    callbacks: {
                                        label: (ctx) => {
                                            const pct = ((ctx.parsed / total) * 100).toFixed(1);
                                            return ` ${ctx.parsed} deteksi (${pct}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                },
            };
        };
    </script>

    <div x-data="dashboardCharts()" x-init="init()" class="space-y-6">

        {{-- Welcome banner --}}
        <div class="p-5 sm:p-6 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 text-white">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold">Selamat datang!</h2>
                    <p class="mt-1 text-sm sm:text-base text-emerald-50">
                        Pantau kesehatan bunga Edelweiss Jawa melalui ringkasan deteksi di bawah ini.
                    </p>
                </div>
                <a href="{{ route('admin.detection') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-white text-emerald-700 font-medium text-sm hover:bg-emerald-50 transition shrink-0">
                    <x-icon name="scan" class="w-4 h-4" />
                    Mulai Deteksi
                </a>
            </div>
        </div>

        {{-- Stat cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <x-stat-card label="Total Deteksi" :value="$totalDetections ?? 0" color="emerald" icon="flower" />
            <x-stat-card label="Mekar" :value="$byClass['Mekar'] ?? 0" color="rose" />
            <x-stat-card label="Sangat Mekar" :value="$byClass['Sangat_Mekar'] ?? 0" color="rose" />
            <x-stat-card label="Penyemaian" :value="$byClass['Penyemaian'] ?? 0" color="emerald" />
        </div>

        {{-- Chart row 1: Trend (lebih lebar) + Distribusi --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Trend Deteksi (lg:col-span-2) --}}
            <div class="lg:col-span-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="font-semibold text-slate-900 dark:text-white">Trend Deteksi</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5"
                           x-text="`${trendPeriod} hari terakhir`"></p>
                    </div>

                    {{-- Period toggle --}}
                    <div class="inline-flex p-0.5 rounded-lg bg-slate-100 dark:bg-slate-800 self-start">
                        <button @click="setTrendPeriod(7)"
                                :class="trendPeriod === 7 ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400'"
                                class="px-3 py-1 rounded-md text-xs font-medium transition">
                            7 hari
                        </button>
                        <button @click="setTrendPeriod(30)"
                                :class="trendPeriod === 30 ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400'"
                                class="px-3 py-1 rounded-md text-xs font-medium transition">
                            30 hari
                        </button>
                        <button @click="setTrendPeriod(90)"
                                :class="trendPeriod === 90 ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400'"
                                class="px-3 py-1 rounded-md text-xs font-medium transition">
                            90 hari
                        </button>
                    </div>
                </div>

                <div class="p-5">
                    @if (($totalDetections ?? 0) === 0)
                        <div class="h-64 flex items-center justify-center">
                            <x-empty-state title="Belum ada data" message="Trend akan muncul setelah ada deteksi." icon="chart" />
                        </div>
                    @else
                        <div class="relative h-64">
                            <canvas x-ref="trendChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Distribusi Kondisi (pie) --}}
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Distribusi Kondisi</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Semua deteksi</p>
                </div>

                <div class="p-5">
                    @php $totalByClass = array_sum($byClass ?? []); @endphp
                    @if ($totalByClass === 0)
                        <div class="h-64 flex items-center justify-center">
                            <x-empty-state title="Belum ada data" message="Grafik distribusi akan muncul setelah ada deteksi." icon="chart" />
                        </div>
                    @else
                        <div class="relative h-64">
                            <canvas x-ref="distributionChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Chart row 2: Hourly + Source --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Deteksi per Jam (bar) --}}
            <div class="lg:col-span-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Aktivitas per Jam</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Pola deteksi sepanjang 24 jam
                    </p>
                </div>

                <div class="p-5">
                    @if (($totalDetections ?? 0) === 0)
                        <div class="h-64 flex items-center justify-center">
                            <x-empty-state title="Belum ada data" message="Grafik aktivitas akan muncul setelah ada deteksi." icon="chart" />
                        </div>
                    @else
                        <div class="relative h-64">
                            <canvas x-ref="hourlyChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sumber Deteksi (donut) --}}
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Sumber Deteksi</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Admin vs Pengunjung</p>
                </div>

                <div class="p-5">
                    @php $totalSource = ($chartSource['admin'] ?? 0) + ($chartSource['guest'] ?? 0); @endphp
                    @if ($totalSource === 0)
                        <div class="h-64 flex items-center justify-center">
                            <x-empty-state title="Belum ada data" message="Belum ada deteksi tercatat." icon="chart" />
                        </div>
                    @else
                        <div class="relative h-64">
                            <canvas x-ref="sourceChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent detections --}}
        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                <h3 class="font-semibold text-slate-900 dark:text-white">Deteksi Terbaru</h3>
                <a href="{{ route('admin.history', ['tab' => 'riwayat']) }}"
                   class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline">
                    Lihat semua
                </a>
            </div>

            @if (empty($recent) || count($recent) === 0)
                <x-empty-state
                    title="Belum ada deteksi"
                    message="Riwayat deteksi akan muncul di sini setelah Anda mulai memotret atau mengupload gambar."
                    icon="inbox">
                    <x-slot:action>
                        <a href="{{ route('admin.detection') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">
                            <x-icon name="scan" class="w-4 h-4" />
                            Mulai Deteksi Pertama
                        </a>
                    </x-slot:action>
                </x-empty-state>
            @else
                <div class="divide-y divide-slate-200 dark:divide-slate-800">
                    @foreach ($recent as $r)
                        <a href="{{ route('admin.history.detail', $r) }}"
                           class="flex items-center gap-4 px-5 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">

                            <div class="w-12 h-12 rounded-lg overflow-hidden shrink-0 bg-slate-200 dark:bg-slate-800">
                                @if ($r->image_path)
                                    <img src="{{ $r->image_url }}" alt="Deteksi #{{ $r->id }}"
                                         loading="lazy" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                        <x-icon name="inbox" class="w-5 h-5" />
                                    </div>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white truncate">
                                        Deteksi #{{ $r->id }}
                                    </p>
                                    @if ($r->dominant_label)
                                        <x-fase-badge :fase="$r->dominant_label" />
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    {{ $r->created_at->format('d M Y H:i') }} &middot; {{ $r->object_count }} objek
                                </p>
                            </div>

                            <span class="text-xs text-slate-400 shrink-0">
                                {{ $r->source === 'camera' ? 'Kamera' : 'Upload' }}
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</x-layouts.app>
