<x-layouts.app title="Detail Deteksi #{{ $detection->id }} - Edelweiss Detection">
    <x-slot:header>Detail Deteksi #{{ $detection->id }}</x-slot:header>

    <div x-data="historyDetail()"
         x-init="init({{ json_encode($detection->result['detections'] ?? []) }})"
         class="space-y-6">

        {{-- Breadcrumb + actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <a href="{{ route('admin.dataset', ['tab' => 'riwayat']) }}"
               class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">
                ← Kembali ke Riwayat
            </a>

            {{-- Hapus Deteksi pakai modal confirm --}}
            <div x-data="detailDelete()">
                <button type="button"
                        @click="confirmDelete()"
                        class="px-4 py-2 rounded-lg bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-500/30 text-sm font-medium hover:bg-rose-100 dark:hover:bg-rose-500/20">
                    Hapus Deteksi
                </button>

                <form x-ref="deleteForm"
                      method="POST"
                      action="{{ route('admin.dataset.destroy', $detection) }}"
                      class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Gambar + bbox --}}
            <div class="lg:col-span-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
                <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Hasil Deteksi</h3>
                    <div class="flex gap-2">
                        <button @click="showBoxes = !showBoxes"
                                :class="showBoxes ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400'"
                                class="px-3 py-1.5 rounded-lg text-xs font-medium transition">
                            <span x-text="showBoxes ? 'Sembunyikan Box' : 'Tampilkan Box'"></span>
                        </button>
                        @if ($detection->image_path)
                            <button @click="downloadAnnotated()"
                                    :disabled="isDownloading"
                                    class="px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-medium hover:bg-slate-200 dark:hover:bg-slate-700 disabled:opacity-50 inline-flex items-center gap-1.5">
                                <template x-if="!isDownloading">
                                    <span>Unduh</span>
                                </template>
                                <template x-if="isDownloading">
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg class="animate-spin w-3 h-3" viewBox="0 0 24 24" fill="none">
                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-opacity="0.25"/>
                                            <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                                        </svg>
                                        Mengunduh...
                                    </span>
                                </template>
                            </button>
                        @endif
                    </div>
                </div>

                <div class="p-5">
                    <div class="relative bg-slate-100 dark:bg-slate-800 rounded-xl overflow-hidden">
                        @if ($detection->image_path)
                            <div class="relative">
                                <img x-ref="img"
                                     src="{{ $detection->image_url }}"
                                     crossorigin="anonymous"
                                     alt="Deteksi #{{ $detection->id }}"
                                     class="w-full h-auto block"
                                     @load="onImageLoad()">
                                <canvas x-ref="canvas"
                                        :class="showBoxes ? 'opacity-100' : 'opacity-0'"
                                        class="absolute inset-0 w-full h-full pointer-events-none transition-opacity"></canvas>
                            </div>
                        @else
                            <div class="aspect-video flex flex-col items-center justify-center text-slate-400">
                                <x-icon name="inbox" class="w-12 h-12 mb-2" />
                                <p class="text-sm">Gambar tidak tersedia</p>
                                <p class="text-xs text-slate-500 mt-1">(deteksi lama, sebelum fitur save image)</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar info --}}
            <aside class="space-y-4">

                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5">
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-4">Informasi</h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-500 dark:text-slate-400">ID</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">#{{ $detection->id }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-500 dark:text-slate-400">Tanggal</dt>
                            <dd class="font-medium text-slate-900 dark:text-white text-right">
                                {{ $detection->created_at->format('d M Y, H:i') }}
                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-500 dark:text-slate-400">Metode</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">
                                {{ $detection->source === 'camera' ? 'Kamera' : 'Upload' }}
                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-500 dark:text-slate-400">Total Objek</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">{{ $detection->object_count }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-500 dark:text-slate-400">Kondisi Dominan</dt>
                            <dd>
                                @if ($detection->dominant_label)
                                    <x-fase-badge :fase="$detection->dominant_label" />
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </dd>
                        </div>
                        <div class="flex justify-between gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                            <dt class="text-slate-500 dark:text-slate-400">Diunggah oleh</dt>
                            <dd class="font-medium text-slate-900 dark:text-white text-right">
                                @if ($detection->user)
                                    {{ $detection->user->name }}
                                @else
                                    <span class="inline-flex items-center gap-1.5">
                                        Pengunjung
                                        <span class="px-1.5 py-0.5 rounded text-xs bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400">Guest</span>
                                    </span>
                                @endif
                            </dd>
                        </div>
                        @if ($detection->is_guest && $detection->guest_ip)
                            <div class="flex justify-between gap-3">
                                <dt class="text-slate-500 dark:text-slate-400">IP Address</dt>
                                <dd class="font-mono text-xs text-slate-600 dark:text-slate-400">{{ $detection->guest_ip }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5">
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-3">
                        Detail Objek
                        <span class="text-xs font-normal text-slate-500">({{ $detection->object_count }})</span>
                    </h3>

                    @php
                        $detectionsList = $detection->result['detections'] ?? [];
                    @endphp

                    @if (empty($detectionsList))
                        <p class="text-sm text-slate-500 dark:text-slate-400 py-4 text-center">
                            Tidak ada objek terdeteksi.
                        </p>
                    @else
                        <div class="space-y-2">
                            @foreach ($detectionsList as $i => $det)
                                <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full"
                                                  style="background: {{ ['Mekar' => '#f43f5e', 'Sangat_Mekar' => '#ec4899', 'Penyemaian' => '#059669'][$det['label']] ?? '#64748b' }};"></span>
                                            <span class="text-sm font-medium text-slate-900 dark:text-white">
                                                #{{ $i + 1 }} {{ str_replace('_', ' ', $det['label']) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        <div class="flex justify-between">
                                            <span class="text-slate-500 dark:text-slate-400">YOLO:</span>
                                            <span class="font-medium text-slate-700 dark:text-slate-300">
                                                {{ number_format(($det['yolo_confidence'] ?? 0) * 100, 1) }}%
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-500 dark:text-slate-400">MLP:</span>
                                            <span class="font-medium text-slate-700 dark:text-slate-300">
                                                {{ number_format(($det['mlp_confidence'] ?? 0) * 100, 1) }}%
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>

    @push('scripts')
    <script>
        window._labelColors = {
            'Mekar': '#f43f5e',
            'Sangat_Mekar': '#ec4899',
            'Penyemaian': '#059669',
            'Kuncup': '#84cc16',
            'Pematangan_Biji': '#eab308',
            'Biji_Matang': '#b45309',
            'Penyemaian_Baru': '#14b8a6',
        };

        window.historyDetail = function () {
            return {
                showBoxes: true,
                detections: [],
                isDownloading: false,

                init(detections) {
                    this.detections = detections || [];

                    // Robust multi-strategy untuk draw bbox:
                    // 1. Tunggu next tick supaya $refs ready
                    // 2. Cek apakah image sudah complete (cached/bfcache)
                    // 3. Kalau belum complete, @load handler akan trigger nanti
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
                    if (!this.$refs.canvas || !this.$refs.img) return;

                    const img = this.$refs.img;
                    const canvas = this.$refs.canvas;
                    const w = img.naturalWidth;
                    const h = img.naturalHeight;

                    if (w === 0 || h === 0) return;

                    canvas.width = w;
                    canvas.height = h;
                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, w, h);

                    this.detections.forEach(det => {
                        const [x1, y1, x2, y2] = det.box;
                        const color = window._labelColors[det.label] || '#64748b';

                        ctx.strokeStyle = color;
                        ctx.lineWidth = Math.max(2, w / 400);
                        ctx.strokeRect(x1, y1, x2 - x1, y2 - y1);

                        const displayLabel = (det.label || '').replace(/_/g, ' ');
                        const conf = det.mlp_confidence ?? det.yolo_confidence ?? 0;
                        const text = `${displayLabel} ${(conf * 100).toFixed(0)}%`;
                        ctx.font = `${Math.max(14, w / 60)}px sans-serif`;
                        const tw = ctx.measureText(text).width;
                        const th = Math.max(18, w / 50);
                        ctx.fillStyle = color;
                        ctx.fillRect(x1, Math.max(0, y1 - th), tw + 10, th);
                        ctx.fillStyle = '#fff';
                        ctx.fillText(text, x1 + 5, Math.max(th - 4, y1 - 5));
                    });
                },

                /**
                 * Download gambar dengan bbox annotated.
                 * Fetch via Blob → draw ke canvas baru → bbox di atas → export → trigger download.
                 */
                async downloadAnnotated() {
                    if (this.isDownloading) return;
                    this.isDownloading = true;

                    try {
                        const img = this.$refs.img;
                        const w = img.naturalWidth;
                        const h = img.naturalHeight;

                        const exportCanvas = document.createElement('canvas');
                        exportCanvas.width = w;
                        exportCanvas.height = h;
                        const ctx = exportCanvas.getContext('2d');

                        // Fetch sebagai blob - hindari CORS canvas taint
                        const response = await fetch(img.src);
                        if (!response.ok) {
                            throw new Error('Gagal memuat gambar');
                        }
                        const blob = await response.blob();
                        const objectUrl = URL.createObjectURL(blob);

                        const tempImg = new Image();
                        await new Promise((resolve, reject) => {
                            tempImg.onload = resolve;
                            tempImg.onerror = reject;
                            tempImg.src = objectUrl;
                        });

                        ctx.drawImage(tempImg, 0, 0, w, h);
                        URL.revokeObjectURL(objectUrl);

                        // Draw bbox kalau showBoxes aktif
                        if (this.showBoxes && this.detections.length > 0) {
                            this.detections.forEach(det => {
                                const [x1, y1, x2, y2] = det.box;
                                const color = window._labelColors[det.label] || '#64748b';

                                ctx.strokeStyle = color;
                                ctx.lineWidth = Math.max(2, w / 400);
                                ctx.strokeRect(x1, y1, x2 - x1, y2 - y1);

                                const displayLabel = (det.label || '').replace(/_/g, ' ');
                                const conf = det.mlp_confidence ?? det.yolo_confidence ?? 0;
                                const text = `${displayLabel} ${(conf * 100).toFixed(0)}%`;
                                ctx.font = `${Math.max(14, w / 60)}px sans-serif`;
                                const tw = ctx.measureText(text).width;
                                const th = Math.max(18, w / 50);
                                ctx.fillStyle = color;
                                ctx.fillRect(x1, Math.max(0, y1 - th), tw + 10, th);
                                ctx.fillStyle = '#fff';
                                ctx.fillText(text, x1 + 5, Math.max(th - 4, y1 - 5));
                            });
                        }

                        // Export & trigger download
                        exportCanvas.toBlob((exportBlob) => {
                            if (!exportBlob) {
                                Alpine.store('toast').show('Gagal membuat gambar', 'error');
                                this.isDownloading = false;
                                return;
                            }

                            const url = URL.createObjectURL(exportBlob);
                            const a = document.createElement('a');
                            a.href = url;
                            const suffix = this.showBoxes ? '-annotated' : '';
                            a.download = `deteksi-{{ $detection->id }}${suffix}.jpg`;
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);

                            setTimeout(() => URL.revokeObjectURL(url), 1000);

                            Alpine.store('toast').show('Gambar berhasil diunduh', 'success');
                            this.isDownloading = false;
                        }, 'image/jpeg', 0.92);

                    } catch (err) {
                        console.error('Download error:', err);
                        Alpine.store('toast').show('Gagal mengunduh: ' + err.message, 'error');
                        this.isDownloading = false;
                    }
                },
            };
        };

        window.detailDelete = function () {
            return {
                confirmDelete() {
                    Alpine.store('confirm').show({
                        title: 'Hapus Deteksi #{{ $detection->id }}?',
                        message: 'Deteksi beserta gambar terkait akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.',
                        confirmText: 'Hapus',
                        cancelText: 'Batal',
                        variant: 'danger',
                        onConfirm: () => {
                            this.$refs.deleteForm.submit();
                        }
                    });
                },
            };
        };
    </script>
    @endpush
</x-layouts.app>
