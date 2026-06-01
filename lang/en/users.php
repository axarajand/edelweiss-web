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
];
