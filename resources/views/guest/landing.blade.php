<x-layouts.guest :title="__('landing.hero.title_line1') . ' ' . __('landing.hero.title_line2')">

    {{-- ============================================================
         HERO SECTION
         ============================================================ --}}
    <section class="relative min-h-[600px] lg:min-h-[700px] flex items-center overflow-hidden">

        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/hero-edelweiss.jpg') }}"
                 alt="Padang Edelweiss Jawa di pegunungan"
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
         R&D PREVIEW SECTION (tim peneliti)
         ============================================================ --}}
    @if ($researchers->isNotEmpty())
    <section class="py-16 lg:py-24 bg-slate-50 dark:bg-slate-900/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center mb-12">
                <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 mb-2 uppercase tracking-wider">
                    {{ __('landing.research.eyebrow') }}
                </p>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-3">
                    {{ __('landing.research.title') }}
                </h2>
                <p class="text-base text-slate-600 dark:text-slate-400">
                    {{ __('landing.research.subtitle') }}
                </p>
            </div>

            <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach ($researchers->take(6) as $person)
                    <a href="{{ route('guest.research') }}" class="text-center group">
                        @if ($person->photo_path)
                            <img src="{{ asset('storage/' . $person->photo_path) }}"
                                 alt="{{ $person->name }}"
                                 loading="lazy"
                                 class="w-20 h-20 sm:w-24 sm:h-24 rounded-full object-cover mx-auto mb-3 ring-4 ring-white dark:ring-slate-800 shadow-sm group-hover:ring-emerald-100 dark:group-hover:ring-emerald-500/20 transition">
                        @else
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full mx-auto mb-3 ring-4 ring-white dark:ring-slate-800 shadow-sm bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center text-white text-2xl font-bold group-hover:ring-emerald-100 dark:group-hover:ring-emerald-500/20 transition">
                                {{ strtoupper(mb_substr($person->name, 0, 1)) }}
                            </div>
                        @endif
                        <p class="text-xs font-medium text-slate-900 dark:text-white leading-tight line-clamp-2">{{ $person->name }}</p>
                        @if ($person->role)
                            <p class="text-[11px] text-emerald-600 dark:text-emerald-400 mt-0.5">{{ $person->role }}</p>
                        @endif
                    </a>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('guest.research') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/10 transition">
                    {{ __('landing.research.view_all') }}
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================================
         GALLERY PREVIEW SECTION
         ============================================================ --}}
    @if ($galleries->isNotEmpty())
    <section class="py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center mb-12">
                <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 mb-2 uppercase tracking-wider">
                    {{ __('landing.gallery.eyebrow') }}
                </p>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-3">
                    {{ __('landing.gallery.title') }}
                </h2>
                <p class="text-base text-slate-600 dark:text-slate-400">
                    {{ __('landing.gallery.subtitle') }}
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
                @foreach ($galleries as $g)
                    <div class="group relative aspect-[4/3] rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800">
                        <img src="{{ asset('storage/' . $g->image_path) }}"
                             alt="{{ $g->title }}"
                             loading="lazy"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition flex items-end p-3">
                            <span class="text-white text-sm font-medium">{{ $g->title }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('guest.gallery') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/10 transition">
                    {{ __('landing.gallery.view_all') }}
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================================
         PARTNERS PREVIEW SECTION
         ============================================================ --}}
    <section class="py-16 lg:py-24 bg-slate-50 dark:bg-slate-900/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center mb-12">
                <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 mb-2 uppercase tracking-wider">
                    {{ __('landing.partners.eyebrow') }}
                </p>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-3">
                    {{ __('landing.partners.title') }}
                </h2>
                <p class="text-base text-slate-600 dark:text-slate-400">
                    {{ __('landing.partners.subtitle') }}
                </p>
            </div>

            @if ($partners->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 items-center">
                    @foreach ($partners as $p)
                        <div class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:shadow-md transition aspect-square">
                            @if ($p->logo_path)
                                <img src="{{ asset('storage/' . $p->logo_path) }}"
                                     alt="{{ $p->name }}"
                                     loading="lazy"
                                     class="max-h-12 max-w-full object-contain mb-2">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-emerald-100 dark:bg-emerald-500/15 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-bold text-lg mb-2">
                                    {{ strtoupper(substr($p->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="text-xs text-center text-slate-600 dark:text-slate-400 leading-tight line-clamp-2">{{ $p->name }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-10">
                    <a href="{{ route('guest.partners') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/10 transition">
                        {{ __('landing.partners.view_all') }}
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </a>
                </div>
            @else
                {{-- Placeholder: tampil sampai partner diisi via admin panel --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 items-center">
                    @for ($i = 0; $i < 6; $i++)
                        <div class="flex flex-col items-center justify-center p-4 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 bg-white/50 dark:bg-slate-900/40 aspect-square">
                            <div class="w-12 h-12 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-300 dark:text-slate-600 mb-2">
                                <x-icon name="handshake" class="w-6 h-6" />
                            </div>
                            <span class="text-xs text-center text-slate-400 dark:text-slate-500 leading-tight">{{ __('landing.partners.placeholder') }}</span>
                        </div>
                    @endfor
                </div>
                <p class="text-center text-sm text-slate-400 dark:text-slate-500 mt-8">
                    {{ __('landing.partners.coming_soon') }}
                </p>
            @endif
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
