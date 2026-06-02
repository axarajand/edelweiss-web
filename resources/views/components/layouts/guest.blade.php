{{-- 
    Layout untuk halaman publik (guest).
    Nav: Logo kiri, menu tengah, actions kanan. Mobile: hamburger.
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

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script>
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

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
            checking_status: @json(__('messages.status.checking_status')),
            ml_active: 'ML Service: ' + @json(__('messages.status.active')),
            ml_offline: 'ML Service: ' + @json(__('messages.status.offline')),
            enter_fullscreen: @json(__('detection.camera.enter_fullscreen')),
            exit_fullscreen: @json(__('detection.camera.exit_fullscreen')),
            objects_detected: @json(__('detection.result.objects_detected')),
        };
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-200 min-h-screen antialiased flex flex-col"
      x-data
      x-init="$store.theme.init()">

    {{-- HEADER --}}
    <header class="sticky top-0 z-30 bg-white/90 dark:bg-slate-950/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-800"
            x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center">

            {{-- Logo (kiri) --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0">
                <x-logo class="w-9 h-9" />
                <span class="font-bold text-slate-900 dark:text-white text-base tracking-tight hidden sm:inline">
                    Edelweiss Detection
                </span>
            </a>

            {{-- Nav tengah (desktop ≥ md) --}}
            <nav class="hidden md:flex items-center gap-0.5 absolute left-1/2 -translate-x-1/2">
                @php
                    $guestNav = [
                        ['route' => 'home', 'label' => __('messages.nav.home')],
                        ['route' => 'guest.detection', 'label' => __('messages.nav.detection')],
                        ['route' => 'guest.research', 'label' => __('messages.nav.research')],
                        ['route' => 'guest.partners', 'label' => __('messages.nav.partners')],
                        ['route' => 'guest.gallery', 'label' => __('messages.nav.gallery')],
                    ];
                @endphp
                @foreach ($guestNav as $nav)
                    <a href="{{ route($nav['route']) }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs($nav['route'])
                                  ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10'
                                  : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        {{ $nav['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- Actions kanan --}}
            <div class="ml-auto flex items-center gap-1.5 sm:gap-2">

                {{-- Language switcher --}}
                <x-language-switcher />

                {{-- Dark mode toggle --}}
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
                       class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
                        <x-icon name="home" class="w-4 h-4" />
                        {{ __('messages.nav.dashboard') }}
                    </a>
                @else
                    <a href="{{ route('admin.login') }}"
                       class="hidden sm:inline px-3 py-2 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        {{ __('messages.nav.login') }}
                    </a>
                    <a href="{{ route('admin.register') }}"
                       class="hidden sm:inline px-3 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
                        {{ __('messages.nav.register') }}
                    </a>
                @endauth

                {{-- Hamburger (mobile < md) --}}
                <button @click="mobileOpen = !mobileOpen"
                        class="md:hidden p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400">
                    <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileOpen" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu (< md) --}}
        <div x-show="mobileOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 space-y-1">

            @foreach ($guestNav as $nav)
                <a href="{{ route($nav['route']) }}"
                   @click="mobileOpen = false"
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition
                          {{ request()->routeIs($nav['route'])
                              ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                              : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    {{ $nav['label'] }}
                </a>
            @endforeach

            <div class="pt-2 border-t border-slate-200 dark:border-slate-800 flex gap-2">
                @auth
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex-1 text-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
                        {{ __('messages.nav.dashboard') }}
                    </a>
                @else
                    <a href="{{ route('admin.login') }}"
                       class="flex-1 text-center px-4 py-2 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                        {{ __('messages.nav.login') }}
                    </a>
                    <a href="{{ route('admin.register') }}"
                       class="flex-1 text-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
                        {{ __('messages.nav.register') }}
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-1">
        {{ $slot }}
    </main>

    {{-- FOOTER --}}
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
