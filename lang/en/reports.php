<?php

return [
    'title' => 'Reports',
    'page_title' => 'Reports - Edelweiss Detection',

    'tab' => [
        'data' => 'Report Data',
        'conditions' => 'Edelweiss Conditions',
        'system' => 'About System',
    ],

    'filter' => [
        'from_date' => 'From Date',
        'to_date' => 'To Date',
        'condition' => 'Condition',
        'method' => 'Method',
        'source' => 'Source',
        'all' => 'All',
        'apply' => 'Apply',
        'reset' => 'Reset',
    ],

    'method' => [
        'upload' => 'Upload',
        'camera' => 'Camera',
    ],

    'source' => [
        'admin' => 'Admin',
        'guest' => 'Guest',
    ],

    'stat' => [
        'total_detection' => 'Total Detections',
        'avg_per_day' => 'Average per Day',
        'dominant_condition' => 'Dominant Condition',
        'total_object' => 'Total Objects',
    ],

    'chart' => [
        'trend_title' => 'Detection Trend',
        'trend_subtitle' => ':from — :to (:days days)',
        'distribution_title' => 'Condition Distribution',
        'distribution_subtitle' => 'Selected period',
    ],

    'export' => [
        'pdf' => 'Export PDF',
        'excel' => 'Export Excel',
        'pdf_title' => 'Download report in PDF format',
        'excel_title' => 'Download report in Excel format',
    ],

    'empty' => [
        'title' => 'No data',
        'subtitle' => 'No detections match the selected filters.',
    ],

    'detail_title' => 'Detection Detail',
    'col_dominant' => 'Dominant Condition',
    'trend_empty' => 'Trend will appear when detections are made.',
    'detection_label' => 'Detections',

    'distribution_empty' => 'Distribution chart is empty.',

    'col_date' => 'Date',
    'exporting_pdf' => 'Exporting to PDF...',
    'exporting_excel' => 'Exporting to Excel...',
    'export_success_pdf' => 'PDF exported successfully',
    'export_success_excel' => 'Excel exported successfully',
    'export_failed' => 'Export failed. Try again.',
];
