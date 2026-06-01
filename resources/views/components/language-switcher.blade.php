{{--
    Language switcher component - dropdown ID/EN.
    Pakai di nav (app.blade.php + guest.blade.php + auth.blade.php).

    Format:
        <x-language-switcher />

    Klik button → muncul dropdown dengan ID + EN.
    Active language ditandai checkmark.
--}}

@php
    $currentLocale = app()->getLocale();
    $languages = [
        'id' => ['code' => 'ID', 'name' => 'Indonesia', 'flag' => '🇮🇩'],
        'en' => ['code' => 'EN', 'name' => 'English', 'flag' => '🇬🇧'],
    ];
    $current = $languages[$currentLocale] ?? $languages['id'];
@endphp

<div x-data="{ open: false }"
     @click.away="open = false"
     class="relative">

    {{-- Button: tampilkan kode bahasa aktif --}}
    <button @click="open = !open"
            type="button"
            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg
                   bg-white dark:bg-slate-800
                   text-slate-700 dark:text-slate-300
                   border border-slate-200 dark:border-slate-700
                   hover:bg-slate-50 dark:hover:bg-slate-700
                   text-sm font-medium transition"
            :aria-expanded="open"
            aria-label="{{ __('messages.nav.language') }}">
        <span>{{ $current['code'] }}</span>
        <svg class="w-3 h-3 transition-transform"
             :class="{ 'rotate-180': open }"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- Dropdown menu --}}
    <div x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-44 origin-top-right
                rounded-lg bg-white dark:bg-slate-900
                border border-slate-200 dark:border-slate-700
                shadow-lg ring-1 ring-black/5 z-50">

        @foreach ($languages as $code => $lang)
            <form method="POST" action="{{ url('/locale/' . $code) }}" class="block">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-between gap-2 px-3 py-2
                               text-sm text-slate-700 dark:text-slate-300
                               hover:bg-slate-100 dark:hover:bg-slate-800
                               first:rounded-t-lg last:rounded-b-lg transition">
                    <span class="flex items-center gap-2">
                        <span class="text-base leading-none">{{ $lang['flag'] }}</span>
                        <span>{{ $lang['name'] }}</span>
                    </span>
                    @if ($currentLocale === $code)
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    @endif
                </button>
            </form>
        @endforeach
    </div>
</div>
