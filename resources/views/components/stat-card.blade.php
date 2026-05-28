{{-- 
    Stat card untuk dashboard.
    Pakai: <x-stat-card label="Total" value="372" color="emerald" :icon="'flower'" />
--}}
@props([
    'label',
    'value',
    'color' => 'slate',
    'icon' => null,
    'trend' => null,
])

@php
    $colorMap = [
        'emerald' => ['bg' => 'bg-emerald-50 dark:bg-emerald-500/10', 'icon-bg' => 'bg-emerald-100 dark:bg-emerald-500/20', 'text' => 'text-emerald-600 dark:text-emerald-400', 'border' => 'border-emerald-100 dark:border-emerald-500/20'],
        'lime' => ['bg' => 'bg-lime-50 dark:bg-lime-500/10', 'icon-bg' => 'bg-lime-100 dark:bg-lime-500/20', 'text' => 'text-lime-600 dark:text-lime-400', 'border' => 'border-lime-100 dark:border-lime-500/20'],
        'rose' => ['bg' => 'bg-rose-50 dark:bg-rose-500/10', 'icon-bg' => 'bg-rose-100 dark:bg-rose-500/20', 'text' => 'text-rose-600 dark:text-rose-400', 'border' => 'border-rose-100 dark:border-rose-500/20'],
        'yellow' => ['bg' => 'bg-yellow-50 dark:bg-yellow-500/10', 'icon-bg' => 'bg-yellow-100 dark:bg-yellow-500/20', 'text' => 'text-yellow-600 dark:text-yellow-400', 'border' => 'border-yellow-100 dark:border-yellow-500/20'],
        'amber' => ['bg' => 'bg-amber-50 dark:bg-amber-700/15', 'icon-bg' => 'bg-amber-100 dark:bg-amber-700/25', 'text' => 'text-amber-700 dark:text-amber-400', 'border' => 'border-amber-100 dark:border-amber-700/20'],
        'slate' => ['bg' => 'bg-white dark:bg-slate-900', 'icon-bg' => 'bg-slate-100 dark:bg-slate-800', 'text' => 'text-slate-600 dark:text-slate-400', 'border' => 'border-slate-200 dark:border-slate-800'],
    ];
    $c = $colorMap[$color] ?? $colorMap['slate'];
@endphp

<div class="rounded-xl border p-5 {{ $c['bg'] }} {{ $c['border'] }} transition hover:shadow-sm">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0 flex-1">
            <p class="text-xs font-medium uppercase tracking-wider {{ $c['text'] }}">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white truncate">{{ $value }}</p>
            @if ($trend !== null)
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $trend }}</p>
            @endif
        </div>
        @if ($icon)
            <div class="w-10 h-10 rounded-lg {{ $c['icon-bg'] }} flex items-center justify-center shrink-0">
                <x-icon name="{{ $icon }}" class="w-5 h-5 {{ $c['text'] }}" />
            </div>
        @endif
    </div>
</div>
