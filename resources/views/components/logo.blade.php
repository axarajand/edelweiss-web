{{-- 
    Logo Edelweis dengan unique ID untuk gradient (fix bug render saat dipakai >1x).
    Pakai: <x-logo class="w-9 h-9" />
--}}
@props(['class' => 'w-9 h-9'])

@php
    // Generate unique ID untuk gradient supaya tidak konflik kalau logo dipakai berkali-kali
    $uid = 'edw-' . uniqid();
@endphp

<svg {{ $attributes->merge(['class' => $class]) }}
     viewBox="0 0 64 64"
     fill="none"
     xmlns="http://www.w3.org/2000/svg"
     aria-label="Edelweiss logo"
     preserveAspectRatio="xMidYMid meet">

    <defs>
        <linearGradient id="{{ $uid }}" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
            <stop offset="0%" stop-color="#10b981"/>
            <stop offset="100%" stop-color="#059669"/>
        </linearGradient>
    </defs>

    {{-- Background --}}
    <rect width="64" height="64" rx="14" fill="url(#{{ $uid }})"/>

    {{-- 5 petals --}}
    <g transform="translate(32 32)">
        <ellipse cx="0" cy="-13" rx="5" ry="9" fill="white" fill-opacity="0.95"/>
        <ellipse cx="0" cy="-13" rx="5" ry="9" fill="white" fill-opacity="0.95" transform="rotate(72)"/>
        <ellipse cx="0" cy="-13" rx="5" ry="9" fill="white" fill-opacity="0.95" transform="rotate(144)"/>
        <ellipse cx="0" cy="-13" rx="5" ry="9" fill="white" fill-opacity="0.95" transform="rotate(216)"/>
        <ellipse cx="0" cy="-13" rx="5" ry="9" fill="white" fill-opacity="0.95" transform="rotate(288)"/>

        {{-- Center --}}
        <circle cx="0" cy="0" r="5" fill="#fbbf24"/>
        <circle cx="0" cy="0" r="3" fill="#f59e0b"/>

        {{-- Stamens --}}
        <circle cx="-1.5" cy="-1.5" r="0.7" fill="#92400e"/>
        <circle cx="1.5" cy="-1.5" r="0.7" fill="#92400e"/>
        <circle cx="-1.5" cy="1.5" r="0.7" fill="#92400e"/>
        <circle cx="1.5" cy="1.5" r="0.7" fill="#92400e"/>
        <circle cx="0" cy="0" r="0.7" fill="#92400e"/>
    </g>
</svg>
