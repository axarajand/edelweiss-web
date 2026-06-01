<x-layouts.app :title="__('learning.page_title')">
    <x-slot:header>{{ __('learning.title') }}</x-slot:header>

    <div x-data="{ tab: 'kondisi' }" class="space-y-6">

        <div class="inline-flex p-1 rounded-xl bg-slate-100 dark:bg-slate-800 self-start flex-wrap">
            <button @click="tab = 'kondisi'"
                    :class="tab === 'kondisi' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400'"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition">
                {{ __('learning.tab.conditions') }}
            </button>
            <button @click="tab = 'model'"
                    :class="tab === 'model' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400'"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition">
                {{ __('learning.tab.system') }}
            </button>
        </div>

        {{-- TAB 1: Mengenal Kondisi --}}
        <div x-show="tab === 'kondisi'" class="space-y-6">

            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                    {{ __('learning.conditions.card_title') }}
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    {!! __('learning.conditions.card_desc') !!}
                </p>
            </div>

            @php
                $kondisi = [
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
                @foreach ($kondisi as $i => $f)
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

        {{-- TAB 2: Tentang Sistem --}}
        <div x-show="tab === 'model'" x-cloak class="space-y-6">

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
                            <dt class="text-slate-500 dark:text-slate-400">{{ __('learning.system.data_location') }}</dt>
                            <dd class="font-medium text-slate-900 dark:text-white text-right">{{ __('learning.system.data_location_value') }}</dd>
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

                {{-- Tools --}}
                <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-900 dark:text-white">{{ __('learning.system.tools_title') }}</h3>
                    </div>
                    <dl class="space-y-2 text-sm">
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
                        <div class="flex justify-between gap-3 py-1.5">
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
                        <p class="text-xs text-slate-600 dark:text-slate-400">
                            {{ __('learning.system.pipeline_step1_desc') }}
                        </p>
                    </div>

                    <div class="hidden md:flex items-center text-slate-400 dark:text-slate-600">&rarr;</div>

                    <div class="flex-1 p-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <div class="font-bold text-emerald-600 dark:text-emerald-400 mb-1">{{ __('learning.system.pipeline_step2_title') }}</div>
                        <p class="text-xs text-slate-600 dark:text-slate-400">
                            {{ __('learning.system.pipeline_step2_desc') }}
                        </p>
                    </div>

                    <div class="hidden md:flex items-center text-slate-400 dark:text-slate-600">&rarr;</div>

                    <div class="flex-1 p-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <div class="font-bold text-emerald-600 dark:text-emerald-400 mb-1">{{ __('learning.system.pipeline_step3_title') }}</div>
                        <p class="text-xs text-slate-600 dark:text-slate-400">
                            {{ __('learning.system.pipeline_step3_desc') }}
                        </p>
                    </div>

                    <div class="hidden md:flex items-center text-slate-400 dark:text-slate-600">&rarr;</div>

                    <div class="flex-1 p-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <div class="font-bold text-emerald-600 dark:text-emerald-400 mb-1">{{ __('learning.system.pipeline_step4_title') }}</div>
                        <p class="text-xs text-slate-600 dark:text-slate-400">
                            {{ __('learning.system.pipeline_step4_desc') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
