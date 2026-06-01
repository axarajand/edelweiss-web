<?php

return [
    'title' => 'Deteksi Kesehatan',
    'page_title' => 'Deteksi Kesehatan - Edelweiss Detection',
    'subtitle' => 'Upload foto atau gunakan kamera untuk mendeteksi kesehatan bunga Edelweiss Jawa.',

    'mode' => [
        'upload' => 'Upload Gambar',
        'camera' => 'Kamera Realtime',
    ],

    'upload' => [
        'drop_or_click' => 'Drag & drop gambar di sini, atau klik untuk pilih',
        'select_file' => 'Pilih Gambar',
        'caption' => 'Pilih foto bunga Edelweiss yang ingin dicek kesehatannya (.jpg, .png, .webp · maks 10MB)',
        'detect_button' => 'Deteksi Sekarang',
        'detecting' => 'Mendeteksi...',
        'change_image' => 'Ganti Gambar',
    ],

    'camera' => [
        'start' => 'Mulai Kamera',
        'stop' => 'Stop Kamera',
        'capture' => 'Potret',
        'capturing' => 'Menyimpan...',
        'inactive' => 'Kamera belum aktif',
        'live' => 'LIVE',
        'requesting_permission' => 'Meminta izin kamera...',
        'detecting_label' => 'Mendeteksi...',
        'stopped' => 'Berhenti',
        'enter_fullscreen' => 'Layar Penuh',
        'exit_fullscreen' => 'Keluar Layar Penuh',
    ],

    'result' => [
        'title' => 'Hasil Deteksi',
        'objects_detected' => 'objek terdeteksi',
        'no_object_detected' => 'Tidak ada bunga Edelweiss terdeteksi',
        'no_object_hint' => 'Coba arahkan kamera ke bunga, atau upload gambar dengan bunga yang lebih jelas.',
        'show_box' => 'Tampilkan Box',
        'hide_box' => 'Sembunyikan Box',
        'download_annotated' => 'Unduh',
    ],

    'history_recent' => [
        'title' => 'Deteksi Terakhir',
        'view_all' => 'Lihat Semua',
        'empty_title' => 'Belum ada deteksi',
        'empty_subtitle' => 'Riwayat deteksi akan muncul setelah Anda mulai mengecek kesehatan bunga Edelweiss',
    ],

    'errors' => [
        'file_invalid' => 'File harus berupa gambar (.jpg, .png, atau .webp)',
        'file_too_big' => 'Ukuran file terlalu besar. Maksimum 10MB.',
        'timeout' => 'Proses deteksi lebih lama dari biasanya. Coba lagi dengan gambar resolusi lebih kecil.',
        'network' => 'Koneksi terputus. Periksa jaringan Anda lalu coba lagi.',
        'service_offline_title' => 'Service Deteksi Belum Tersedia',
        'service_offline_message' => 'Sistem belum dapat terhubung ke service deteksi saat ini. Silakan coba beberapa saat lagi, atau hubungi admin jika masalah berlanjut.',
        'generic' => 'Terjadi kesalahan saat mendeteksi. Silakan coba lagi.',
        'camera_permission_denied' => 'Izinkan akses kamera di browser, lalu coba lagi.',
        'camera_not_found' => 'Kamera tidak ditemukan pada perangkat ini.',
        'camera_in_use' => 'Kamera sedang digunakan oleh aplikasi lain.',
        'camera_generic' => 'Tidak dapat mengakses kamera.',
    ],
];
