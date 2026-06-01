{{-- 
    Layout untuk halaman publik (guest).
--}}
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Edelweiss Detection' }}</title>
    <meta name="description" content="Sistem deteksi kesehatan bunga Edelweiss Jawa berbasis AI (YOLOv11 + MLP)">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">

    {{-- Inline critical CSS — cegah FOUC saat reload --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script>
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>


    {{-- Inject translation keys untuk JS (toast/modal messages) --}}
    <script>
        window.lang = {
            file_invalid: @json(__('detection.errors.file_invalid')),
            file_too_big: @json(__('detection.errors.file_too_big')),
            timeout: @json(__('detection.errors.timeout')),
            network: @json(__('detection.errors.network')),
            service_offline_title: @json(__('detection.errors.service_offline_title')),
            service_offline_message: @json(__('detection.errors.service_offline_message')),
            generic: @json(__('detection.errors.generic')),
            camera_permission_denied: @json(__('detection.errors.camera_permission_denied')),
            camera_not_found: @json(__('detection.errors.camera_not_found')),
            camera_in_use: @json(__('detection.errors.camera_in_use')),
            camera_generic: @json(__('detection.errors.camera_generic')),
            understand: @json(__('messages.action.understand')),
        };
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-200 min-h-screen antialiased flex flex-col"
      x-data
      x-init="$store.theme.init()">

    {{-- HEADER --}}
    <header class="sticky top-0 z-30 bg-white/85 dark:bg-slate-950/85 backdrop-blur-md border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center">

            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <x-logo class="w-9 h-9" />
                <span class="font-bold text-slate-900 dark:text-white text-base sm:text-lg tracking-tight">
                    Edelweiss Detection
                </span>
            </a>

            <div class="ml-auto flex items-center gap-2 sm:gap-3">

                <nav class="hidden md:flex items-center gap-1 mr-2">
                    <a href="{{ route('home') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('home') ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                        {{ __('messages.nav.home') }}
                    </a>
                    <a href="{{ route('guest.detection') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('guest.detection') ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                        {{ __('messages.nav.detection') }}
                    </a>
                </nav>

                {{-- Language switcher (ID / EN) --}}
                <x-language-switcher />

                <button @click="$store.theme.toggle()"
                        class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400"
                        :title="$store.theme.dark ? 'Light mode' : 'Dark mode'">
                    <template x-if="!$store.theme.dark">
                        <x-icon name="moon" class="w-5 h-5" />
                    </template>
                    <template x-if="$store.theme.dark">
                        <x-icon name="sun" class="w-5 h-5" />
                    </template>
                </button>

                @auth
                    <a href="{{ route('admin.dashboard') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
                        <x-icon name="home" class="w-4 h-4" />
                        <span class="hidden sm:inline">{{ __('messages.nav.dashboard') }}</span>
                    </a>
                @else
                    <a href="{{ route('admin.login') }}"
                       class="px-3 sm:px-4 py-2 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        {{ __('messages.nav.login') }}
                    </a>
                    <a href="{{ route('admin.register') }}"
                       class="px-3 sm:px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
                        {{ __('messages.nav.register') }}
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-1">
        {{ $slot }}
    </main>

    {{-- FOOTER (rebrand) --}}
    <footer class="border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <x-logo class="w-7 h-7" />
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Edelweiss Detection</span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 text-center">
                    Sistem deteksi kesehatan berbasis YOLOv11 + MLP &middot; Untuk konservasi <em>Anaphalis javanica</em>
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    &copy; {{ date('Y') }} Edelweiss Detection
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
    <x-toast />
</body>
</html>
