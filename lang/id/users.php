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

    'tab_all' => 'Semua',
    'tab_pending_short' => 'Pending',
    'tab_approved' => 'Disetujui',
    'tab_rejected' => 'Ditolak',
    'col_user' => 'User',
    'col_status' => 'Status',
    'col_registered' => 'Daftar',
    'col_approved' => 'Disetujui',
    'col_action' => 'Aksi',
    'status_pending' => 'Pending',
    'status_approved' => 'Disetujui',
    'status_rejected' => 'Ditolak',
    'btn_approve' => 'Approve',
    'btn_reject' => 'Tolak',
    'btn_delete' => 'Hapus',
    'you_label' => 'Anda',
    'approved_by' => 'oleh :name',
    'empty_state' => '—',

    'confirm_approve_title' => 'Setujui Pendaftaran?',
    'confirm_approve_msg' => ':name akan dapat akses penuh ke sistem.',
    'confirm_approve_btn' => 'Setujui',
    'confirm_reject_title' => 'Tolak Pendaftaran?',
    'confirm_reject_msg' => ':name tidak akan bisa login. Anda dapat menyetujui ulang nanti.',
    'confirm_reject_btn' => 'Tolak',
    'confirm_delete_title' => 'Hapus User?',
    'confirm_delete_msg' => ':name dan seluruh riwayat deteksinya akan dihapus permanen.',
    'confirm_delete_btn' => 'Hapus',
    'confirm_cancel_btn' => 'Batal',
];
