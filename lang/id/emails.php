<?php

return [
    'common' => [
        'greeting' => 'Halo :name',
        'regards' => 'Salam,',
        'team' => 'Tim Edelweiss Detection',
        'footer_brand' => 'Edelweiss Detection',
        'footer_note' => 'Email ini dikirim otomatis. Mohon tidak membalas.',
        'visit_website' => 'Kunjungi Website',
    ],

    // Email ke admin saat ada user baru daftar
    'user_registered' => [
        'subject' => '[Edelweiss Detection] Pendaftaran User Baru Menunggu Persetujuan',
        'preheader' => 'User baru telah mendaftar dan menunggu persetujuan Anda.',
        'title' => 'Pendaftaran User Baru',
        'intro' => 'Halo Super Admin,',
        'body' => 'Ada user baru yang mendaftar dan menunggu persetujuan Anda:',
        'name_label' => 'Nama',
        'email_label' => 'Email',
        'registered_at_label' => 'Tanggal Daftar',
        'action_button' => 'Buka Manajemen User',
        'action_note' => 'Silakan login ke panel admin untuk menyetujui atau menolak pendaftaran ini.',
    ],

    // Email ke user saat akunnya disetujui
    'account_approved' => [
        'subject' => '[Edelweiss Detection] Akun Anda Telah Disetujui',
        'preheader' => 'Akun Anda sudah aktif. Silakan login untuk mulai menggunakan sistem.',
        'title' => 'Akun Anda Telah Disetujui',
        'body' => 'Selamat! Akun Anda di Edelweiss Detection sudah disetujui dan siap digunakan.',
        'action_button' => 'Masuk ke Akun',
        'features_title' => 'Yang dapat Anda lakukan sekarang:',
        'feature1' => 'Mendeteksi kesehatan bunga Edelweiss dengan foto atau kamera',
        'feature2' => 'Menyimpan riwayat deteksi untuk referensi',
        'feature3' => 'Melihat statistik dan tren deteksi',
        'feature4' => 'Ekspor laporan dalam PDF atau Excel',
    ],

    // Email ke user saat akun ditolak
    'account_rejected' => [
        'subject' => '[Edelweiss Detection] Pendaftaran Akun Ditolak',
        'preheader' => 'Pendaftaran akun Anda tidak dapat disetujui.',
        'title' => 'Pendaftaran Akun Ditolak',
        'body' => 'Maaf, pendaftaran akun Anda di Edelweiss Detection tidak dapat kami setujui saat ini.',
        'contact_note' => 'Jika Anda merasa ini kesalahan, silakan hubungi admin untuk informasi lebih lanjut.',
    ],
];
