<?php

return [
    'title' => 'Belajar',
    'page_title' => 'Belajar - Edelweiss Detection',

    'tab' => [
        'conditions' => 'Mengenal Kondisi',
        'system' => 'Tentang Sistem',
    ],

    'conditions' => [
        'card_title' => 'Mengenal Kondisi Kesehatan Bunga Edelweiss Jawa',
        'card_desc' => 'Edelweiss Jawa (Anaphalis javanica) adalah bunga endemik pegunungan Indonesia yang dilindungi. Memahami indikator kesehatannya membantu upaya pelestarian dan budidaya. Berikut kondisi kesehatan yang dapat dikenali sistem.',
        'note' => 'Sistem akan terus dikembangkan untuk mengenali lebih banyak indikator kesehatan Edelweiss di masa mendatang.',

        'mekar' => [
            'desc' => 'Bunga sehat dengan mahkota terbuka. Warna khas terlihat jelas, struktur utuh. Tanaman dalam kondisi sehat.',
            'ciri1' => 'Mahkota terbuka penuh',
            'ciri2' => 'Warna putih krem khas',
            'ciri3' => 'Struktur bunga utuh',
        ],
        'sangat_mekar' => [
            'desc' => 'Bunga di puncak kondisi kesehatannya — ukuran besar, warna cerah. Tanda lingkungan tumbuh yang sangat baik.',
            'ciri1' => 'Ukuran mahkota maksimal',
            'ciri2' => 'Warna sangat cerah',
            'ciri3' => 'Bentuk paling lengkap',
        ],
        'penyemaian' => [
            'desc' => 'Fase awal pertumbuhan dari biji. Indikator regenerasi populasi Edelweiss di habitat aslinya.',
            'ciri1' => 'Tunas hijau muda',
            'ciri2' => 'Akar mulai tumbuh',
            'ciri3' => 'Ukuran masih kecil',
        ],
    ],

    'system' => [
        'card_title' => 'Tentang Sistem Ini',
        'card_desc' => 'Informasi teknis mengenai bagaimana sistem ini mendeteksi kondisi kesehatan bunga Edelweiss, termasuk arsitektur model, dataset yang digunakan, dan tools pengembangan.',

        'arch_title' => 'Arsitektur Model',
        'arch_approach' => 'Pendekatan',
        'arch_approach_value' => 'Dua Tahap',
        'arch_stage1' => 'Tahap 1',
        'arch_stage2' => 'Tahap 2',
        'arch_input' => 'Resolusi Input',
        'arch_conditions' => 'Kondisi Dikenali',
        'arch_conditions_value' => '3 kondisi',

        'metrics_title' => 'Hasil Pelatihan',
        'metrics_yolo_map50' => 'YOLO mAP@0.5',
        'metrics_yolo_map5095' => 'YOLO mAP@0.5:0.95',
        'metrics_yolo_precision' => 'YOLO Precision',
        'metrics_yolo_recall' => 'YOLO Recall',
        'metrics_mlp_acc' => 'MLP Akurasi Validasi',
        'metrics_optimizer' => 'Optimizer',

        'data_title' => 'Data Pelatihan',
        'data_source' => 'Sumber Data',
        'data_source_value' => 'Pengambilan mandiri',
        'data_location' => 'Lokasi Pengambilan Data',
        'data_location_value' => 'Gunung Gede Pangrango & Gunung Lawu',
        'data_location_ggp' => 'Gunung Gede Pangrango',
        'data_location_gl' => 'Gunung Lawu',
        'data_location_maps_ggp' => 'https://maps.google.com/?q=Gunung+Gede+Pangrango+Cianjur',
        'data_location_maps_gl' => 'https://maps.google.com/?q=Gunung+Lawu+Karanganyar',
        'data_annotation_tool' => 'Tools Anotasi',
        'data_total' => 'Total Gambar',
        'data_train' => 'Latih',
        'data_val' => 'Validasi',
        'data_test' => 'Uji',

        'tools_title' => 'Tools yang Digunakan',
        'tools_training' => 'Tempat Pelatihan',
        'tools_gpu' => 'GPU',
        'tools_ml_framework' => 'Framework ML',
        'tools_web' => 'Aplikasi Web',
        'tools_service' => 'Service Deteksi',
        'tools_db' => 'Database',

        'pipeline_title' => 'Bagaimana Sistem Bekerja',
        'pipeline_step1_title' => '1. Gambar Masuk',
        'pipeline_step1_desc' => 'Foto dari upload atau kamera (resolusi bebas)',
        'pipeline_step2_title' => '2. Mencari Bunga',
        'pipeline_step2_desc' => 'YOLOv11 menentukan posisi setiap bunga di gambar',
        'pipeline_step3_title' => '3. Menentukan Kesehatan',
        'pipeline_step3_desc' => 'MLP mengklasifikasikan ke 3 kondisi: Mekar, Sangat Mekar, atau Penyemaian',
        'pipeline_step4_title' => '4. Hasil Tampil',
        'pipeline_step4_desc' => 'Kotak penanda + label kondisi + tingkat keyakinan',
    ],
];
