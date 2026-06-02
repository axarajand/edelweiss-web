{{-- 
    Badge untuk label fase pertumbuhan / kesehatan bunga.
    Pakai: <x-fase-badge fase="Mekar" />
    
    Internal label HARUS match dengan dataset YOLO/MLP class names:
    - Mekar, Penyemaian, Sangat_Mekar (3 label aktif sekarang)
    - Kuncup, Pematangan_Biji, Biji_Matang, Penyemaian_Baru (untuk 5 label nanti)
--}}
@props(['fase'])

@php
    // Display name (untuk tampilkan ke user, pakai translation)
    $displayName = __('messages.kondisi.' . $fase);

    // Config warna per label - support 3 aktif + 5 future
    $config = [
        // === 3 label aktif (Roboflow dataset) ===
        'Mekar' => ['bg' => 'bg-rose-100 dark:bg-rose-500/15', 'text' => 'text-rose-700 dark:text-rose-400', 'dot' => 'bg-rose-500'],
        'Sangat_Mekar' => ['bg' => 'bg-pink-100 dark:bg-pink-500/15', 'text' => 'text-pink-700 dark:text-pink-400', 'dot' => 'bg-pink-500'],
        'Penyemaian' => ['bg' => 'bg-emerald-100 dark:bg-emerald-500/15', 'text' => 'text-emerald-700 dark:text-emerald-400', 'dot' => 'bg-emerald-500'],

        // === 5 label (future expansion) ===
        'Kuncup' => ['bg' => 'bg-lime-100 dark:bg-lime-500/15', 'text' => 'text-lime-700 dark:text-lime-400', 'dot' => 'bg-lime-500'],
        'Pematangan_Biji' => ['bg' => 'bg-yellow-100 dark:bg-yellow-500/15', 'text' => 'text-yellow-700 dark:text-yellow-400', 'dot' => 'bg-yellow-500'],
        'Biji_Matang' => ['bg' => 'bg-amber-100 dark:bg-amber-700/20', 'text' => 'text-amber-800 dark:text-amber-400', 'dot' => 'bg-amber-700'],
        'Penyemaian_Baru' => ['bg' => 'bg-teal-100 dark:bg-teal-500/15', 'text' => 'text-teal-700 dark:text-teal-400', 'dot' => 'bg-teal-500'],
    ];

    // Fallback kalau label tidak dikenal
    $c = $config[$fase] ?? ['bg' => 'bg-slate-100 dark:bg-slate-700', 'text' => 'text-slate-600 dark:text-slate-300', 'dot' => 'bg-slate-400'];
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $c['bg'] }} {{ $c['text'] }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $c['dot'] }}"></span>
    {{ $displayName }}
</span>
