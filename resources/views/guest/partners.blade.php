<x-layouts.guest :title="__('content.partners.page_title')">

    {{-- HEADER --}}
    <section class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 mb-2 uppercase tracking-wider">
                {{ __('content.partners.eyebrow') }}
            </p>
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-3">
                {{ __('content.partners.title') }}
            </h1>
            <p class="text-base text-slate-600 dark:text-slate-400 max-w-2xl">
                {{ __('content.partners.subtitle') }}
            </p>
        </div>
    </section>

    {{-- GRID --}}
    <section class="py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($partners->isEmpty())
                <div class="text-center py-16">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 mb-4">
                        <x-icon name="handshake" class="w-7 h-7" />
                    </div>
                    <p class="text-slate-500 dark:text-slate-400">{{ __('content.partners.empty') }}</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($partners as $p)
                        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 hover:shadow-md transition flex flex-col">
                            <div class="flex items-center gap-4 mb-4">
                                @if ($p->logo_path)
                                    <div class="w-16 h-16 rounded-lg bg-slate-50 dark:bg-slate-800 flex items-center justify-center p-2 shrink-0">
                                        <img src="{{ asset('storage/' . $p->logo_path) }}" alt="{{ $p->name }}" class="max-h-full max-w-full object-contain">
                                    </div>
                                @else
                                    <div class="w-16 h-16 rounded-lg bg-emerald-100 dark:bg-emerald-500/15 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-bold text-2xl shrink-0">
                                        {{ strtoupper(substr($p->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <h2 class="font-bold text-slate-900 dark:text-white leading-snug">{{ $p->name }}</h2>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ __('content.partners.categories.' . $p->category) }}</span>
                                </div>
                            </div>
                            @if ($p->description)
                                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed flex-1">{{ $p->description }}</p>
                            @endif
                            @if ($p->website)
                                <a href="{{ $p->website }}" target="_blank" rel="noopener"
                                   class="mt-4 inline-flex items-center gap-1.5 text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:underline">
                                    {{ __('content.partners.visit_website') }}
                                    <x-icon name="external-link" class="w-3.5 h-3.5" />
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

</x-layouts.guest>
