<?php

return [
    'title' => 'Laporan',
    'page_title' => 'Laporan - Edelweiss Detection',

    'tab' => [
        'data' => 'Data Laporan',
        'conditions' => 'Kondisi Edelweis',
        'system' => 'Tentang Sistem',
    ],

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

    'detail_title' => 'Detail Deteksi',
    'col_dominant' => 'Kondisi Dominan',
    'trend_empty' => 'Trend akan muncul saat ada deteksi.',
    'detection_label' => 'Deteksi',

    'distribution_empty' => 'Grafik distribusi kosong.',

    'col_date' => 'Tanggal',
    'exporting_pdf' => 'Sedang mengekspor ke PDF...',
    'exporting_excel' => 'Sedang mengekspor ke Excel...',
    'export_success_pdf' => 'Berhasil export PDF',
    'export_success_excel' => 'Berhasil export Excel',
    'export_failed' => 'Gagal mengekspor. Coba lagi.',
];
