<?php

return [
    'title' => 'Laporan',
    'page_title' => 'Laporan - Edelweiss Detection',

    'filter' => [
        'from_date' => 'Dari Tanggal',
        'to_date' => 'Sampai Tanggal',
        'condition' => 'Kondisi',
        'method' => 'Metode',
        'source' => 'Sumber',
        'all' => 'Semua',
        'apply' => 'Terapkan',
        'reset' => 'Reset',
    ],

    'method' => [
        'upload' => 'Upload',
        'camera' => 'Kamera',
    ],

    'source' => [
        'admin' => 'Admin',
        'guest' => 'Pengunjung',
    ],

    'stat' => [
        'total_detection' => 'Total Deteksi',
        'avg_per_day' => 'Rata-rata per Hari',
        'dominant_condition' => 'Kondisi Dominan',
        'total_object' => 'Total Objek',
    ],

    'chart' => [
        'trend_title' => 'Trend Deteksi',
        'trend_subtitle' => ':from — :to (:days hari)',
        'distribution_title' => 'Distribusi Kondisi',
        'distribution_subtitle' => 'Periode terpilih',
    ],

    'export' => [
        'pdf' => 'Ekspor PDF',
        'excel' => 'Ekspor Excel',
        'pdf_title' => 'Unduh laporan dalam format PDF',
        'excel_title' => 'Unduh laporan dalam format Excel',
    ],

    'empty' => [
        'title' => 'Tidak ada data',
        'subtitle' => 'Tidak ada deteksi yang sesuai dengan filter yang dipilih.',
    ],
];
