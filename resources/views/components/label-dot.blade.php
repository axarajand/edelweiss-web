{{-- 
    Label dot compact - dot warna + angka count + tooltip hover.
    Pakai untuk tampilkan breakdown label di card list (riwayat) supaya compact.
    
    Pakai:
        <x-label-dot fase="Mekar" :count="3" />
    
    Tooltip otomatis muncul saat hover, isinya: "Mekar: 3 objek"
    
    Internal label HARUS match dengan dataset YOLO/MLP class names:
    - Mekar, Penyemaian, Sangat_Mekar (3 label aktif sekarang)
    - Kuncup, Pematangan_Biji, Biji_Matang, Penyemaian_Baru (untuk 5 label nanti)
--}}
@props(['fase', 'count' => 1])

@php
    // Display name untuk tooltip (ganti underscore dengan space)
    $displayName = str_replace('_', ' ', $fase);

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
    $tooltip = $displayName . ': ' . $count . ' objek';
@endphp

<span class="inline-flex items-center gap-1 text-xs font-medium text-slate-700 dark:text-slate-300 cursor-default"
      title="{{ $tooltip }}"
      aria-label="{{ $tooltip }}">
    <span class="w-2 h-2 rounded-full {{ $dotColor }}"></span>
    <span>{{ $count }}</span>
</span>
