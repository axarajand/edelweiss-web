{{-- 
    Layout utama untuk panel admin.
    Desktop (≥ lg): sidebar kiri dengan section profile bawah
    Mobile (< lg): bottom navigation + topbar dengan profile dropdown
--}}
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Edelweiss Detection' }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">

    {{-- Inline critical CSS — di-apply SEBELUM Vite load CSS external.
         Mencegah FOUC (Flash of Unstyled Content) untuk elemen Alpine
         dengan x-cloak (modal, toast, dropdown, dll). --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>


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
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 min-h-screen antialiased"
      x-data
      x-init="$store.theme.init()">

    @php
        $navItems = [
            ['route' => 'admin.dashboard', 'label' => __('messages.nav.dashboard'), 'icon' => 'home'],
            ['route' => 'admin.detection', 'label' => __('messages.nav.detection'), 'icon' => 'scan'],
            ['route' => 'admin.history', 'label' => __('messages.nav.history'), 'icon' => 'database'],
            ['route' => 'admin.content.index', 'label' => __('messages.nav.content'), 'icon' => 'grid'],
            ['route' => 'admin.reports', 'label' => __('messages.nav.reports'), 'icon' => 'chart'],
        ];
        $user = auth()->user();
    @endphp

    {{-- ============================================================
         DESKTOP SIDEBAR (≥ lg)
         ============================================================ --}}
    <aside class="hidden lg:flex fixed top-0 left-0 z-40 h-full bg-white dark:bg-slate-900
                  border-r border-slate-200 dark:border-slate-800
                  flex-col sidebar-transition"
           :class="$store.sidebar.open ? 'w-64' : 'w-20'">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 h-16 border-b border-slate-200 dark:border-slate-800 shrink-0">
            <x-logo class="w-9 h-9 shrink-0" />
            <span x-show="$store.sidebar.open"
                  x-transition:enter="transition-opacity duration-200 delay-75"
                  x-transition:enter-start="opacity-0"
                  x-transition:enter-end="opacity-100"
                  class="font-bold text-slate-900 dark:text-white text-base tracking-tight whitespace-nowrap">
                Edelweiss Detection
            </span>
        </div>

        {{-- Main navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto overflow-x-hidden">
            @foreach ($navItems as $item)
                @php $isActive = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   :title="!$store.sidebar.open ? '{{ $item['label'] }}' : ''"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                          {{ $isActive
                              ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400'
                              : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}"
                   :class="!$store.sidebar.open && 'justify-center'">
                    <x-icon name="{{ $item['icon'] }}" class="w-5 h-5 shrink-0" />
                    <span x-show="$store.sidebar.open"
                          x-transition:enter="transition-opacity duration-200 delay-75"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          class="whitespace-nowrap">
                        {{ $item['label'] }}
                    </span>
                </a>
            @endforeach

            {{-- Manajemen User (admin only feature) --}}
            <div class="pt-3 mt-3 border-t border-slate-200 dark:border-slate-800">
                @php $isUserMgmt = request()->routeIs('admin.users.*'); @endphp
                <a href="{{ route('admin.users.index') }}"
                   :title="!$store.sidebar.open ? '{{ __('messages.nav.users') }}' : ''"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                          {{ $isUserMgmt
                              ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400'
                              : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}"
                   :class="!$store.sidebar.open && 'justify-center'">
                    <x-icon name="users" class="w-5 h-5 shrink-0" />
                    <span x-show="$store.sidebar.open"
                          x-transition:enter="transition-opacity duration-200 delay-75"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          class="whitespace-nowrap">
                        {{ __('messages.nav.users') }}
                    </span>
                </a>
            </div>
        </nav>

        {{-- Profile section di bawah --}}
        <div class="border-t border-slate-200 dark:border-slate-800 p-3 shrink-0">
            <div x-data="{ open: false }" class="relative">

                {{-- Profile button --}}
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                        :class="!$store.sidebar.open && 'justify-center'">
                    <x-avatar :user="$user" class="w-9 h-9 shrink-0" />
                    <div x-show="$store.sidebar.open"
                         x-transition:enter="transition-opacity duration-200 delay-75"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="flex-1 min-w-0 text-left">
                        <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ $user->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $user->email }}</p>
                    </div>
                </button>

                {{-- Dropdown menu --}}
                <div x-show="open"
                     @click.outside="open = false"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak
                     class="absolute bottom-full left-0 right-0 mb-2 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 py-1 z-50"
                     :class="!$store.sidebar.open && 'left-full ml-2 right-auto w-48'">
                    <a href="{{ route('admin.settings') }}"
                       class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">
                        <x-icon name="settings" class="w-4 h-4" />
                        {{ __('messages.nav.settings') }}
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-2 px-3 py-2 text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10">
                            <x-icon name="logout" class="w-4 h-4" />
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    {{-- ============================================================
         MAIN WRAPPER
         ============================================================ --}}
    <div class="sidebar-transition pb-20 lg:pb-0"
         :class="$store.sidebar.open ? 'lg:pl-64' : 'lg:pl-20'">

        {{-- Topbar --}}
        <header class="sticky top-0 z-20 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md
                       border-b border-slate-200 dark:border-slate-800 h-16 flex items-center px-4 sm:px-6">

            {{-- Mobile: Logo + nama --}}
            <div class="flex items-center gap-2 lg:hidden">
                <x-logo class="w-8 h-8" />
                <span class="font-bold text-slate-900 dark:text-white text-sm">Edelweiss</span>
            </div>

            {{-- Desktop: hamburger collapse --}}
            <button @click="$store.sidebar.toggle()"
                    class="hidden lg:flex p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400">
                <x-icon name="menu" class="w-5 h-5" />
            </button>

            {{-- Page title (desktop) --}}
            <div class="ml-4 hidden lg:block">
                <h1 class="text-lg font-semibold text-slate-900 dark:text-white">
                    {{ $header ?? '' }}
                </h1>
            </div>

            <div class="ml-auto flex items-center gap-2">
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

                {{-- ML service status (desktop) - dynamic check --}}
                <div x-data="mlServiceCheck()" x-init="check(); setInterval(() => check(), 10000)"
                     class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-lg
                            bg-slate-100 dark:bg-slate-800 text-xs text-slate-600 dark:text-slate-400 relative group cursor-help">
                    {{-- Status dot --}}
                    <span class="w-2 h-2 rounded-full transition-colors"
                          :class="{
                              'bg-emerald-500 animate-pulse-soft': status === 'online',
                              'bg-rose-500': status === 'offline',
                              'bg-amber-400 animate-pulse': status === 'checking'
                          }"></span>
                    <span x-text="status === 'online' ? 'ML Service' : status === 'offline' ? 'ML Offline' : (window.lang?.checking_status || 'Checking...')"></span>

                    {{-- Tooltip on hover --}}
                    <div class="absolute top-full right-0 mt-2 w-64 p-3 rounded-lg bg-slate-900 dark:bg-slate-700 text-white text-xs shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50 pointer-events-none">
                        <div class="font-semibold mb-1.5">
                            <span x-text="status === 'online' ? (window.lang?.ml_active || 'ML Service: Aktif') : status === 'offline' ? (window.lang?.ml_offline || 'ML Service: Offline') : (window.lang?.checking_status || 'Memeriksa status...')"></span>
                        </div>
                        <div class="space-y-1 text-slate-300">
                            <div class="flex justify-between gap-2">
                                <span>URL:</span>
                                <span class="font-mono text-[10px]" x-text="url || '—'"></span>
                            </div>
                            <div class="flex justify-between gap-2" x-show="responseTime !== null">
                                <span>Response:</span>
                                <span x-text="`${responseTime}ms`"></span>
                            </div>
                            <div class="flex justify-between gap-2" x-show="lastChecked">
                                <span>Last check:</span>
                                <span x-text="lastChecked"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Language switcher (ID / EN) --}}
                <x-language-switcher />

                {{-- Desktop: nama user di topbar --}}
                <div class="hidden xl:flex items-center gap-2 pl-2 border-l border-slate-200 dark:border-slate-700">
                    <x-avatar :user="$user" class="w-8 h-8" />
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ $user->name }}
                    </span>
                </div>

                {{-- Mobile: profile dropdown trigger --}}
                <div x-data="{ open: false }" class="relative lg:hidden">
                    <button @click="open = !open"
                            class="p-1 rounded-full hover:ring-2 hover:ring-emerald-500 transition">
                        <x-avatar :user="$user" class="w-8 h-8" />
                    </button>

                    {{-- Dropdown menu --}}
                    <div x-show="open"
                         @click.outside="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-cloak
                         class="absolute right-0 top-full mt-2 w-56 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 py-1 z-50">

                        {{-- Header info --}}
                        <div class="px-3 py-2 border-b border-slate-200 dark:border-slate-700">
                            <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ $user->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $user->email }}</p>
                        </div>

                        {{-- Manajemen User (mobile) --}}
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">
                            <x-icon name="users" class="w-4 h-4" />
                            {{ __('messages.nav.users') }}
                        </a>
                        <a href="{{ route('admin.settings') }}"
                           class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">
                            <x-icon name="settings" class="w-4 h-4" />
                            {{ __('messages.nav.settings') }}
                        </a>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-2 px-3 py-2 text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10">
                                <x-icon name="logout" class="w-4 h-4" />
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Mobile page header --}}
        @if (!empty($header ?? null))
            <div class="lg:hidden px-4 pt-4">
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ $header }}</h1>
            </div>
        @endif

        {{-- Flash messages --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mx-4 sm:mx-6 lg:mx-8 mt-4 p-3 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 text-sm text-emerald-700 dark:text-emerald-400 flex items-start gap-2">
                <x-icon name="check-circle" class="w-4 h-4 mt-0.5 shrink-0" />
                <span class="flex-1">{{ session('success') }}</span>
                <button @click="show = false" class="text-emerald-700 dark:text-emerald-400 hover:opacity-70">
                    <x-icon name="x" class="w-4 h-4" />
                </button>
            </div>
        @endif

        @if (session('error') || $errors->has('action'))
            <div x-data="{ show: true }" x-show="show"
                 class="mx-4 sm:mx-6 lg:mx-8 mt-4 p-3 rounded-lg bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/30 text-sm text-rose-700 dark:text-rose-400 flex items-start gap-2">
                <span class="flex-1">{{ session('error') ?? $errors->first('action') }}</span>
                <button @click="show = false" class="text-rose-700 dark:text-rose-400 hover:opacity-70">
                    <x-icon name="x" class="w-4 h-4" />
                </button>
            </div>
        @endif

        {{-- Page content --}}
        <main class="px-4 sm:px-6 lg:px-8 py-4 lg:py-6 max-w-[1600px] mx-auto">
            {{ $slot }}
        </main>
    </div>

    {{-- ============================================================
         MOBILE BOTTOM NAVIGATION (< lg)
         ============================================================ --}}
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40
                bg-white/95 dark:bg-slate-900/95 backdrop-blur-md
                border-t border-slate-200 dark:border-slate-800
                pb-[env(safe-area-inset-bottom)]">
        <div class="grid grid-cols-5 gap-1 px-1 pt-1.5 pb-1.5">
            @foreach ($navItems as $item)
                @php $isActive = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex flex-col items-center justify-center gap-0.5 py-1.5 px-1 rounded-lg transition
                          {{ $isActive
                              ? 'text-emerald-600 dark:text-emerald-400'
                              : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
                    <div class="relative">
                        @if ($isActive)
                            <span class="absolute -top-2 left-1/2 -translate-x-1/2 w-8 h-1 rounded-full bg-emerald-500"></span>
                        @endif
                        <x-icon name="{{ $item['icon'] }}" class="w-5 h-5" />
                    </div>
                    <span class="text-[10px] font-medium leading-tight">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>

    {{-- ML Service health check polling --}}
    <script>
        window.mlServiceCheck = function () {
            return {
                status: 'checking',    // 'checking' | 'online' | 'offline'
                url: null,
                responseTime: null,
                lastChecked: null,

                async check() {
                    try {
                        const response = await fetch('{{ route('admin.health.ml') }}', {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await response.json();
                        this.status = data.online ? 'online' : 'offline';
                        this.url = data.url;
                        this.responseTime = data.response_time_ms;
                        this.lastChecked = new Date().toLocaleTimeString('id-ID', { hour12: false });
                    } catch (err) {
                        this.status = 'offline';
                        this.lastChecked = new Date().toLocaleTimeString('id-ID', { hour12: false });
                    }
                },
            };
        };
    </script>

    {{-- Page Loader (top progress bar) --}}
    <div id="page-loader-bar"></div>
    <script>
        (function () {
            const bar = document.getElementById('page-loader-bar');

            function start() {
                bar.classList.add('active');
                bar.style.width = '30%';
                setTimeout(() => { if (bar.classList.contains('active')) bar.style.width = '70%'; }, 300);
                setTimeout(() => { if (bar.classList.contains('active')) bar.style.width = '85%'; }, 800);
            }

            function done() {
                bar.style.width = '100%';
                setTimeout(() => {
                    bar.classList.remove('active');
                    bar.style.width = '0%';
                }, 200);
            }

            // Trigger saat navigasi atau form submit
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a[href]');
                if (!link) return;
                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
                if (link.target === '_blank') return;
                if (link.hasAttribute('download')) return;
                // Skip same-page anchor
                if (link.host !== window.location.host) return;
                start();
            });

            document.addEventListener('submit', (e) => {
                if (e.target.tagName === 'FORM') {
                    // Skip kalau form GET dengan target sama (filter)
                    start();
                }
            });

            // Selesai saat page fully loaded
            window.addEventListener('load', done);
            window.addEventListener('pageshow', (e) => {
                if (e.persisted) done();
            });
        })();
    </script>

    @stack('scripts')
    <x-confirm-modal />
    <x-toast />
</body>
</html>
