{{-- 
    Modal Konfirmasi — reusable, dikontrol via Alpine.store('confirm').
    Letakkan SEKALI di akhir <body> di layout (app.blade.php & guest.blade.php).
    
    Pakai dari JavaScript:
    
        Alpine.store('confirm').show({
            title: 'Hapus Deteksi?',
            message: 'Deteksi #14 akan dihapus permanen.',
            confirmText: 'Hapus',
            variant: 'danger',
            onConfirm: () => { form.submit(); }
        });
--}}
<div x-data
     x-show="$store.confirm.visible"
     x-cloak
     @keydown.escape.window="$store.confirm.cancel()"
     class="fixed inset-0 z-50 overflow-y-auto">

    {{-- Backdrop --}}
    <div x-show="$store.confirm.visible"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="$store.confirm.cancel()"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm">
    </div>

    {{-- Modal panel --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="$store.confirm.visible"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
             @click.stop
             class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">

            {{-- Body --}}
            <div class="p-5 sm:p-6">
                <div class="flex items-start gap-4">

                    {{-- Icon (warna sesuai variant) --}}
                    <div :class="$store.confirm.variant === 'danger'
                            ? 'bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400'
                            : 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400'"
                         class="shrink-0 w-11 h-11 rounded-full flex items-center justify-center">

                        {{-- Icon: danger = warning triangle --}}
                        <template x-if="$store.confirm.variant === 'danger'">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                            </svg>
                        </template>

                        {{-- Icon: default = question mark --}}
                        <template x-if="$store.confirm.variant !== 'danger'">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/>
                            </svg>
                        </template>
                    </div>

                    <div class="min-w-0 flex-1 pt-0.5">
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white"
                            x-text="$store.confirm.title"></h3>
                        <p class="mt-1.5 text-sm text-slate-600 dark:text-slate-400"
                           x-text="$store.confirm.message"></p>
                    </div>

                    {{-- Close button --}}
                    <button @click="$store.confirm.cancel()"
                            class="shrink-0 -mt-1 -mr-1 p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Footer dengan 2 tombol --}}
            <div class="px-5 sm:px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                <button @click="$store.confirm.cancel()"
                        x-show="$store.confirm.cancelText"
                        class="px-4 py-2 rounded-lg bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <span x-text="$store.confirm.cancelText"></span>
                </button>

                <button @click="$store.confirm.confirm()"
                        :class="$store.confirm.variant === 'danger'
                            ? 'bg-rose-600 hover:bg-rose-700 focus:ring-rose-500'
                            : 'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500'"
                        class="px-4 py-2 rounded-lg text-sm font-semibold text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition">
                    <span x-text="$store.confirm.confirmText"></span>
                </button>
            </div>
        </div>
    </div>
</div>
