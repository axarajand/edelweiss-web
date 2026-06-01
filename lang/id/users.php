<?php

return [
    'title' => 'Manajemen User',

    'tab' => [
        'pending' => 'Menunggu :count',
        'approved' => 'Aktif :count',
        'all' => 'Semua',
    ],

    'table' => [
        'name' => 'Nama',
        'email' => 'Email',
        'role' => 'Peran',
        'status' => 'Status',
        'registered' => 'Daftar',
        'action' => 'Aksi',
    ],

    'role' => [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'user' => 'User',
    ],

    'status' => [
        'pending' => 'Menunggu',
        'approved' => 'Aktif',
        'rejected' => 'Ditolak',
    ],

    'action' => [
        'approve' => 'Setujui',
        'reject' => 'Tolak',
        'edit_role' => 'Edit Peran',
        'delete' => 'Hapus',
    ],

    'empty' => [
        'no_pending' => 'Tidak ada user menunggu persetujuan',
        'no_users' => 'Belum ada user terdaftar',
    ],

    'confirm' => [
        'approve_title' => 'Setujui User?',
        'approve_message' => 'User :name akan dapat mengakses sistem.',
        'reject_title' => 'Tolak User?',
        'reject_message' => 'User :name akan ditolak aksesnya. Anda dapat menyetujui ulang nanti.',
        'delete_title' => 'Hapus User?',
        'delete_message' => 'User :name dan seluruh riwayatnya akan dihapus permanen.',
    ],
];
