<x-layouts.auth :title="__('auth.login.title') . ' - ' . __('messages.brand_name')">

    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-1">{{ __('auth.login.title') }}</h2>
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
        {{ __('auth.login.subtitle') }}
    </p>

    <form action="{{ route('admin.login') }}" method="POST" class="space-y-4">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                {{ __('auth.login.email_label') }}
            </label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                          @error('email') border-rose-500 @enderror"
                   placeholder="{{ __('auth.login.email_placeholder') }}">
            @error('email')
                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                {{ __('auth.login.password_label') }}
            </label>
            <input id="password" name="password" type="password" required
                   class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                          @error('password') border-rose-500 @enderror"
                   placeholder="********">
            @error('password')
                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember me --}}
        <div class="flex items-center">
            <input id="remember" name="remember" type="checkbox"
                   class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
            <label for="remember" class="ml-2 text-sm text-slate-600 dark:text-slate-400">
                {{ __('auth.login.remember_me') }}
            </label>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full px-4 py-2.5 rounded-lg bg-emerald-600 text-white font-medium text-sm hover:bg-emerald-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
            {{ __('auth.login.submit') }}
        </button>
    </form>

    {{-- Register link --}}
    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800 text-center">
        <p class="text-sm text-slate-500 dark:text-slate-400">
            {{ __('auth.login.no_account') }}
            <a href="{{ route('admin.register') }}" class="text-emerald-600 dark:text-emerald-400 font-medium hover:underline">
                {{ __('auth.login.register_link') }}
            </a>
        </p>
    </div>
</x-layouts.auth>
