<x-layouts.guest :title="__('detection.page_title')">

    <div @dragover.window.prevent @drop.window.prevent x-data="detectionPage()"
         class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">

        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-2">
                {{ __('detection.h1_guest') }}
            </h1>
            <p class="text-base text-slate-600 dark:text-slate-400">
                {{ __('detection.subtitle') }}
            </p>
        </div>

        <div class="inline-flex p-1 rounded-xl bg-slate-100 dark:bg-slate-800 mb-6">
            <button @click="mode = 'upload'"
                    :class="mode === 'upload'
                        ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm'
                        : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition">
                <x-icon name="upload" class="w-4 h-4" />
                {{ __('detection.mode.upload') }}
            </button>
            <button @click="mode = 'camera'"
                    :class="mode === 'camera'
                        ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm'
                        : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition">
                <x-icon name="camera" class="w-4 h-4" />
                {{ __('detection.mode.camera') }}
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">

                {{-- UPLOAD MODE --}}
                <div x-show="mode === 'upload'" x-transition>
                    <div class="p-5 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="font-semibold text-slate-900 dark:text-white mb-1">{{ __('detection.mode.upload') }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {!! __('detection.upload.caption') !!}
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
                                <span x-show="!isDragging">{{ __('detection.upload.click_to_select') }}</span>
                                <span x-show="isDragging" x-cloak>{{ __('detection.upload.release_file') }}</span>
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1" x-show="!isDragging">
                                {{ __('detection.upload.drag_drop_hint') }}
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
                                {{ __('detection.upload.change_image') }}
                            </label>
                        </div>

                        <div x-show="previewUrl" x-cloak x-transition
                             @dragover.prevent="isDragging = true"
                             @dragleave.prevent="isDragging = false"
                             @drop.prevent="handleFileDrop($event)"
                             class="relative bg-slate-100 dark:bg-slate-800 rounded-xl overflow-hidden aspect-video">
                            <img id="previewImg" :src="previewUrl" class="w-full h-full object-contain">
                            <canvas id="uploadCanvas" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>

                            {{-- AI Scan Effect overlay --}}
                            <div x-show="isLoading"
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="absolute inset-0 pointer-events-none ai-scan-overlay">
                                <div class="absolute inset-0 bg-emerald-950/20"></div>
                                <div class="ai-bracket ai-bracket-tl"></div>
                                <div class="ai-bracket ai-bracket-tr"></div>
                                <div class="ai-bracket ai-bracket-bl"></div>
                                <div class="ai-bracket ai-bracket-br"></div>
                                <div class="ai-scan-line"></div>
                                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 px-3 py-1.5 rounded-full bg-black/70 backdrop-blur text-emerald-400 text-xs font-medium flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    {{ __('detection.upload.ai_analyzing') }}
                                </div>
                            </div>

                            <div x-show="isDragging" x-cloak
                                 class="absolute inset-0 bg-emerald-500/20 border-4 border-dashed border-emerald-500 backdrop-blur-sm flex flex-col items-center justify-center pointer-events-none">
                                <x-icon name="upload" class="w-10 h-10 text-emerald-600 mb-2" />
                                <p class="text-base font-medium text-emerald-900 dark:text-emerald-300">{{ __('detection.upload.release_to_change') }}</p>
                            </div>
                        </div>

                        <button @click="detectUpload()" x-show="currentFile" x-cloak :disabled="isLoading"
                                class="mt-4 w-full sm:w-auto px-5 py-2.5 rounded-lg bg-emerald-600 text-white font-medium
                                       hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed transition
                                       inline-flex items-center justify-center gap-2">
                            <template x-if="!isLoading">
                                <span class="inline-flex items-center gap-2">
                                    <x-icon name="scan" class="w-4 h-4" />
                                    {{ __('detection.upload.detect_button') }}
                                </span>
                            </template>
                            <template x-if="isLoading">
                                <span class="inline-flex items-center gap-2">
                                    <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-opacity="0.25"/>
                                        <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                                    </svg>
                                    {{ __('detection.upload.processing') }}
                                </span>
                            </template>
                        </button>
                    </div>
                </div>

                {{-- CAMERA MODE --}}
                <div x-show="mode === 'camera'" x-cloak x-transition>
                    <div class="p-5 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="font-semibold text-slate-900 dark:text-white mb-1">{{ __('detection.camera.realtime_title') }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {!! __('detection.camera.realtime_subtitle') !!}
                        </p>
                    </div>

                    <div class="p-5">
                        <div id="camera-container" class="relative bg-black rounded-xl overflow-hidden aspect-video">
                            <video id="video" class="w-full h-full object-contain" autoplay playsinline muted></video>
                            <canvas id="overlay" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>

                            <div x-show="!cameraActive" x-cloak
                                 class="absolute inset-0 flex flex-col items-center justify-center text-slate-500">
                                <x-icon name="camera" class="w-12 h-12 mb-2 opacity-50" />
                                <p class="text-sm">{{ __('detection.camera.inactive') }}</p>
                            </div>

                            <div x-show="cameraActive" x-cloak
                                 class="absolute top-3 left-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-600/90 text-white text-xs font-medium backdrop-blur">
                                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                                LIVE
                            </div>

                            <div x-show="cameraActive && results.length > 0 && !isFullscreen" x-cloak
                                 class="absolute top-3 right-12 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-600/90 text-white text-xs font-medium backdrop-blur">
                                <span x-text="results.length + ' ' + (window.lang?.objects_detected || 'objek terdeteksi')"></span>
                            </div>

                            <div x-show="cameraActive && results.length > 0 && isFullscreen" x-cloak
                                 class="absolute top-4 left-1/2 -translate-x-1/2 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-600/90 text-white text-sm font-medium backdrop-blur">
                                <span x-text="results.length + ' ' + (window.lang?.objects_detected || 'objek terdeteksi')"></span>
                            </div>

                            <button x-show="cameraActive" x-cloak
                                    @click="toggleFullscreen()"
                                    type="button"
                                    class="camera-fullscreen-toggle"
                                    :title="isFullscreen ? (window.lang?.exit_fullscreen || 'Exit Fullscreen') : (window.lang?.enter_fullscreen || 'Fullscreen')"
                                    :aria-label="isFullscreen ? (window.lang?.exit_fullscreen || 'Exit Fullscreen') : (window.lang?.enter_fullscreen || 'Fullscreen')">
                                <svg x-show="!isFullscreen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 4H4v6M4 4l6 6" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 4h6v6M20 4l-6 6" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 20H4v-6M4 20l6-6" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 20h6v-6M20 20l-6-6" />
                                </svg>
                                <svg x-show="isFullscreen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4l5 5m0 0H5m4 0V5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 4l-5 5m0 0h4m-4 0V5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 20l5-5m0 0H5m4 0v4" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 20l-5-5m0 0h4m-4 0v4" />
                                </svg>
                            </button>

                            <div x-show="isCapturing" x-cloak
                                 x-transition:enter="transition duration-100"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition duration-300"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="absolute inset-0 bg-white pointer-events-none"></div>

                            <div x-show="isFullscreen && cameraActive" x-cloak
                                 class="camera-fullscreen-toolbar">
                                <button @click="capturePhoto()"
                                        x-show="results.length > 0"
                                        x-cloak
                                        :disabled="isCapturing"
                                        type="button"
                                        class="rounded-full bg-blue-600 text-white font-medium hover:bg-blue-700
                                               disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center gap-2">
                                    <template x-if="!isCapturing">
                                        <span class="inline-flex items-center gap-2">
                                            <x-icon name="camera" class="w-4 h-4" />
                                            {{ __('detection.camera.capture') }}
                                        </span>
                                    </template>
                                    <template x-if="isCapturing">
                                        <span class="inline-flex items-center gap-2">
                                            <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-opacity="0.25"/>
                                                <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                                            </svg>
                                            {{ __('detection.camera.capturing') }}
                                        </span>
                                    </template>
                                </button>

                                <button @click="stopCamera()"
                                        type="button"
                                        class="rounded-full bg-rose-600 text-white font-medium hover:bg-rose-700 inline-flex items-center gap-2">
                                    {{ __('detection.camera.stop') }}
                                </button>

                                <button @click="exitFullscreen()"
                                        type="button"
                                        class="rounded-full bg-white/20 text-white font-medium hover:bg-white/30 inline-flex items-center gap-2 backdrop-blur">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 9V5m0 0H5m4 0L4 4m11 5h4m0 0V5m0 4l5-5M9 15v4m0 0H5m4 0l-5 5m11-5h4m0 0v4m0-4l5 5"/>
                                    </svg>
                                    {{ __('detection.camera.exit_fullscreen') }}
                                </button>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-col sm:flex-row gap-2 camera-normal-controls"
                             :class="{ 'hidden': isFullscreen }">
                            <button @click="startCamera()" :disabled="cameraActive"
                                    class="px-5 py-2.5 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700
                                           disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center justify-center gap-2">
                                <x-icon name="camera" class="w-4 h-4" />
                                {{ __('detection.camera.start') }}
                            </button>

                            <button @click="capturePhoto()"
                                    x-show="cameraActive && results.length > 0"
                                    x-cloak
                                    :disabled="isCapturing"
                                    class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700
                                           disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center justify-center gap-2">
                                <template x-if="!isCapturing">
                                    <span class="inline-flex items-center gap-2">
                                        <x-icon name="camera" class="w-4 h-4" />
                                        {{ __('detection.camera.capture_save') }}
                                    </span>
                                </template>
                                <template x-if="isCapturing">
                                    <span class="inline-flex items-center gap-2">
                                        <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-opacity="0.25"/>
                                            <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                                        </svg>
                                        {{ __('detection.camera.capturing') }}
                                    </span>
                                </template>
                            </button>

                            <div x-show="cameraActive && results.length === 0" x-cloak
                                 class="px-4 py-2.5 text-sm text-slate-500 dark:text-slate-400 italic">
                                {{ __('detection.camera.point_camera') }}
                            </div>

                            <button @click="stopCamera()" :disabled="!cameraActive"
                                    class="px-5 py-2.5 rounded-lg bg-rose-600 text-white font-medium hover:bg-rose-700
                                           disabled:opacity-50 disabled:cursor-not-allowed">
                                {{ __('detection.camera.stop_short') }}
                            </button>
                            <span class="ml-auto self-center text-sm text-slate-500 dark:text-slate-400" x-text="cameraStatus"></span>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('detection.result.title') }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        <span x-text="results.length"></span> <span x-text="window.lang?.objects_detected || 'objek terdeteksi'"></span>
                    </p>
                </div>

                <div class="p-5">
                    <template x-if="results.length === 0">
                        <div class="flex flex-col items-center justify-center py-10 text-center">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-3">
                                <x-icon name="inbox" class="w-6 h-6 text-slate-400" />
                            </div>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('detection.result.empty_title') }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                {{ __('detection.result.empty_subtitle') }}
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
        <div class="mt-6 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5">
            <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">{{ __('detection.result.conditions_title') }}</h4>
            <div class="flex flex-wrap gap-2">
                <x-fase-badge fase="Mekar" />
                <x-fase-badge fase="Sangat_Mekar" />
                <x-fase-badge fase="Penyemaian" />
            </div>
        </div>

        @guest
            <div class="mt-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30">
                <div class="flex items-start gap-3">
                    <x-icon name="check-circle" class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" />
                    <div>
                        <p class="text-sm font-medium text-emerald-900 dark:text-emerald-300">
                            {{ __('detection.guest_cta_title') }}
                        </p>
                        <p class="text-xs text-emerald-700 dark:text-emerald-400 mt-0.5">
                            <a href="{{ route('admin.register') }}" class="font-medium underline hover:no-underline">{{ __('detection.guest_cta_register') }}</a>
                            {{ __('detection.guest_cta_text') }}
                        </p>
                    </div>
                </div>
            </div>
        @endguest
    </div>
</x-layouts.guest>
