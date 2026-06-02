<x-layouts.guest :title="__('content.gallery.page_title')">

    {{-- HEADER --}}
    <section class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 mb-2 uppercase tracking-wider">
                {{ __('content.gallery.eyebrow') }}
            </p>
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-3">
                {{ __('content.gallery.title') }}
            </h1>
            <p class="text-base text-slate-600 dark:text-slate-400 max-w-2xl">
                {{ __('content.gallery.subtitle') }}
            </p>
        </div>
    </section>

    {{-- GRID --}}
    <section class="py-12 lg:py-16"
             x-data="{ open: false, current: {} }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($galleries->isEmpty())
                <div class="text-center py-16">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 mb-4">
                        <x-icon name="photo" class="w-7 h-7" />
                    </div>
                    <p class="text-slate-500 dark:text-slate-400">{{ __('content.gallery.empty') }}</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                    @foreach ($galleries as $g)
                        <button type="button"
                                @click="open = true; current = {
                                    src: '{{ asset('storage/' . $g->image_path) }}',
                                    title: @js($g->title),
                                    desc: @js($g->description),
                                    location: @js($g->location),
                                    category: @js(__('content.gallery.categories.' . $g->category))
                                }"
                                class="group relative aspect-square rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 text-left">
                            <img src="{{ asset('storage/' . $g->image_path) }}"
                                 alt="{{ $g->title }}"
                                 loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition flex items-end p-3">
                                <div>
                                    <p class="text-white text-sm font-medium leading-tight">{{ $g->title }}</p>
                                    @if ($g->location)
                                        <p class="text-white/70 text-xs mt-0.5">{{ $g->location }}</p>
                                    @endif
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- LIGHTBOX --}}
            <div x-show="open"
                 x-cloak
                 @keydown.escape.window="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
                 @click.self="open = false">
                <div class="relative max-w-4xl w-full bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-2xl"
                     @click.stop>
                    <button @click="open = false"
                            class="absolute top-3 right-3 z-10 p-2 rounded-full bg-black/40 text-white hover:bg-black/60 transition">
                        <x-icon name="x" class="w-5 h-5" />
                    </button>
                    <img :src="current.src" :alt="current.title" class="w-full max-h-[70vh] object-contain bg-slate-100 dark:bg-slate-950">
                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white" x-text="current.title"></h3>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-400" x-text="current.category"></span>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-2" x-show="current.location" x-text="current.location"></p>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed" x-show="current.desc" x-text="current.desc"></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layouts.guest>
