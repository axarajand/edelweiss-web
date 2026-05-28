<x-layouts.auth title="Daftar Admin - Edelweiss Detection">

    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-1">Daftar Akun Baru</h2>
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
        Akun baru memerlukan persetujuan admin sebelum bisa login.
    </p>

    <form action="{{ route('admin.register') }}" method="POST" class="space-y-4">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                Nama Lengkap
            </label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                   class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                          @error('name') border-rose-500 @enderror"
                   placeholder="Nama Anda">
            @error('name')
                <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                Email
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
                Password
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
                Konfirmasi Password
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
            Daftar
        </button>
    </form>

    {{-- Login link --}}
    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800 text-center">
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Sudah punya akun?
            <a href="{{ route('admin.login') }}" class="text-emerald-600 dark:text-emerald-400 font-medium hover:underline">
                Login di sini
            </a>
        </p>
    </div>
</x-layouts.auth>
