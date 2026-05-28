<x-layouts.app title="Dataset - Edelweiss Detection">
    <x-slot:header>Dataset</x-slot:header>

    <div x-data="datasetPage()" class="space-y-6">

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 text-sm text-emerald-700 dark:text-emerald-300 flex items-center justify-between gap-2">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="opacity-70 hover:opacity-100">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="inline-flex p-1 rounded-xl bg-slate-100 dark:bg-slate-800 self-start flex-wrap">
                <button @click="tab = 'riwayat'"
                        :class="tab === 'riwayat' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400'"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition">
                    Riwayat Deteksi
                </button>
                <button @click="tab = 'training'"
                        :class="tab === 'training' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400'"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition">
                    Data Training
                </button>
                <button @click="tab = 'training-history'"
                        :class="tab === 'training-history' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400'"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition">
                    Riwayat Training
                </button>
            </div>

            <div class="flex gap-2">
                <button x-show="tab === 'training'" x-cloak
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">
                    <x-icon name="plus" class="w-4 h-4" />
                    Tambah Data
                </button>
            </div>
        </div>

        <div x-show="tab === 'riwayat'" class="space-y-6">

            {{-- 4 stat card: Total, Hari Ini, Dari Admin/User, Dari Guest (urutan baru) --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                <x-stat-card label="Total Riwayat" :value="$stats['total']" color="emerald" icon="flower" />
                <x-stat-card label="Hari Ini" :value="$stats['today']" color="slate" />
                <x-stat-card label="Dari Admin/User" :value="$stats['admin']" color="slate" />
                <x-stat-card label="Dari Guest" :value="$stats['guest']" color="slate" />
            </div>

            <form method="GET" action="{{ route('admin.dataset') }}"
                  class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4">
                <input type="hidden" name="tab" value="riwayat">

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-3">

                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">Dari Tanggal</label>
                        <input type="date" name="from" value="{{ $filters['from'] }}"
                               class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">Sampai</label>
                        <input type="date" name="to" value="{{ $filters['to'] }}"
                               class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">Kondisi</label>
                        <select name="condition"
                                class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                            <option value="">Semua</option>
                            <option value="Mekar" @selected($filters['condition'] === 'Mekar')>Mekar</option>
                            <option value="Sangat_Mekar" @selected($filters['condition'] === 'Sangat_Mekar')>Sangat Mekar</option>
                            <option value="Penyemaian" @selected($filters['condition'] === 'Penyemaian')>Penyemaian</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">Sumber</label>
                        <select name="user_source"
                                class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                            <option value="all" @selected($filters['user_source'] === 'all')>Semua</option>
                            <option value="admin" @selected($filters['user_source'] === 'admin')>Admin/User</option>
                            <option value="guest" @selected($filters['user_source'] === 'guest')>Guest</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">Metode</label>
                        <select name="input_method"
                                class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                            <option value="all" @selected($filters['input_method'] === 'all')>Semua</option>
                            <option value="upload" @selected($filters['input_method'] === 'upload')>Upload</option>
                            <option value="camera" @selected($filters['input_method'] === 'camera')>Kamera</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">Cari ID</label>
                        <input type="number" name="search" value="{{ $filters['search'] }}" placeholder="#123"
                               class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="flex-1 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">
                            Filter
                        </button>
                        <a href="{{ route('admin.dataset', ['tab' => 'riwayat']) }}"
                           class="px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-medium hover:bg-slate-200 dark:hover:bg-slate-700">
                            Reset
                        </a>
                    </div>
                </div>

                <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
            </form>

            <div class="flex items-center justify-between gap-3 flex-wrap">
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    Menampilkan <span class="font-medium text-slate-900 dark:text-white">{{ $detections->count() }}</span>
                    dari <span class="font-medium text-slate-900 dark:text-white">{{ $detections->total() }}</span> deteksi
                </p>

                <div class="flex items-center gap-2 flex-wrap">
                    @if ($detections->isNotEmpty())
                        <button @click="toggleSelectMode()"
                                :class="selectMode ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                                class="px-3 py-1.5 rounded-md text-xs font-medium transition">
                            <span x-text="selectMode ? 'Batal Pilih' : 'Pilih Banyak'"></span>
                        </button>
                    @endif

                    <span class="text-xs text-slate-500 dark:text-slate-400 hidden sm:inline">Urutkan:</span>
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}"
                       class="px-2.5 py-1 rounded-md text-xs font-medium {{ $filters['sort'] === 'newest' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                        Terbaru
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}"
                       class="px-2.5 py-1 rounded-md text-xs font-medium {{ $filters['sort'] === 'oldest' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                        Terlama
                    </a>
                </div>
            </div>

            @if ($detections->isEmpty())
                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-12">
                    <x-empty-state
                        title="Belum ada riwayat deteksi"
                        message="Riwayat akan muncul setelah Anda atau pengunjung melakukan deteksi."
                        icon="inbox">
                        <x-slot:action>
                            <a href="{{ route('admin.detection') }}"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">
                                <x-icon name="scan" class="w-4 h-4" />
                                Mulai Deteksi
                            </a>
                        </x-slot:action>
                    </x-empty-state>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach ($detections as $d)
                        @php
                            $thumbDetections = $d->result['detections'] ?? [];
                        @endphp

                        <div x-data="historyCard()"
                             x-init="init({{ json_encode($thumbDetections) }})"
                             :class="isSelected({{ $d->id }}) ? 'ring-2 ring-emerald-500 dark:ring-emerald-400' : ''"
                             class="group relative rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:shadow-lg hover:-translate-y-0.5 transition-all">

                            <div x-show="selectMode" x-cloak class="absolute top-2 left-2 z-20">
                                <label class="flex items-center justify-center w-7 h-7 rounded-md bg-white/90 dark:bg-slate-900/90 backdrop-blur cursor-pointer shadow-md">
                                    <input type="checkbox"
                                           :checked="isSelected({{ $d->id }})"
                                           @change="toggleSelect({{ $d->id }})"
                                           class="w-4 h-4 rounded accent-emerald-600">
                                </label>
                            </div>

                            <a href="{{ route('admin.dataset.detail', $d) }}"
                               @click="handleCardClick($event, {{ $d->id }})"
                               class="block">

                                <div class="relative aspect-square bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                    @if ($d->image_path)
                                        <img src="{{ $d->image_url }}"
                                             alt="Deteksi #{{ $d->id }}"
                                             loading="lazy"
                                             x-ref="img"
                                             @load="onImageLoad()"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <canvas x-ref="canvas"
                                                class="absolute inset-0 w-full h-full pointer-events-none"></canvas>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                                            <x-icon name="inbox" class="w-10 h-10" />
                                        </div>
                                    @endif

                                    <div class="absolute top-2 right-2 flex items-center gap-1 z-10">
                                        @if ($d->source === 'camera')
                                            <span class="px-1.5 py-0.5 rounded-full bg-blue-500/90 text-white text-xs backdrop-blur" title="Dari kamera">
                                                <x-icon name="camera" class="w-3 h-3" />
                                            </span>
                                        @endif
                                        <span class="px-2 py-0.5 rounded-full bg-black/60 text-white text-xs font-medium backdrop-blur">
                                            {{ $d->object_count }} obj
                                        </span>
                                    </div>

                                    @if ($d->is_guest)
                                        <div class="absolute bottom-2 left-2 px-2 py-0.5 rounded-full bg-amber-500/90 text-white text-xs font-medium backdrop-blur z-10">
                                            Guest
                                        </div>
                                    @endif
                                </div>

                                <div class="p-3">
                                    <div class="flex items-center justify-between mb-2 gap-2">
                                        <div class="flex items-center gap-2 min-w-0 flex-1">
                                            @if ($d->dominant_label)
                                                <x-fase-badge :fase="$d->dominant_label" />
                                            @else
                                                <span class="text-xs text-slate-400">Tidak terdeteksi</span>
                                            @endif
                                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">#{{ $d->id }}</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                        {{ $d->created_at->format('d M Y, H:i') }}
                                    </p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 truncate">
                                        @if ($d->user)
                                            {{ $d->user->name }}
                                        @else
                                            Pengunjung
                                        @endif
                                    </p>
                                </div>
                            </a>

                            {{-- Tombol Hapus single → pakai modal confirm --}}
                            <button x-show="!selectMode"
                                    @click.stop="confirmDelete({{ $d->id }}, {{ json_encode(route('admin.dataset.destroy', $d)) }})"
                                    class="absolute bottom-3 right-3 px-2.5 py-1 rounded-md bg-rose-600 text-white text-xs font-medium hover:bg-rose-700 shadow-md opacity-0 group-hover:opacity-100 transition-opacity z-10">
                                Hapus
                            </button>

                            {{-- Hidden form yang akan di-submit oleh modal --}}
                            <form :id="`delete-form-{{ $d->id }}`"
                                  method="POST"
                                  action="{{ route('admin.dataset.destroy', $d) }}"
                                  class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    @endforeach
                </div>

                <div>
                    {{ $detections->links() }}
                </div>
            @endif
        </div>

        <div x-show="tab === 'training'" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-3 grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach (['Mekar', 'Sangat_Mekar', 'Penyemaian'] as $label)
                        <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                            <x-fase-badge :fase="$label" />
                            <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">0</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">sampel</p>
                        </div>
                    @endforeach
                </div>

                <div class="lg:col-span-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                    <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="font-semibold text-slate-900 dark:text-white">Upload Data Training</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                            Pilih label kondisi, lalu upload gambar untuk dataset training.
                        </p>
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Label Kondisi</label>
                            <select class="w-full px-4 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                                <option>-- Pilih kondisi --</option>
                                <option>Mekar</option>
                                <option>Sangat Mekar</option>
                                <option>Penyemaian</option>
                            </select>
                        </div>

                        <label class="block cursor-pointer border-2 border-dashed border-slate-300 dark:border-slate-700
                                      rounded-xl px-6 py-10 text-center hover:border-emerald-500 dark:hover:border-emerald-500
                                      hover:bg-emerald-50/50 dark:hover:bg-emerald-500/5 transition">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                <x-icon name="upload" class="w-6 h-6 text-slate-500" />
                            </div>
                            <p class="text-sm font-medium text-slate-900 dark:text-white">Pilih beberapa gambar</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Mendukung multiple files</p>
                            <input type="file" accept="image/*" multiple class="hidden">
                        </label>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5">
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-3">Aksi Training</h3>
                    <div class="space-y-2">
                        <button class="w-full px-4 py-2.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white text-sm font-medium hover:bg-slate-200 dark:hover:bg-slate-700 disabled:opacity-50" disabled>
                            Latih Ulang Model
                        </button>
                        <button class="w-full px-4 py-2.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white text-sm font-medium hover:bg-slate-200 dark:hover:bg-slate-700 disabled:opacity-50" disabled>
                            Export Dataset
                        </button>
                    </div>
                    <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                        Training memerlukan minimal 50 sampel per kondisi. Saat ini belum tersedia.
                    </p>
                </div>
            </div>
        </div>

        <div x-show="tab === 'training-history'" x-cloak>
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Riwayat Training Model</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Histori pelatihan ulang model YOLOv11 + MLP beserta metriknya.
                    </p>
                </div>

                <x-empty-state
                    title="Belum ada riwayat training"
                    message="Riwayat akan muncul setelah model dilatih ulang. Tambahkan data training di tab Data Training terlebih dahulu."
                    icon="book">
                    <x-slot:action>
                        <button @click="tab = 'training'"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">
                            <x-icon name="database" class="w-4 h-4" />
                            Buka Data Training
                        </button>
                    </x-slot:action>
                </x-empty-state>
            </div>
        </div>

        {{-- FLOATING ACTION BAR untuk multi-select --}}
        <div x-show="selectMode && selectedIds.length > 0"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4"
             class="fixed bottom-4 left-1/2 -translate-x-1/2 z-40 max-w-2xl w-[calc(100%-2rem)]">

            <div class="bg-slate-900 dark:bg-slate-800 text-white rounded-xl shadow-2xl p-3 flex items-center gap-3 flex-wrap sm:flex-nowrap">
                <span class="text-sm font-medium">
                    <span x-text="selectedIds.length"></span> dipilih
                </span>

                <div class="h-6 w-px bg-slate-700 hidden sm:block"></div>

                <button @click="selectAllVisible()"
                        class="px-3 py-1.5 rounded-md bg-slate-700 hover:bg-slate-600 text-xs font-medium transition">
                    Pilih Semua
                </button>

                <button @click="clearSelection()"
                        class="px-3 py-1.5 rounded-md bg-slate-700 hover:bg-slate-600 text-xs font-medium transition">
                    Hapus Pilihan
                </button>

                <div class="flex-1"></div>

                {{-- Tombol Hapus Terpilih → trigger modal --}}
                <button @click="confirmBatchDelete()"
                        class="px-4 py-1.5 rounded-md bg-rose-600 hover:bg-rose-700 text-xs font-bold transition">
                    Hapus Terpilih
                </button>

                {{-- Hidden form yang akan di-submit oleh modal --}}
                <form x-ref="batchForm"
                      method="POST"
                      action="{{ route('admin.dataset.destroy-batch') }}"
                      class="hidden">
                    @csrf
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        window._visibleDetectionIds = {!! json_encode($detections->pluck('id')) !!};

        window.datasetPage = function () {
            return {
                tab: '{{ $filters['tab'] }}',
                selectMode: false,
                selectedIds: [],

                toggleSelectMode() {
                    this.selectMode = !this.selectMode;
                    if (!this.selectMode) {
                        this.selectedIds = [];
                    }
                },

                isSelected(id) {
                    return this.selectedIds.includes(id);
                },

                toggleSelect(id) {
                    const idx = this.selectedIds.indexOf(id);
                    if (idx >= 0) {
                        this.selectedIds.splice(idx, 1);
                    } else {
                        this.selectedIds.push(id);
                    }
                },

                selectAllVisible() {
                    this.selectedIds = [...window._visibleDetectionIds];
                },

                clearSelection() {
                    this.selectedIds = [];
                },

                handleCardClick(event, id) {
                    if (this.selectMode) {
                        event.preventDefault();
                        this.toggleSelect(id);
                    }
                },

                /**
                 * Trigger modal confirm untuk single delete.
                 */
                confirmDelete(id, actionUrl) {
                    Alpine.store('confirm').show({
                        title: `Hapus Deteksi #${id}?`,
                        message: 'Deteksi beserta gambar terkait akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.',
                        confirmText: 'Hapus',
                        cancelText: 'Batal',
                        variant: 'danger',
                        onConfirm: () => {
                            // Submit hidden form yang sudah di-render per-card
                            const form = document.getElementById(`delete-form-${id}`);
                            if (form) form.submit();
                        }
                    });
                },

                /**
                 * Trigger modal confirm untuk batch delete.
                 */
                confirmBatchDelete() {
                    const count = this.selectedIds.length;
                    if (count === 0) return;

                    Alpine.store('confirm').show({
                        title: `Hapus ${count} Deteksi?`,
                        message: `${count} deteksi terpilih beserta gambarnya akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.`,
                        confirmText: 'Hapus Semua',
                        cancelText: 'Batal',
                        variant: 'danger',
                        onConfirm: () => {
                            this.$refs.batchForm.submit();
                        }
                    });
                },
            };
        };

        window.historyCard = function () {
            return {
                detections: [],

                init(detections) {
                    this.detections = detections || [];
                    this.$nextTick(() => {
                        const img = this.$refs.img;
                        if (img && img.complete && img.naturalWidth > 0) {
                            this.drawBoxes();
                        }
                    });
                },

                onImageLoad() {
                    this.drawBoxes();
                },

                drawBoxes() {
                    if (!this.$refs.img || !this.$refs.canvas) return;
                    if (!this.detections.length) return;

                    const img = this.$refs.img;
                    const canvas = this.$refs.canvas;
                    const w = img.naturalWidth;
                    const h = img.naturalHeight;

                    if (w === 0 || h === 0) return;

                    canvas.width = w;
                    canvas.height = h;
                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, w, h);

                    const colors = {
                        'Mekar': '#f43f5e',
                        'Sangat_Mekar': '#ec4899',
                        'Penyemaian': '#059669',
                        'Kuncup': '#84cc16',
                        'Pematangan_Biji': '#eab308',
                        'Biji_Matang': '#b45309',
                        'Penyemaian_Baru': '#14b8a6',
                    };

                    this.detections.forEach(det => {
                        const [x1, y1, x2, y2] = det.box;
                        const color = colors[det.label] || '#64748b';
                        ctx.strokeStyle = color;
                        ctx.lineWidth = Math.max(3, w / 200);
                        ctx.strokeRect(x1, y1, x2 - x1, y2 - y1);
                    });
                },
            };
        };

        // Re-render saat back/forward navigation
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
    @endpush
</x-layouts.app>
