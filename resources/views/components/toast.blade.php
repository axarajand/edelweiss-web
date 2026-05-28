{{-- 
    Toast notification — diatur via Alpine.store('toast').
    Letakkan di akhir <body> di layout (app.blade.php & guest.blade.php).
    
    Pakai dari JS:
        Alpine.store('toast').show('Pesan', 'success');     // hijau
        Alpine.store('toast').show('Pesan', 'error');       // merah
        Alpine.store('toast').show('Pesan', 'warning');     // kuning/amber
        Alpine.store('toast').show('Pesan', 'info');        // biru
        Alpine.store('toast').show('Pesan', 'success', 6000);  // durasi custom (ms)
--}}
<div x-data
     x-show="$store.toast.visible"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-2"
     x-cloak
     class="fixed bottom-4 left-1/2 -translate-x-1/2 z-50 max-w-md w-[calc(100%-2rem)] sm:w-auto pointer-events-none">

    <div :class="{
            'bg-emerald-600 dark:bg-emerald-500': $store.toast.type === 'success',
            'bg-rose-600 dark:bg-rose-500': $store.toast.type === 'error',
            'bg-amber-500 dark:bg-amber-500': $store.toast.type === 'warning',
            'bg-blue-600 dark:bg-blue-500': $store.toast.type === 'info',
         }"
         class="rounded-xl shadow-lg px-4 py-3 flex items-center gap-3 text-white pointer-events-auto">

        {{-- Icon success: checkmark --}}
        <template x-if="$store.toast.type === 'success'">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </template>

        {{-- Icon error: warning triangle --}}
        <template x-if="$store.toast.type === 'error'">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
            </svg>
        </template>

        {{-- Icon warning: exclamation circle --}}
        <template x-if="$store.toast.type === 'warning'">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </template>

        {{-- Icon info: info circle --}}
        <template x-if="$store.toast.type === 'info'">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </template>

        <span class="text-sm font-medium flex-1" x-text="$store.toast.message"></span>

        <button @click="$store.toast.hide()" class="opacity-70 hover:opacity-100">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
