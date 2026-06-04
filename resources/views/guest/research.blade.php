<x-layouts.guest :title="__('content.research.page_title')">

    {{-- HERO / PROJECT --}}
    <section class="border-b border-slate-200 dark:border-slate-800 bg-gradient-to-br from-emerald-50 to-white dark:from-emerald-500/5 dark:to-slate-950">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 mb-2 uppercase tracking-wider">
                {{ __('content.research.eyebrow') }}
            </p>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 dark:text-white mb-3 leading-tight">
                {{ __('content.research.project_title') }}
            </h1>
            <p class="inline-flex items-center gap-2 text-sm text-emerald-700 dark:text-emerald-400 font-medium mb-5">
                <x-icon name="beaker" class="w-4 h-4" />
                {{ __('content.research.project_scheme') }}
            </p>
            <p class="text-base text-slate-600 dark:text-slate-300 leading-relaxed max-w-3xl">
                {{ __('content.research.project_summary') }}
            </p>
        </div>
    </section>

    {{-- HIGHLIGHTS --}}
    <section class="py-12 lg:py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white mb-8">
                {{ __('content.research.highlights_title') }}
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5">
                    <div class="w-11 h-11 rounded-xl bg-emerald-100 dark:bg-emerald-500/15 flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-4">
                        <x-icon name="scan" class="w-5 h-5" />
                    </div>
                    <h3 class="font-bold text-slate-900 dark:text-white mb-1.5">{{ __('content.research.hl_method_title') }}</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ __('content.research.hl_method_desc') }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5">
                    <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-500/15 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-4">
                        <x-icon name="database" class="w-5 h-5" />
                    </div>
                    <h3 class="font-bold text-slate-900 dark:text-white mb-1.5">{{ __('content.research.hl_dataset_title') }}</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ __('content.research.hl_dataset_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- TEAM (highlighted) --}}
    <section class="py-12 lg:py-16 bg-slate-50 dark:bg-slate-900/30 border-y border-slate-200 dark:border-slate-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white mb-3">
                    {{ __('content.research.team_title') }}
                </h2>
                <p class="text-base text-slate-600 dark:text-slate-400">
                    {{ __('content.research.team_subtitle') }}
                </p>
            </div>

            @if ($researchers->isEmpty())
                <p class="text-center text-slate-500 dark:text-slate-400">{{ __('content.research.team_empty') }}</p>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-5 max-w-4xl mx-auto">
                    @foreach ($researchers as $person)
                        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 text-center hover:shadow-md transition">
                            @if ($person->photo_path)
                                <img src="{{ asset('storage/' . $person->photo_path) }}"
                                     alt="{{ $person->name }}"
                                     class="w-24 h-24 rounded-full object-cover mx-auto mb-4 ring-4 ring-emerald-100 dark:ring-emerald-500/20">
                            @else
                                <div class="w-24 h-24 rounded-full mx-auto mb-4 ring-4 ring-emerald-100 dark:ring-emerald-500/20 bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center text-white text-3xl font-bold">
                                    {{ strtoupper(mb_substr($person->name, 0, 1)) }}
                                </div>
                            @endif
                            <h3 class="font-bold text-slate-900 dark:text-white text-sm leading-snug">{{ $person->name }}</h3>
                            @if ($person->role)
                                <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400 mt-1">{{ $person->role }}</p>
                            @endif
                            @if ($person->affiliation)
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $person->affiliation }}</p>
                            @endif
                            @if ($person->scholar_url)
                                <a href="{{ $person->scholar_url }}" target="_blank" rel="noopener"
                                   class="mt-3 inline-flex items-center gap-1 text-xs text-emerald-600 dark:text-emerald-400 hover:underline">
                                    Profil <x-icon name="external-link" class="w-3 h-3" />
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- LOKASI DATASET + penjelasan awam --}}
    <section class="py-12 lg:py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
                <div class="flex items-center gap-2 mb-4">
                    <x-icon name="map-pin" class="w-5 h-5 text-rose-500" />
                    <h3 class="font-bold text-slate-900 dark:text-white">{{ __('guest_research.location_title') }}</h3>
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-5">
                    {{ __('guest_research.location_desc') }}
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex items-center justify-between gap-3 p-4 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
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
                    <div class="flex items-center justify-between gap-3 p-4 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
                    <div class="w-11 h-11 rounded-xl bg-emerald-100 dark:bg-emerald-500/15 flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-4">
                        <x-icon name="scan" class="w-5 h-5" />
                    </div>
                    <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ __('guest_research.stage1_title') }}</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ __('guest_research.stage1_desc') }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
                    <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-500/15 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-4">
                        <x-icon name="check-circle" class="w-5 h-5" />
                    </div>
                    <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ __('guest_research.stage2_title') }}</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ __('guest_research.stage2_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

</x-layouts.guest>
