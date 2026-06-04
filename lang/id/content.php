<?php

return [
    // Admin content management
    'page_title' => 'Konten - Edelweiss Detection',
    'title' => 'Kelola Konten',

    'tab' => [
        'research' => 'R&D',
        'partners' => 'Partner',
        'gallery' => 'Galeri',
    ],

    // R&D — project showcase + tim peneliti
    'research' => [
        'page_title' => 'R&D - Edelweiss Detection',
        'title' => 'Research & Development',
        'subtitle' => 'Penelitian ilmiah di balik sistem deteksi kesehatan bunga Edelweis.',
        'eyebrow' => 'Riset & Inovasi',

        // Showcase project (statis, dari proposal penelitian)
        'project_title' => 'Sistem Cerdas Deteksi Kesehatan Bunga Edelweis Berbasis Citra Digital',
        'project_scheme' => 'Skema Penelitian Dasar Fundamental · Universitas Nusa Putra',
        'project_summary' => 'Bunga Edelweis (Anaphalis javanica) adalah flora endemik pegunungan Indonesia yang terancam oleh perubahan iklim dan aktivitas wisata. Pemantauan kesehatannya selama ini dilakukan manual sehingga subjektif dan kurang efisien. Penelitian ini mengembangkan sistem cerdas berbasis citra digital yang memadukan YOLOv11 untuk mendeteksi objek bunga dan Multi-Layer Perceptron (MLP) untuk mengklasifikasikan kondisi kesehatannya, sebagai dukungan terhadap konservasi tanaman endemik.',

        'highlights_title' => 'Sorotan Penelitian',
        'hl_method_title' => 'Metode Dua Tahap',
        'hl_method_desc' => 'YOLOv11 mendeteksi & melokalisasi bunga, lalu MLP mengklasifikasikan tingkat kesehatannya.',
        'hl_dataset_title' => 'Dataset Lapangan',
        'hl_dataset_desc' => '3.000 citra bunga Edelweis yang diambil langsung dari habitat alami di Gunung Gede Pangrango dan Gunung Lawu.',
        'hl_output_title' => 'Luaran Penelitian',
        'hl_output_desc' => 'Artikel jurnal internasional bereputasi (Data and Metadata) serta HAKI program komputer.',
        'hl_roadmap_title' => 'Peta Jalan',
        'hl_roadmap_desc' => 'Riset berjalan bertahap pada periode 2024–2028, dari pengembangan sistem hingga adopsi.',

        'team_title' => 'Tim Peneliti',
        'team_subtitle' => 'Peneliti di balik pengembangan sistem ini.',
        'team_empty' => 'Belum ada data tim peneliti.',

        // Admin
        'admin_title' => 'Tim Peneliti R&D',
        'admin_subtitle' => 'Kelola daftar peneliti yang tampil di halaman R&D publik.',
        'add_btn' => 'Tambah Peneliti',
        'form_title_add' => 'Tambah Peneliti',
        'form_title_edit' => 'Edit Peneliti',
        'confirm_delete' => 'Hapus peneliti ini secara permanen?',
        'photo_hint' => 'Foto akan dipotong persegi otomatis (512×512). Maks 10MB.',
        'affiliation' => 'Afiliasi',
    ],

    // Partners
    'partners' => [
        'page_title' => 'Partner - Edelweiss Detection',
        'title' => 'Partner',
        'subtitle' => 'Institusi dan organisasi yang bermitra dalam proyek konservasi Edelweiss Jawa.',
        'eyebrow' => 'Kolaborasi',
        'empty' => 'Belum ada data partner.',
        'add_btn' => 'Tambah Partner',
        'edit_btn' => 'Edit',
        'delete_btn' => 'Hapus',
        'form_title_add' => 'Tambah Partner',
        'form_title_edit' => 'Edit Partner',
        'categories' => [
            'institution' => 'Institusi',
            'ngo' => 'NGO',
            'government' => 'Pemerintah',
            'university' => 'Universitas',
        ],
        'confirm_delete' => 'Hapus partner ini secara permanen?',
        'visit_website' => 'Kunjungi Website',
    ],

    // Gallery
    'gallery' => [
        'page_title' => 'Galeri - Edelweiss Detection',
        'title' => 'Galeri Edelweiss',
        'subtitle' => 'Koleksi foto bunga Edelweiss Jawa dari lokasi penelitian di Gunung Gede Pangrango dan Gunung Lawu.',
        'eyebrow' => 'Koleksi Foto',
        'empty' => 'Belum ada foto di galeri.',
        'add_btn' => 'Tambah Foto',
        'edit_btn' => 'Edit',
        'delete_btn' => 'Hapus',
        'form_title_add' => 'Tambah Foto Galeri',
        'form_title_edit' => 'Edit Foto Galeri',
        'categories' => [
            'field' => 'Lapangan',
            'lab' => 'Laboratorium',
            'event' => 'Kegiatan',
            'other' => 'Lainnya',
        ],
        'confirm_delete' => 'Hapus foto ini secara permanen?',
        'image_hint' => 'Format: JPG, PNG, WEBP. Maks 10MB. Gambar akan dikompres otomatis.',
    ],
];
