<?php

return [
    'common' => [
        'greeting' => 'Hello :name',
        'regards' => 'Best regards,',
        'team' => 'The Edelweiss Detection Team',
        'footer_brand' => 'Edelweiss Detection',
        'footer_note' => 'This email is sent automatically. Please do not reply.',
        'visit_website' => 'Visit Website',
    ],

    'user_registered' => [
        'subject' => '[Edelweiss Detection] New User Registration Pending Approval',
        'preheader' => 'A new user has registered and is waiting for your approval.',
        'title' => 'New User Registration',
        'intro' => 'Hello Super Admin,',
        'body' => 'A new user has registered and is waiting for your approval:',
        'name_label' => 'Name',
        'email_label' => 'Email',
        'registered_at_label' => 'Registered At',
        'action_button' => 'Open User Management',
        'action_note' => 'Please log in to the admin panel to approve or reject this registration.',
    ],

    'account_approved' => [
        'subject' => '[Edelweiss Detection] Your Account Has Been Approved',
        'preheader' => 'Your account is now active. Please sign in to start using the system.',
        'title' => 'Your Account Has Been Approved',
        'body' => 'Congratulations! Your Edelweiss Detection account has been approved and is ready to use.',
        'action_button' => 'Sign In to Account',
        'features_title' => 'What you can do now:',
        'feature1' => 'Detect Edelweiss flower health using photos or camera',
        'feature2' => 'Save detection history for reference',
        'feature3' => 'View detection statistics and trends',
        'feature4' => 'Export reports in PDF or Excel format',
    ],

    'account_rejected' => [
        'subject' => '[Edelweiss Detection] Account Registration Rejected',
        'preheader' => 'Your account registration cannot be approved.',
        'title' => 'Account Registration Rejected',
        'body' => 'Sorry, your Edelweiss Detection account registration cannot be approved at this time.',
        'contact_note' => 'If you believe this is a mistake, please contact admin for more information.',
    ],
];
