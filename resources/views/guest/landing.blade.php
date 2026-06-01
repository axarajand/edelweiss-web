<x-layouts.guest :title="__('landing.hero.title_line1') . ' ' . __('landing.hero.title_line2')">

    {{-- ============================================================
         HERO SECTION
         ============================================================ --}}
    <section class="relative min-h-[600px] lg:min-h-[700px] flex items-center overflow-hidden">

        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1920&q=80"
                 alt="Pegunungan Indonesia"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/85 to-white/40 dark:from-slate-950/95 dark:via-slate-950/85 dark:to-slate-950/40"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 w-full">
            <div class="max-w-2xl">

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-900 dark:text-white tracking-tight leading-tight mb-6">
                    {{ __('landing.hero.title_line1') }}
                    <span class="block bg-gradient-to-r from-emerald-600 to-green-700 dark:from-emerald-400 dark:to-green-500 bg-clip-text text-transparent">
                        {{ __('landing.hero.title_line2') }}
                    </span>
                </h1>

                <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 leading-relaxed mb-8 max-w-xl">
                    {!! __('landing.hero.description') !!}
                </p>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('guest.detection') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/20">
                        <x-icon name="scan" class="w-5 h-5" />
                        {{ __('landing.hero.cta_primary') }}
                    </a>
                    <a href="#kondisi"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-medium border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                        {{ __('landing.hero.cta_secondary') }}
                    </a>
                </div>

                <div class="mt-10 flex flex-wrap gap-6 items-center text-sm text-slate-500 dark:text-slate-400">
                    <div class="flex items-center gap-2">
                        <x-icon name="check-circle" class="w-4 h-4 text-emerald-500" />
                        <span>{{ __('landing.hero.feature_no_register') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-icon name="check-circle" class="w-4 h-4 text-emerald-500" />
                        <span>{{ __('landing.hero.feature_fast') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-icon name="check-circle" class="w-4 h-4 text-emerald-500" />
                        <span>{{ __('landing.hero.feature_free') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         KONDISI/LABEL SECTION
         ============================================================ --}}
    <section id="kondisi" class="py-16 lg:py-24 bg-slate-50 dark:bg-slate-900/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="max-w-2xl mx-auto text-center mb-12">
                <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 mb-2 uppercase tracking-wider">
                    {{ __('landing.conditions.eyebrow') }}
                </p>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                    {{ __('landing.conditions.title') }}
                </h2>
                <p class="text-base text-slate-600 dark:text-slate-400">
                    {{ __('landing.conditions.subtitle') }}
                </p>
            </div>

            @php
                $kondisi = [
                    ['nama' => 'Mekar', 'desc' => __('landing.conditions.mekar.desc')],
                    ['nama' => 'Sangat_Mekar', 'desc' => __('landing.conditions.sangat_mekar.desc')],
                    ['nama' => 'Penyemaian', 'desc' => __('landing.conditions.penyemaian.desc')],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($kondisi as $i => $k)
                    <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 hover:shadow-md transition">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-sm font-bold text-slate-600 dark:text-slate-300">
                                {{ $i + 1 }}
                            </span>
                            <x-fase-badge :fase="$k['nama']" />
                        </div>
                        <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ __('messages.kondisi.' . $k['nama']) }}</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ $k['desc'] }}
                        </p>
                    </div>
                @endforeach
            </div>

            <p class="mt-8 text-center text-xs text-slate-500 dark:text-slate-400 max-w-2xl mx-auto">
                {{ __('landing.conditions.note') }}
            </p>
        </div>
    </section>

    {{-- ============================================================
         CARA KERJA SECTION
         ============================================================ --}}
    <section class="py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="max-w-2xl mx-auto text-center mb-12">
                <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 mb-2 uppercase tracking-wider">
                    {{ __('landing.how.eyebrow') }}
                </p>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                    {{ __('landing.how.title') }}
                </h2>
                <p class="text-base text-slate-600 dark:text-slate-400">
                    {!! __('landing.how.subtitle') !!}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">

                <div class="relative">
                    <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 mb-5">
                        <x-icon name="upload" class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                        {{ __('landing.how.step1_title') }}
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        {{ __('landing.how.step1_desc') }}
                    </p>
                    <div class="hidden md:block absolute top-6 -right-4 lg:-right-8">
                        <svg class="w-8 h-2 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 32 8">
                            <path d="M0 4h28m-4-4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <div class="relative">
                    <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 mb-5">
                        <x-icon name="scan" class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                        {{ __('landing.how.step2_title') }}
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        {{ __('landing.how.step2_desc') }}
                    </p>
                    <div class="hidden md:block absolute top-6 -right-4 lg:-right-8">
                        <svg class="w-8 h-2 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 32 8">
                            <path d="M0 4h28m-4-4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 mb-5">
                        <x-icon name="check-circle" class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                        {{ __('landing.how.step3_title') }}
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        {!! __('landing.how.step3_desc') !!}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         CTA SECTION
         ============================================================ --}}
    <section class="py-16 lg:py-24 bg-gradient-to-br from-emerald-600 to-green-700 dark:from-emerald-700 dark:to-green-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4 leading-tight">
                {{ __('landing.cta.title') }}
            </h2>
            <p class="text-base sm:text-lg text-emerald-50 mb-8 max-w-2xl mx-auto">
                {!! __('landing.cta.subtitle') !!}
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('guest.detection') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-3 rounded-lg bg-white text-emerald-700 font-semibold hover:bg-emerald-50 transition shadow-lg">
                    <x-icon name="scan" class="w-5 h-5" />
                    {{ __('landing.cta.primary') }}
                </a>
                <a href="{{ route('admin.register') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-3 rounded-lg bg-emerald-700/40 text-white font-semibold border-2 border-white/30 hover:bg-emerald-700/60 transition backdrop-blur">
                    {{ __('landing.cta.secondary') }}
                </a>
            </div>
            <p class="mt-6 text-sm text-emerald-100">
                {{ __('landing.cta.note') }}
            </p>
        </div>
    </section>

</x-layouts.guest>
