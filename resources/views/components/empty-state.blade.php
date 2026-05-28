{{-- 
    Empty state generic.
    Pakai: <x-empty-state title="Belum ada data" message="..." icon="inbox" />
--}}
@props(['title' => 'Belum ada data', 'message' => 'Data akan muncul di sini setelah Anda menambahkannya.', 'icon' => 'inbox'])

<div class="flex flex-col items-center justify-center py-16 px-6 text-center">
    <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-4">
        <x-icon name="{{ $icon }}" class="w-8 h-8 text-slate-400 dark:text-slate-500" />
    </div>
    <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-1">{{ $title }}</h3>
    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm">{{ $message }}</p>
    @if (isset($action))
        <div class="mt-5">{{ $action }}</div>
    @endif
</div>
