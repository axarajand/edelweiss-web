<?php

return [
    'title' => 'User Management',

    'tab' => [
        'pending' => 'Pending :count',
        'approved' => 'Active :count',
        'all' => 'All',
    ],

    'table' => [
        'name' => 'Name',
        'email' => 'Email',
        'role' => 'Role',
        'status' => 'Status',
        'registered' => 'Registered',
        'action' => 'Action',
    ],

    'role' => [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'user' => 'User',
    ],

    'status' => [
        'pending' => 'Pending',
        'approved' => 'Active',
        'rejected' => 'Rejected',
    ],

    'action' => [
        'approve' => 'Approve',
        'reject' => 'Reject',
        'edit_role' => 'Edit Role',
        'delete' => 'Delete',
    ],

    'empty' => [
        'no_pending' => 'No users pending approval',
        'no_users' => 'No registered users yet',
    ],

    'confirm' => [
        'approve_title' => 'Approve User?',
        'approve_message' => 'User :name will be able to access the system.',
        'reject_title' => 'Reject User?',
        'reject_message' => 'User :name access will be rejected. You can re-approve later.',
        'delete_title' => 'Delete User?',
        'delete_message' => 'User :name and all their history will be permanently deleted.',
    ],

    'tab_all' => 'All',
    'tab_pending_short' => 'Pending',
    'tab_approved' => 'Approved',
    'tab_rejected' => 'Rejected',
    'col_user' => 'User',
    'col_status' => 'Status',
    'col_registered' => 'Registered',
    'col_approved' => 'Approved',
    'col_action' => 'Action',
    'status_pending' => 'Pending',
    'status_approved' => 'Approved',
    'status_rejected' => 'Rejected',
    'btn_approve' => 'Approve',
    'btn_reject' => 'Reject',
    'btn_delete' => 'Delete',
    'you_label' => 'You',
    'approved_by' => 'by :name',
    'empty_state' => '—',

    'confirm_approve_title' => 'Approve Registration?',
    'confirm_approve_msg' => ':name will have full access to the system.',
    'confirm_approve_btn' => 'Approve',
    'confirm_reject_title' => 'Reject Registration?',
    'confirm_reject_msg' => ':name will not be able to sign in. You can re-approve later.',
    'confirm_reject_btn' => 'Reject',
    'confirm_delete_title' => 'Delete User?',
    'confirm_delete_msg' => ':name and all their detection history will be permanently deleted.',
    'confirm_delete_btn' => 'Delete',
    'confirm_cancel_btn' => 'Cancel',
];
