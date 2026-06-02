<x-layouts.app :title="__('settings.title') . ' - ' . __('messages.brand_name')">
    <x-slot:header>{{ __('settings.title') }}</x-slot:header>

    @php $user = auth()->user(); @endphp

    <div x-data="{ tab: 'profile' }" class="space-y-6">

        {{-- Tabs --}}
        <div class="inline-flex p-1 rounded-xl bg-slate-100 dark:bg-slate-800">
            <button @click="tab = 'profile'"
                    :class="tab === 'profile'
                        ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm'
                        : 'text-slate-600 dark:text-slate-400'"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition">
                Profil
            </button>
            <button @click="tab = 'password'"
                    :class="tab === 'password'
                        ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm'
                        : 'text-slate-600 dark:text-slate-400'"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition">
                Keamanan
            </button>
        </div>

        {{-- PROFILE TAB --}}
        <div x-show="tab === 'profile'" class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                <h3 class="font-semibold text-slate-900 dark:text-white">Informasi Profil</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Perbarui informasi akun dan foto profil.</p>
            </div>

            <form action="{{ route('admin.settings.profile') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-5">
                @csrf
                @method('PUT')

                {{-- Avatar --}}
                <div class="flex items-center gap-4">
                    <x-avatar :user="$user" class="w-20 h-20" />
                    <div class="flex flex-col gap-2">
                        <label class="cursor-pointer">
                            <span class="px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition inline-block">
                                Ganti Foto
                            </span>
                            <input type="file" name="avatar" accept="image/*" class="hidden" onchange="this.form.submit()">
                        </label>
                        @if ($user->avatar)
                            <button type="button" onclick="document.getElementById('delete-avatar-form').submit()"
                                    class="text-xs text-rose-600 dark:text-rose-400 hover:underline self-start">
                                Hapus foto
                            </button>
                        @endif
                        @error('avatar')
                            <p class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        Nama Lengkap
                    </label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                  @error('name') border-rose-500 @enderror">
                    @error('name')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        Email
                    </label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                  @error('email') border-rose-500 @enderror">
                    @error('email')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                </div>

                <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- Hidden delete avatar form --}}
        <form id="delete-avatar-form" action="{{ route('admin.settings.avatar.delete') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

        {{-- PASSWORD TAB --}}
        <div x-show="tab === 'password'" x-cloak class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                <h3 class="font-semibold text-slate-900 dark:text-white">Ganti Password</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Pastikan password baru cukup kuat dan tidak mudah ditebak.</p>
            </div>

            <form action="{{ route('admin.settings.password') }}" method="POST" class="p-5 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        Password Saat Ini
                    </label>
                    <input id="current_password" name="current_password" type="password" required
                           class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500
                                  @error('current_password') border-rose-500 @enderror">
                    @error('current_password')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        Password Baru
                    </label>
                    <input id="password" name="password" type="password" required
                           class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500
                                  @error('password') border-rose-500 @enderror"
                           placeholder="Minimal 8 karakter">
                    @error('password')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        Konfirmasi Password Baru
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
                    Update Password
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
