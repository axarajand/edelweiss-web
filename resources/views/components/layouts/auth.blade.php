{{-- 
    Layout untuk halaman autentikasi (login, register).
    Minimalis, centered, dark mode aware.
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Edelweiss Detection' }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    {{-- Inline critical CSS — cegah FOUC saat reload --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script>
        // Apply theme sebelum body render (hindari flash)
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
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 min-h-screen antialiased">

    {{-- Language switcher floating top-right --}}
    <div class="fixed top-4 right-4 z-50">
        <x-language-switcher />
    </div>


    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">

            {{-- Logo & branding --}}
            <div class="flex flex-col items-center mb-8">
                <x-logo class="w-16 h-16 mb-4" />
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('messages.brand_name') }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('auth.login.subtitle') }}</p>
            </div>

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="mb-4 p-3 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 text-sm text-emerald-700 dark:text-emerald-400">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-3 rounded-lg bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/30 text-sm text-rose-700 dark:text-rose-400">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Auth card --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-sm">
                {{ $slot }}
            </div>

            {{-- Footer link --}}
            <div class="mt-6 text-center">
                <a href="/" class="text-xs text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition">
                    ← {{ __('auth.login.back_to_home') }}
                </a>
            </div>
        </div>
    </div>

</body>
</html>
