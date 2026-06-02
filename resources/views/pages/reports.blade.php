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
                tab: new URLSearchParams(window.location.search).get('tab') || 'laporan',
                trendChartInstance: null,
                distributionChartInstance: null,

                init() {
                    this.$nextTick(() => {
                        requestAnimationFrame(() => {
                            requestAnimationFrame(() => {
                                if (this.tab === 'laporan') {
                                    this.safeRender(() => this.renderTrendChart(), 'trend');
                                    this.safeRender(() => this.renderDistributionChart(), 'distribution');
                                }
                            });
                        });
                    });
                },

                switchTab(name) {
                    this.tab = name;
                    if (name === 'laporan') {
                        this.$nextTick(() => {
                            requestAnimationFrame(() => {
                                requestAnimationFrame(() => {
                                    this.safeRender(() => this.renderTrendChart(), 'trend');
                                    this.safeRender(() => this.renderDistributionChart(), 'distribution');
                                });
                            });
                        });
                    }
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
                                label: @json(__('reports.detection_label')),
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

        {{-- TAB BAR (3 tab: Data Laporan | Kondisi Edelweis | Tentang Sistem) --}}
        <div class="inline-flex p-1 rounded-xl bg-slate-100 dark:bg-slate-800 flex-wrap">
            <button @click="switchTab('laporan')"
                    :class="tab === 'laporan' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400'"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition">
                {{ __('reports.tab.data') }}
            </button>
            <button @click="switchTab('kondisi')"
                    :class="tab === 'kondisi' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400'"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition">
                {{ __('reports.tab.conditions') }}
            </button>
            <button @click="switchTab('sistem')"
                    :class="tab === 'sistem' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400'"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition">
                {{ __('reports.tab.system') }}
            </button>
        </div>

        {{-- ============================================================
             TAB 1: DATA LAPORAN
             ============================================================ --}}
        <div x-show="tab === 'laporan'" class="space-y-6">

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
            <x-stat-card :label="__('reports.stat.total_detection')" :value="$summary['total']" color="emerald" icon="flower" />
            <x-stat-card label="Rata-rata per Hari" :value="$summary['avg_per_day']" color="slate" icon="chart" />
            <x-stat-card
                :label="__('reports.stat.dominant_condition')"
                :value="$summary['dominant'] ? str_replace('_', ' ', $summary['dominant']) : '—'"
                color="slate" />
            <x-stat-card :label="__('reports.stat.total_object')" :value="$summary['objects']" color="slate" />
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
                            <x-empty-state :title="__('dashboard.no_data_title')" :message="__('reports.trend_empty')" icon="chart" />
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
                            <x-empty-state :title="__('dashboard.no_data_title')" :message="__('reports.distribution_empty')" icon="chart" />
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
                    <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('reports.detail_title') }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        {{ $detections->total() }} deteksi sesuai filter
                    </p>
                </div>
                <div class="flex gap-2"
                     x-data="reportsExport()">

                    {{-- Export PDF --}}
                    <button @click="exportFile('{{ route('admin.reports.export.pdf', request()->query()) }}', 'pdf')"
                            type="button"
                            :disabled="loading"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-500/30 text-sm font-medium hover:bg-rose-100 dark:hover:bg-rose-500/20 disabled:opacity-50 disabled:cursor-not-allowed"
                            title="{{ __('reports.export.pdf_title') }}">
                        <x-icon name="download" class="w-4 h-4" />
                        {{ __('reports.export.pdf') }}
                    </button>

                    {{-- Export Excel --}}
                    <button @click="exportFile('{{ route('admin.reports.export.excel', request()->query()) }}', 'excel')"
                            type="button"
                            :disabled="loading"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30 text-sm font-medium hover:bg-emerald-100 dark:hover:bg-emerald-500/20 disabled:opacity-50 disabled:cursor-not-allowed"
                            title="{{ __('reports.export.excel_title') }}">
                        <x-icon name="download" class="w-4 h-4" />
                        {{ __('reports.export.excel') }}
                    </button>

                    {{-- Modal loader / success --}}
                    <div x-show="showModal"
                         x-cloak
                         x-transition.opacity
                         class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm">
                        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl p-6 max-w-sm w-[calc(100%-2rem)] mx-4"
                             @click.stop>

                            {{-- Loading state --}}
                            <div x-show="status === 'loading'" class="text-center">
                                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-emerald-100 dark:bg-emerald-500/10 flex items-center justify-center">
                                    <svg class="animate-spin w-7 h-7 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="none">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-opacity="0.25"/>
                                        <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1" x-text="loadingText"></h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('messages.status.processing') }}</p>
                            </div>

                            {{-- Success state --}}
                            <div x-show="status === 'success'" class="text-center">
                                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-emerald-100 dark:bg-emerald-500/10 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1" x-text="successText"></h3>
                            </div>

                            {{-- Error state --}}
                            <div x-show="status === 'error'" class="text-center">
                                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-rose-100 dark:bg-rose-500/10 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                                    </svg>
                                </div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1">{{ __('reports.export_failed') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function reportsExport() {
                        return {
                            loading: false,
                            showModal: false,
                            status: 'loading',
                            loadingText: '',
                            successText: '',

                            async exportFile(url, type) {
                                this.loading = true;
                                this.status = 'loading';
                                this.loadingText = type === 'pdf'
                                    ? @json(__('reports.exporting_pdf'))
                                    : @json(__('reports.exporting_excel'));
                                this.successText = type === 'pdf'
                                    ? @json(__('reports.export_success_pdf'))
                                    : @json(__('reports.export_success_excel'));
                                this.showModal = true;

                                try {
                                    const response = await fetch(url, {
                                        method: 'GET',
                                        headers: { 'Accept': '*/*' },
                                    });

                                    if (!response.ok) throw new Error('HTTP ' + response.status);

                                    const blob = await response.blob();

                                    // Ambil filename dari Content-Disposition header
                                    const disposition = response.headers.get('Content-Disposition') || '';
                                    let filename = type === 'pdf' ? 'laporan.pdf' : 'laporan.xlsx';
                                    const match = disposition.match(/filename="?([^"]+)"?/);
                                    if (match) filename = match[1];

                                    // Trigger download
                                    const blobUrl = window.URL.createObjectURL(blob);
                                    const a = document.createElement('a');
                                    a.href = blobUrl;
                                    a.download = filename;
                                    document.body.appendChild(a);
                                    a.click();
                                    document.body.removeChild(a);
                                    window.URL.revokeObjectURL(blobUrl);

                                    // Show success briefly
                                    this.status = 'success';
                                    setTimeout(() => {
                                        this.showModal = false;
                                        this.loading = false;
                                    }, 1500);

                                } catch (e) {
                                    console.error('Export failed:', e);
                                    this.status = 'error';
                                    setTimeout(() => {
                                        this.showModal = false;
                                        this.loading = false;
                                    }, 2500);
                                }
                            }
                        };
                    }
                </script>
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
                                <th class="px-5 py-3 font-medium">{{ __('reports.col_date') }}</th>
                                <th class="px-5 py-3 font-medium">{{ __('reports.filter.source') }}</th>
                                <th class="px-5 py-3 font-medium">{{ __('reports.filter.method') }}</th>
                                <th class="px-5 py-3 font-medium">{{ __('reports.col_dominant') }}</th>
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

        </div>{{-- end TAB 1 --}}

        {{-- ============================================================
             TAB 2: KONDISI EDELWEIS (dari halaman Belajar)
             ============================================================ --}}
        <div x-show="tab === 'kondisi'" x-cloak class="space-y-6">

            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                    {{ __('learning.conditions.card_title') }}
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    {!! __('learning.conditions.card_desc') !!}
                </p>
            </div>

            @php
                $kondisiTab = [
                    [
                        'nama' => 'Mekar',
                        'deskripsi' => __('learning.conditions.mekar.desc'),
                        'ciri' => [__('learning.conditions.mekar.ciri1'), __('learning.conditions.mekar.ciri2'), __('learning.conditions.mekar.ciri3')],
                    ],
                    [
                        'nama' => 'Sangat_Mekar',
                        'deskripsi' => __('learning.conditions.sangat_mekar.desc'),
                        'ciri' => [__('learning.conditions.sangat_mekar.ciri1'), __('learning.conditions.sangat_mekar.ciri2'), __('learning.conditions.sangat_mekar.ciri3')],
                    ],
                    [
                        'nama' => 'Penyemaian',
                        'deskripsi' => __('learning.conditions.penyemaian.desc'),
                        'ciri' => [__('learning.conditions.penyemaian.ciri1'), __('learning.conditions.penyemaian.ciri2'), __('learning.conditions.penyemaian.ciri3')],
                    ],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($kondisiTab as $i => $f)
                    <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 hover:shadow-sm transition">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-600 dark:text-slate-300">
                                    {{ $i + 1 }}
                                </span>
                                <h3 class="font-bold text-slate-900 dark:text-white">{{ __('messages.kondisi.' . $f['nama']) }}</h3>
                            </div>
                            <x-fase-badge :fase="$f['nama']" />
                        </div>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-3">
                            {!! $f['deskripsi'] !!}
                        </p>
                        <div class="space-y-1.5">
                            @foreach ($f['ciri'] as $c)
                                <div class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-400">
                                    <x-icon name="check-circle" class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" />
                                    <span>{{ $c }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/30">
                <p class="text-sm text-blue-900 dark:text-blue-300">
                    {{ __('learning.conditions.note') }}
                </p>
            </div>
        </div>

        {{-- ============================================================
             TAB 3: TENTANG SISTEM (dari halaman Belajar)
             ============================================================ --}}
        <div x-show="tab === 'sistem'" x-cloak class="space-y-6">

            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                    {{ __('learning.system.card_title') }}
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    {{ __('learning.system.card_desc') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Arsitektur Model --}}
                <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('learning.system.arch_title') }}</h3>
                    </div>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.arch_approach') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">{{ __('learning.system.arch_approach_value') }}</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.arch_stage1') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">YOLOv11n</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.arch_stage2') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">MLP Classifier</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.arch_input') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">640&times;640 px</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.arch_conditions') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white text-right">{{ __('learning.system.arch_conditions_value') }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Hasil Pelatihan --}}
                <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-rose-100 dark:bg-rose-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('learning.system.metrics_title') }}</h3>
                    </div>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">YOLO mAP@0.5</dt>
                            <dd class="font-medium text-emerald-600 dark:text-emerald-400">96.04%</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">YOLO mAP@0.5:0.95</dt>
                            <dd class="font-medium text-emerald-600 dark:text-emerald-400">70.84%</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">YOLO Precision</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">90.29%</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">YOLO Recall</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">92.97%</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.metrics_mlp_acc') }}</dt>
                            <dd class="font-medium text-emerald-600 dark:text-emerald-400">97.98%</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.metrics_optimizer') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">AdamW</dd>
                        </div>
                    </dl>
                </div>

                {{-- Data Pelatihan --}}
                <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('learning.system.data_title') }}</h3>
                    </div>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.data_source') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white text-right">{{ __('learning.system.data_source_value') }}</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.data_annotation_tool') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">Roboflow</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.data_total') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">3.000</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.data_train') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">2.400 (80%)</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.data_val') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">300 (10%)</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.data_test') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">300 (10%)</dd>
                        </div>
                    </dl>
                </div>

                {{-- Lokasi Pengambilan Data (2 gunung + maps) --}}
                <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-rose-100 dark:bg-rose-500/20 flex items-center justify-center">
                            <x-icon name="map-pin" class="w-5 h-5 text-rose-600 dark:text-rose-400" />
                        </div>
                        <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('learning.system.data_location') }}</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-3 p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                            <div>
                                <p class="font-medium text-slate-900 dark:text-white text-sm">{{ __('learning.system.data_location_ggp') }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Jawa Barat</p>
                            </div>
                            <a href="{{ __('learning.system.data_location_maps_ggp') }}" target="_blank" rel="noopener"
                               class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-medium hover:bg-emerald-700 transition">
                                <x-icon name="map-pin" class="w-3.5 h-3.5" />
                                {{ __('messages.action.open_maps') }}
                            </a>
                        </div>
                        <div class="flex items-center justify-between gap-3 p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                            <div>
                                <p class="font-medium text-slate-900 dark:text-white text-sm">{{ __('learning.system.data_location_gl') }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Jawa Tengah / Jawa Timur</p>
                            </div>
                            <a href="{{ __('learning.system.data_location_maps_gl') }}" target="_blank" rel="noopener"
                               class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-medium hover:bg-emerald-700 transition">
                                <x-icon name="map-pin" class="w-3.5 h-3.5" />
                                {{ __('messages.action.open_maps') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Tools --}}
                <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 md:col-span-2">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('learning.system.tools_title') }}</h3>
                    </div>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-sm">
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.tools_training') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">Google Colab</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.tools_gpu') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">NVIDIA T4</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.tools_ml_framework') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">PyTorch + Ultralytics</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.tools_web') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">Laravel 13 + Blade</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.tools_service') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">FastAPI (Python)</dd>
                        </div>
                        <div class="flex justify-between gap-3 py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.tools_db') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">MySQL</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Pipeline --}}
            <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-gradient-to-br from-emerald-50 to-blue-50 dark:from-emerald-500/5 dark:to-blue-500/5">
                <h3 class="font-semibold text-slate-900 dark:text-white mb-3">{{ __('learning.system.pipeline_title') }}</h3>

                <div class="flex flex-col md:flex-row items-stretch gap-3 text-sm">
                    <div class="flex-1 p-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <div class="font-bold text-emerald-600 dark:text-emerald-400 mb-1">{{ __('learning.system.pipeline_step1_title') }}</div>
                        <p class="text-xs text-slate-600 dark:text-slate-400">{{ __('learning.system.pipeline_step1_desc') }}</p>
                    </div>
                    <div class="hidden md:flex items-center text-slate-400 dark:text-slate-600">&rarr;</div>
                    <div class="flex-1 p-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <div class="font-bold text-emerald-600 dark:text-emerald-400 mb-1">{{ __('learning.system.pipeline_step2_title') }}</div>
                        <p class="text-xs text-slate-600 dark:text-slate-400">{{ __('learning.system.pipeline_step2_desc') }}</p>
                    </div>
                    <div class="hidden md:flex items-center text-slate-400 dark:text-slate-600">&rarr;</div>
                    <div class="flex-1 p-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <div class="font-bold text-emerald-600 dark:text-emerald-400 mb-1">{{ __('learning.system.pipeline_step3_title') }}</div>
                        <p class="text-xs text-slate-600 dark:text-slate-400">{{ __('learning.system.pipeline_step3_desc') }}</p>
                    </div>
                    <div class="hidden md:flex items-center text-slate-400 dark:text-slate-600">&rarr;</div>
                    <div class="flex-1 p-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <div class="font-bold text-emerald-600 dark:text-emerald-400 mb-1">{{ __('learning.system.pipeline_step4_title') }}</div>
                        <p class="text-xs text-slate-600 dark:text-slate-400">{{ __('learning.system.pipeline_step4_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-layouts.app>
