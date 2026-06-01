{{-- 
    Label dot compact - dot warna + angka count + tooltip hover.
    Pakai untuk tampilkan breakdown label di card list (riwayat) supaya compact.
    
    Pakai:
        <x-label-dot fase="Mekar" :count="3" />
    
    Tooltip pakai Alpine.js custom - muncul cepat saat hover, ber-style.
    Tooltip isi: nama kondisi + jumlah objek
    
    Internal label HARUS match dengan dataset YOLO/MLP class names:
    - Mekar, Penyemaian, Sangat_Mekar (3 label aktif sekarang)
    - Kuncup, Pematangan_Biji, Biji_Matang, Penyemaian_Baru (untuk 5 label nanti)
--}}
@props(['fase', 'count' => 1])

@php
    // Display name untuk tooltip - pakai lang translation
    $displayName = __('messages.kondisi.' . $fase);

    // Config warna per label - HARUS konsisten dengan fase-badge.blade.php
    $config = [
        // === 3 label aktif ===
        'Mekar' => 'bg-rose-500',
        'Sangat_Mekar' => 'bg-pink-500',
        'Penyemaian' => 'bg-emerald-500',

        // === 5 label (future expansion) ===
        'Kuncup' => 'bg-lime-500',
        'Pematangan_Biji' => 'bg-yellow-500',
        'Biji_Matang' => 'bg-amber-700',
        'Penyemaian_Baru' => 'bg-teal-500',
    ];

    $dotColor = $config[$fase] ?? 'bg-slate-400';
    $tooltipText = $displayName . ' · ' . $count . ' ' . __('messages.label.object');
@endphp

<span x-data="{ showTooltip: false }"
      @mouseenter="showTooltip = true"
      @mouseleave="showTooltip = false"
      @touchstart="showTooltip = true"
      @touchend="setTimeout(() => showTooltip = false, 2000)"
      class="relative inline-flex items-center gap-1 text-xs font-medium text-slate-700 dark:text-slate-300 cursor-default">

    <span class="w-2.5 h-2.5 rounded-full {{ $dotColor }} shrink-0"></span>
    <span>{{ $count }}</span>

    {{-- Custom tooltip Alpine --}}
    <span x-show="showTooltip"
          x-cloak
          x-transition:enter="transition ease-out duration-100"
          x-transition:enter-start="opacity-0 translate-y-1"
          x-transition:enter-end="opacity-100 translate-y-0"
          x-transition:leave="transition ease-in duration-75"
          x-transition:leave-start="opacity-100"
          x-transition:leave-end="opacity-0"
          class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5
                 px-2.5 py-1 rounded-md
                 bg-slate-900 dark:bg-slate-700 text-white text-xs font-medium
                 whitespace-nowrap shadow-lg
                 pointer-events-none z-50">
        {{ $tooltipText }}
        {{-- Arrow tooltip --}}
        <span class="absolute top-full left-1/2 -translate-x-1/2 -mt-px
                     w-0 h-0
                     border-l-4 border-r-4 border-t-4
                     border-l-transparent border-r-transparent
                     border-t-slate-900 dark:border-t-slate-700"></span>
    </span>
</span>
