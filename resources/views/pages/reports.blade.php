<x-layouts.app :title="__('reports.page_title')">
    <x-slot:header>{{ __('reports.title') }}</x-slot:header>

    {{-- Inline script (must come BEFORE x-data) --}}
<script>
        window._reportsData = {
            trendDaily: @json($trendDaily ?? []),
            byClass: @json($byClass ?? []),
        };

        window.reportsPage = function () {
            return {
                trendChartInstance: null,
                distributionChartInstance: null,

                init() {
                    this.$nextTick(() => {
                        requestAnimationFrame(() => {
                            requestAnimationFrame(() => {
                                this.safeRender(() => this.renderTrendChart(), 'trend');
                                this.safeRender(() => this.renderDistributionChart(), 'distribution');
                            });
                        });
                    });
                },

                safeRender(fn, name) {
                    try {
                        fn();
                    } catch (err) {
                        console.warn(`[Chart] gagal render ${name}:`, err.message);
                    }
                },

                renderTrendChart() {
                    const canvas = this.$refs.trendChart;
                    if (!canvas) return;
                    if (canvas.clientWidth === 0 || canvas.clientHeight === 0) return;
                    const data = window._reportsData.trendDaily;
                    if (!data.length) return;

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
                                pointRadius: data.length <= 10 ? 4 : 2,
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
                                    ticks: { maxTicksLimit: 12, autoSkip: true }
                                },
                                y: { beginAtZero: true, ticks: { precision: 0 } }
                            }
                        }
                    });
                },

                renderDistributionChart() {
                    const canvas = this.$refs.distributionChart;
                    if (!canvas) return;
                    if (canvas.clientWidth === 0 || canvas.clientHeight === 0) return;
                    const byClass = window._reportsData.byClass;
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
            };
        };
    </script>

    <div x-data="reportsPage()" x-init="init()" class="space-y-6">

        {{-- Filter bar --}}
        <form method="GET" action="{{ route('admin.reports') }}"
              class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('reports.filter.from_date') }}</label>
                    <input type="date" name="from" value="{{ $filters['from'] }}"
                           class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('reports.filter.to_date') }}</label>
                    <input type="date" name="to" value="{{ $filters['to'] }}"
                           class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('reports.filter.condition') }}</label>
                    <select name="condition"
                            class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                        <option value="">{{ __('reports.filter.all') }}</option>
                        <option value="Mekar" @selected($filters['condition'] === 'Mekar')>Mekar</option>
                        <option value="Sangat_Mekar" @selected($filters['condition'] === 'Sangat_Mekar')>Sangat Mekar</option>
                        <option value="Penyemaian" @selected($filters['condition'] === 'Penyemaian')>Penyemaian</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('reports.filter.method') }}</label>
                    <select name="source_method"
                            class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                        <option value="all" @selected($filters['source_method'] === 'all')>{{ __('reports.filter.all') }}</option>
                        <option value="upload" @selected($filters['source_method'] === 'upload')>{{ __('reports.method.upload') }}</option>
                        <option value="camera" @selected($filters['source_method'] === 'camera')>{{ __('reports.method.camera') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('reports.filter.source') }}</label>
                    <select name="user_source"
                            class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                        <option value="all" @selected($filters['user_source'] === 'all')>{{ __('reports.filter.all') }}</option>
                        <option value="admin" @selected($filters['user_source'] === 'admin')>Admin/User</option>
                        <option value="guest" @selected($filters['user_source'] === 'guest')>Guest</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="flex-1 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 inline-flex items-center justify-center gap-2">
                        <x-icon name="filter" class="w-4 h-4" />
                        {{ __('reports.filter.apply') }}
                    </button>
                    <a href="{{ route('admin.reports') }}"
                       class="px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-medium hover:bg-slate-200 dark:hover:bg-slate-700">
                        {{ __('reports.filter.reset') }}
                    </a>
                </div>
            </div>
        </form>

        {{-- Summary cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <x-stat-card label="Total Deteksi" :value="$summary['total']" color="emerald" icon="flower" />
            <x-stat-card label="Rata-rata per Hari" :value="$summary['avg_per_day']" color="slate" icon="chart" />
            <x-stat-card
                label="Kondisi Dominan"
                :value="$summary['dominant'] ? str_replace('_', ' ', $summary['dominant']) : '—'"
                color="slate" />
            <x-stat-card label="Total Objek" :value="$summary['objects']" color="slate" />
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Trend (lg:col-span-2) --}}
            <div class="lg:col-span-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('reports.chart.trend_title') }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        {{ \Carbon\Carbon::parse($filters['from'])->format('d M Y') }} &mdash;
                        {{ \Carbon\Carbon::parse($filters['to'])->format('d M Y') }}
                        ({{ $summary['days'] }} hari)
                    </p>
                </div>
                <div class="p-5">
                    @if (count($trendDaily) === 0 || $summary['total'] === 0)
                        <div class="h-64 flex items-center justify-center">
                            <x-empty-state title="Belum ada data" message="Trend akan muncul saat ada deteksi pada periode ini." icon="chart" />
                        </div>
                    @else
                        <div class="relative h-64">
                            <canvas x-ref="trendChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Distribusi --}}
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('reports.chart.distribution_title') }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('reports.chart.distribution_subtitle') }}</p>
                </div>
                <div class="p-5">
                    @php $totalByClass = array_sum($byClass ?? []); @endphp
                    @if ($totalByClass === 0)
                        <div class="h-64 flex items-center justify-center">
                            <x-empty-state title="Belum ada data" message="Grafik distribusi kosong." icon="chart" />
                        </div>
                    @else
                        <div class="relative h-64">
                            <canvas x-ref="distributionChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Detail table --}}
        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-white">Detail Deteksi</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        {{ $detections->total() }} deteksi sesuai filter
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.reports.export.pdf', request()->query()) }}"
                       download
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-500/30 text-sm font-medium hover:bg-rose-100 dark:hover:bg-rose-500/20"
                       title="{{ __('reports.export.pdf_title') }}">
                        <x-icon name="download" class="w-4 h-4" />
                        {{ __('reports.export.pdf') }}
                    </a>
                    <a href="{{ route('admin.reports.export.excel', request()->query()) }}"
                       download
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30 text-sm font-medium hover:bg-emerald-100 dark:hover:bg-emerald-500/20"
                       title="{{ __('reports.export.excel_title') }}">
                        <x-icon name="download" class="w-4 h-4" />
                        {{ __('reports.export.excel') }}
                    </a>
                </div>
            </div>

            @if ($detections->isEmpty())
                <x-empty-state
                    title="Tidak ada data"
                    message="Tidak ada deteksi yang sesuai dengan filter saat ini. Coba ubah rentang tanggal atau hapus filter."
                    icon="inbox" />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-left text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50">
                            <tr>
                                <th class="px-5 py-3 font-medium">ID</th>
                                <th class="px-5 py-3 font-medium">Tanggal</th>
                                <th class="px-5 py-3 font-medium">{{ __('reports.filter.source') }}</th>
                                <th class="px-5 py-3 font-medium">{{ __('reports.filter.method') }}</th>
                                <th class="px-5 py-3 font-medium">Kondisi Dominan</th>
                                <th class="px-5 py-3 font-medium">Objek</th>
                                <th class="px-5 py-3 font-medium">Avg Confidence</th>
                                <th class="px-5 py-3 font-medium"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @foreach ($detections as $d)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                                    <td class="px-5 py-3 font-medium text-slate-900 dark:text-white">#{{ $d->id }}</td>
                                    <td class="px-5 py-3 text-slate-700 dark:text-slate-300">
                                        {{ $d->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-5 py-3 text-slate-600 dark:text-slate-400">
                                        @if ($d->is_guest)
                                            <span class="inline-flex items-center gap-1.5">
                                                <span class="px-1.5 py-0.5 rounded text-xs bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400">Guest</span>
                                            </span>
                                        @else
                                            {{ $d->user->name ?? '—' }}
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-slate-700 dark:text-slate-300">
                                        {{ $d->source === 'camera' ? 'Kamera' : 'Upload' }}
                                    </td>
                                    <td class="px-5 py-3">
                                        @if ($d->dominant_label)
                                            <x-fase-badge :fase="$d->dominant_label" />
                                        @else
                                            <span class="text-xs text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-slate-700 dark:text-slate-300">{{ $d->object_count }}</td>
                                    <td class="px-5 py-3 text-slate-700 dark:text-slate-300">
                                        {{ number_format($d->avg_confidence * 100, 1) }}%
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <a href="{{ route('admin.history.detail', $d) }}"
                                           class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-5 py-4 border-t border-slate-200 dark:border-slate-800">
                    {{ $detections->links() }}
                </div>
            @endif
        </div>
    </div>

</x-layouts.app>
