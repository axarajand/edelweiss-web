<x-layouts.app title="Deteksi Kesehatan - Edelweiss Detection">
    <x-slot:header>Deteksi Kesehatan</x-slot:header>

    <div @dragover.window.prevent @drop.window.prevent x-data="detectionPage()" class="space-y-6">

        {{-- Mode tabs --}}
        <div class="inline-flex p-1 rounded-xl bg-slate-100 dark:bg-slate-800">
            <button @click="mode = 'upload'"
                    :class="mode === 'upload'
                        ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm'
                        : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition">
                <x-icon name="upload" class="w-4 h-4" />
                Upload Gambar
            </button>
            <button @click="mode = 'camera'"
                    :class="mode === 'camera'
                        ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm'
                        : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition">
                <x-icon name="camera" class="w-4 h-4" />
                Kamera Real-time
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">

                {{-- UPLOAD MODE --}}
                <div x-show="mode === 'upload'" x-transition>
                    <div class="p-5 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Upload Gambar</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Pilih foto bunga Edelweiss yang ingin dicek kesehatannya (.jpg, .png, .webp &middot; maks 10MB)
                        </p>
                    </div>

                    <div class="p-5">
                        <input id="fileInput" type="file" accept="image/*" class="hidden"
                               @change="handleFileSelect($event)">

                        <label x-show="!currentFile" for="fileInput"
                               @dragover.prevent="isDragging = true"
                               @dragleave.prevent="isDragging = false"
                               @drop.prevent="handleFileDrop($event)"
                               :class="isDragging
                                   ? 'border-emerald-500 bg-emerald-50/70 dark:bg-emerald-500/10'
                                   : 'border-slate-300 dark:border-slate-700 hover:border-emerald-500 dark:hover:border-emerald-500 hover:bg-emerald-50/50 dark:hover:bg-emerald-500/5'"
                               class="block cursor-pointer border-2 border-dashed rounded-xl px-6 py-10 text-center transition">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                <x-icon name="upload" class="w-6 h-6 text-slate-500 dark:text-slate-400" />
                            </div>
                            <p class="text-sm font-medium text-slate-900 dark:text-white">
                                <span x-show="!isDragging">Klik untuk pilih gambar</span>
                                <span x-show="isDragging" x-cloak>Lepaskan file di sini</span>
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1" x-show="!isDragging">
                                atau drag &amp; drop file di sini
                            </p>
                        </label>

                        <div x-show="currentFile" x-cloak class="flex items-center justify-between gap-3 mb-4">
                            <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400 min-w-0">
                                <x-icon name="check-circle" class="w-4 h-4 text-emerald-500 shrink-0" />
                                <span class="truncate" x-text="currentFile?.name"></span>
                            </div>
                            <label for="fileInput"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-medium hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer transition shrink-0">
                                <x-icon name="upload" class="w-3.5 h-3.5" />
                                Ganti Gambar
                            </label>
                        </div>

                        <div x-show="previewUrl" x-cloak x-transition
                             @dragover.prevent="isDragging = true"
                             @dragleave.prevent="isDragging = false"
                             @drop.prevent="handleFileDrop($event)"
                             class="relative bg-slate-100 dark:bg-slate-800 rounded-xl overflow-hidden aspect-video">
                            <img id="previewImg" :src="previewUrl" class="w-full h-full object-contain">
                            <canvas id="uploadCanvas" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>

                            {{-- AI Scan Effect overlay - muncul saat isLoading --}}
                            <div x-show="isLoading"
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="absolute inset-0 pointer-events-none ai-scan-overlay">
                                {{-- Dark tint --}}
                                <div class="absolute inset-0 bg-emerald-950/20"></div>

                                {{-- 4 Corner brackets --}}
                                <div class="ai-bracket ai-bracket-tl"></div>
                                <div class="ai-bracket ai-bracket-tr"></div>
                                <div class="ai-bracket ai-bracket-bl"></div>
                                <div class="ai-bracket ai-bracket-br"></div>

                                {{-- Scan line bergerak --}}
                                <div class="ai-scan-line"></div>

                                {{-- Label di bawah --}}
                                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 px-3 py-1.5 rounded-full bg-black/70 backdrop-blur text-emerald-400 text-xs font-medium flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    AI Sedang Menganalisis...
                                </div>
                            </div>

                            <div x-show="isDragging" x-cloak
                                 class="absolute inset-0 bg-emerald-500/20 border-4 border-dashed border-emerald-500 backdrop-blur-sm flex flex-col items-center justify-center pointer-events-none">
                                <x-icon name="upload" class="w-10 h-10 text-emerald-600 mb-2" />
                                <p class="text-base font-medium text-emerald-900 dark:text-emerald-300">Lepaskan untuk ganti gambar</p>
                            </div>
                        </div>

                        <button @click="detectUpload()" x-show="currentFile" x-cloak :disabled="isLoading"
                                class="mt-4 w-full sm:w-auto px-5 py-2.5 rounded-lg bg-emerald-600 text-white font-medium
                                       hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed transition
                                       inline-flex items-center justify-center gap-2">
                            <template x-if="!isLoading">
                                <span class="inline-flex items-center gap-2">
                                    <x-icon name="scan" class="w-4 h-4" />
                                    Deteksi Sekarang
                                </span>
                            </template>
                            <template x-if="isLoading">
                                <span class="inline-flex items-center gap-2">
                                    <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-opacity="0.25"/>
                                        <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                                    </svg>
                                    Memproses...
                                </span>
                            </template>
                        </button>
                    </div>
                </div>

                {{-- CAMERA MODE --}}
                <div x-show="mode === 'camera'" x-cloak x-transition>
                    <div class="p-5 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="font-semibold text-slate-900 dark:text-white mb-1">Kamera Real-time</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Tombol <strong>Potret</strong> akan aktif begitu bunga Edelweiss terdeteksi di layar kamera.
                        </p>
                    </div>

                    <div class="p-5">
                        <div class="relative bg-black rounded-xl overflow-hidden aspect-video">
                            <video id="video" class="w-full h-full object-contain" autoplay playsinline muted></video>
                            <canvas id="overlay" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>

                            <div x-show="!cameraActive" x-cloak
                                 class="absolute inset-0 flex flex-col items-center justify-center text-slate-500">
                                <x-icon name="camera" class="w-12 h-12 mb-2 opacity-50" />
                                <p class="text-sm">Kamera belum aktif</p>
                            </div>

                            <div x-show="cameraActive" x-cloak
                                 class="absolute top-3 left-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-600/90 text-white text-xs font-medium backdrop-blur">
                                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                                LIVE
                            </div>

                            {{-- Badge "objek terdeteksi" — kanan atas saat kamera aktif --}}
                            <div x-show="cameraActive && results.length > 0" x-cloak
                                 class="absolute top-3 right-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-600/90 text-white text-xs font-medium backdrop-blur">
                                <span x-text="`${results.length} objek terdeteksi`"></span>
                            </div>

                            {{-- Capturing flash overlay --}}
                            <div x-show="isCapturing" x-cloak
                                 x-transition:enter="transition duration-100"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition duration-300"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="absolute inset-0 bg-white pointer-events-none"></div>
                        </div>

                        <div class="mt-4 flex flex-col sm:flex-row gap-2">
                            <button @click="startCamera()" :disabled="cameraActive"
                                    class="px-5 py-2.5 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700
                                           disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center justify-center gap-2">
                                <x-icon name="camera" class="w-4 h-4" />
                                Mulai Kamera
                            </button>

                            {{-- Item 7: Tombol Potret hanya muncul saat ada objek terdeteksi --}}
                            <button @click="capturePhoto()"
                                    x-show="cameraActive && results.length > 0"
                                    x-cloak
                                    :disabled="isCapturing"
                                    class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700
                                           disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center justify-center gap-2">
                                <template x-if="!isCapturing">
                                    <span class="inline-flex items-center gap-2">
                                        <x-icon name="camera" class="w-4 h-4" />
                                        Potret &amp; Simpan
                                    </span>
                                </template>
                                <template x-if="isCapturing">
                                    <span class="inline-flex items-center gap-2">
                                        <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-opacity="0.25"/>
                                            <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                                        </svg>
                                        Menyimpan...
                                    </span>
                                </template>
                            </button>

                            {{-- Hint saat kamera aktif tapi belum ada deteksi --}}
                            <div x-show="cameraActive && results.length === 0" x-cloak
                                 class="px-4 py-2.5 text-sm text-slate-500 dark:text-slate-400 italic">
                                Arahkan kamera ke bunga Edelweiss...
                            </div>

                            <button @click="stopCamera()" :disabled="!cameraActive"
                                    class="px-5 py-2.5 rounded-lg bg-rose-600 text-white font-medium hover:bg-rose-700
                                           disabled:opacity-50 disabled:cursor-not-allowed">
                                Stop
                            </button>
                            <span class="ml-auto self-center text-sm text-slate-500 dark:text-slate-400" x-text="cameraStatus"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Result panel --}}
            <aside class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Hasil Deteksi</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        <span x-text="results.length"></span> objek terdeteksi
                    </p>
                </div>

                <div class="p-5">
                    <template x-if="results.length === 0">
                        <div class="flex flex-col items-center justify-center py-10 text-center">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-3">
                                <x-icon name="inbox" class="w-6 h-6 text-slate-400" />
                            </div>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Belum ada hasil</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                Upload gambar atau aktifkan kamera untuk mulai mendeteksi.
                            </p>
                        </div>
                    </template>

                    <div class="space-y-2">
                        <template x-for="(det, i) in results" :key="i">
                            <div class="flex items-center justify-between gap-3 p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0"
                                          :style="`background:${getColorForLabel(det.label)}`"></span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white truncate"
                                           x-text="`#${i+1} ${(det.label || '').replace(/_/g, ' ')}`"></p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            MLP: <span x-text="((det.mlp_confidence ?? 0) * 100).toFixed(1) + '%'"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </aside>
        </div>

        {{-- Item 3: "Kondisi Kesehatan Edelweiss" --}}
        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5">
            <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">Kondisi Kesehatan Edelweiss</h4>
            <div class="flex flex-wrap gap-2">
                <x-fase-badge fase="Mekar" />
                <x-fase-badge fase="Sangat_Mekar" />
                <x-fase-badge fase="Penyemaian" />
            </div>
        </div>
    </div>
</x-layouts.app>
