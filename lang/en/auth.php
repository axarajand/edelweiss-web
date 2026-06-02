<?php

return [
    'login' => [
        'title' => 'Sign In to Account',
        'subtitle' => 'Sign in to access the admin panel',
        'email_label' => 'Email',
        'email_placeholder' => 'your@email.com',
        'password_label' => 'Password',
        'remember_me' => 'Remember me',
        'forgot_password' => 'Forgot password?',
        'submit' => 'Sign In',
        'logging_in' => 'Signing in...',
        'no_account' => "Don't have an account?",
        'register_link' => 'Sign up here',
        'back_to_home' => 'Back to home',
    ],

    'register' => [
        'title' => 'Create Account',
        'subtitle' => 'New accounts require admin approval before being able to sign in.',
        'name_label' => 'Full Name',
        'name_placeholder' => 'Your full name',
        'email_label' => 'Email',
        'email_placeholder' => 'your@email.com',
        'password_label' => 'Password',
        'password_placeholder' => 'Minimum 8 characters',
        'password_confirm_label' => 'Confirm Password',
        'password_confirm_placeholder' => 'Re-enter password',
        'submit' => 'Sign Up',
        'registering' => 'Signing up...',
        'has_account' => 'Already have an account?',
        'login_link' => 'Sign in here',
        'approval_note' => 'Your account needs admin approval before it can be used.',
        'notice' => '<strong>Important:</strong> After signing up, your account will have <em>pending</em> status until approved by another admin.',
    ],

    'pending' => [
        'title' => 'Account Pending Approval',
        'message' => 'Your account has been registered. It is currently waiting for admin approval. You will receive an email once your account is approved.',
        'back_to_home' => 'Back to Home',
    ],

    'rejected' => [
        'title' => 'Account Rejected',
        'message' => 'Sorry, your account registration was rejected by the admin. Contact admin for more information.',
    ],
];
