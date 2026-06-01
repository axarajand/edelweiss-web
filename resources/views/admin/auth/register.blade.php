<x-layouts.auth :title="__('auth.register.title') . ' - ' . __('messages.brand_name')">

    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-1">Daftar Akun Baru</h2>
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
        Akun baru memerlukan persetujuan admin sebelum bisa login.
    </p>

    <form action="{{ route('admin.register') }}" method="POST" class="space-y-4">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                {{ __('auth.register.name_label') }}
            </label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                   class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                          @error('name') border-rose-500 @enderror"
                   placeholder="John Doe">
            @error('name')
                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                {{ __('auth.register.email_label') }}
            </label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                   class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                          @error('email') border-rose-500 @enderror"
                   placeholder="email@example.com">
            @error('email')
                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                {{ __('auth.register.password_label') }}
            </label>
            <input id="password" name="password" type="password" required
                   class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                          @error('password') border-rose-500 @enderror"
                   placeholder="Minimal 8 karakter">
            @error('password')
                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                {{ __('auth.register.password_confirm_label') }}
            </label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                   class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                   placeholder="Ketik ulang password">
        </div>

        {{-- Notice --}}
        <div class="p-3 rounded-lg bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 text-xs text-amber-700 dark:text-amber-400">
            <strong>Penting:</strong> Setelah daftar, akun Anda akan berstatus <em>pending</em> sampai disetujui oleh admin lain.
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full px-4 py-2.5 rounded-lg bg-emerald-600 text-white font-medium text-sm hover:bg-emerald-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
            {{ __('auth.register.submit') }}
        </button>
    </form>

    {{-- Login link --}}
    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800 text-center">
        <p class="text-sm text-slate-500 dark:text-slate-400">
            {{ __('auth.register.has_account') }}
            <a href="{{ route('admin.login') }}" class="text-emerald-600 dark:text-emerald-400 font-medium hover:underline">
                {{ __('auth.register.login_link') }}
            </a>
        </p>
    </div>
</x-layouts.auth>
