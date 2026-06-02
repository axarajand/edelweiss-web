{{-- 
    Label dot - dot warna + jumlah objek + nama label inline.
    Format: 🔴 3 Mekar
    
    Tidak pakai tooltip lagi karena bisa kepotong card.
    Nama label langsung tampil di badge supaya user tahu warna mana untuk apa.
    
    Pakai:
        <x-label-dot fase="Mekar" :count="3" />
    
    Internal label HARUS match dengan dataset YOLO/MLP class names:
    - Mekar, Penyemaian, Sangat_Mekar (3 label aktif sekarang)
    - Kuncup, Pematangan_Biji, Biji_Matang, Penyemaian_Baru (untuk 5 label nanti)
--}}
@props(['fase', 'count' => 1])

@php
    // Display name lewat lang translation (Mekar -> Mekar/Blooming, dst)
    $displayName = __('messages.kondisi.' . $fase);

    // Config warna per label - HARUS konsisten dengan fase-badge.blade.php
    $config = [
        // === 3 label aktif ===
        'Mekar' => ['dot' => 'bg-rose-500', 'bg' => 'bg-rose-50 dark:bg-rose-500/10', 'text' => 'text-rose-700 dark:text-rose-400'],
        'Sangat_Mekar' => ['dot' => 'bg-pink-500', 'bg' => 'bg-pink-50 dark:bg-pink-500/10', 'text' => 'text-pink-700 dark:text-pink-400'],
        'Penyemaian' => ['dot' => 'bg-emerald-500', 'bg' => 'bg-emerald-50 dark:bg-emerald-500/10', 'text' => 'text-emerald-700 dark:text-emerald-400'],

        // === 5 label (future expansion) ===
        'Kuncup' => ['dot' => 'bg-lime-500', 'bg' => 'bg-lime-50 dark:bg-lime-500/10', 'text' => 'text-lime-700 dark:text-lime-400'],
        'Pematangan_Biji' => ['dot' => 'bg-yellow-500', 'bg' => 'bg-yellow-50 dark:bg-yellow-500/10', 'text' => 'text-yellow-700 dark:text-yellow-400'],
        'Biji_Matang' => ['dot' => 'bg-amber-700', 'bg' => 'bg-amber-50 dark:bg-amber-700/10', 'text' => 'text-amber-800 dark:text-amber-500'],
        'Penyemaian_Baru' => ['dot' => 'bg-teal-500', 'bg' => 'bg-teal-50 dark:bg-teal-500/10', 'text' => 'text-teal-700 dark:text-teal-400'],
    ];

    $cfg = $config[$fase] ?? ['dot' => 'bg-slate-400', 'bg' => 'bg-slate-100 dark:bg-slate-800', 'text' => 'text-slate-700 dark:text-slate-300'];
@endphp

<span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium {{ $cfg['bg'] }} {{ $cfg['text'] }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }} shrink-0"></span>
    <span class="font-semibold">{{ $count }}</span>
    <span>{{ $displayName }}</span>
</span>
