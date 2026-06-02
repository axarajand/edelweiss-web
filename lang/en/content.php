<?php

return [
    // Admin content management
    'page_title' => 'Content - Edelweiss Detection',
    'title' => 'Manage Content',

    'tab' => [
        'research' => 'R&D',
        'partners' => 'Partners',
        'gallery' => 'Gallery',
    ],

    // R&D — project showcase + research team
    'research' => [
        'page_title' => 'R&D - Edelweiss Detection',
        'title' => 'Research & Development',
        'subtitle' => 'The scientific research behind the Edelweiss health detection system.',
        'eyebrow' => 'Research & Innovation',

        'project_title' => 'Intelligent Edelweiss Health Detection System Based on Digital Images',
        'project_scheme' => 'Fundamental Basic Research Scheme · Universitas Nusa Putra',
        'project_summary' => 'Edelweiss (Anaphalis javanica) is an endemic mountain flora of Indonesia, threatened by climate change and tourism activity. Its health monitoring has been done manually, making it subjective and inefficient. This research develops an intelligent image-based system combining YOLOv11 for flower object detection and a Multi-Layer Perceptron (MLP) for health condition classification, in support of endemic plant conservation.',

        'highlights_title' => 'Research Highlights',
        'hl_method_title' => 'Two-Stage Method',
        'hl_method_desc' => 'YOLOv11 detects & localizes flowers, then MLP classifies their health level.',
        'hl_dataset_title' => 'Field Dataset',
        'hl_dataset_desc' => '3,000 Edelweiss flower images collected directly from natural habitat at Mount Lawu.',
        'hl_output_title' => 'Research Output',
        'hl_output_desc' => 'A reputable international journal article (Data and Metadata) and a software IP right.',
        'hl_roadmap_title' => 'Roadmap',
        'hl_roadmap_desc' => 'The research runs in stages over 2024–2028, from system development to adoption.',

        'team_title' => 'Research Team',
        'team_subtitle' => 'The researchers behind the development of this system.',
        'team_empty' => 'No research team data yet.',

        'admin_title' => 'R&D Research Team',
        'admin_subtitle' => 'Manage the researchers shown on the public R&D page.',
        'add_btn' => 'Add Researcher',
        'form_title_add' => 'Add Researcher',
        'form_title_edit' => 'Edit Researcher',
        'confirm_delete' => 'Permanently delete this researcher?',
        'photo_hint' => 'Photo will be auto-cropped square (512×512). Max 10MB.',
        'affiliation' => 'Affiliation',
    ],

    // Partners
    'partners' => [
        'page_title' => 'Partners - Edelweiss Detection',
        'title' => 'Partners',
        'subtitle' => 'Institutions and organizations partnering in the Java Edelweiss conservation project.',
        'eyebrow' => 'Collaboration',
        'empty' => 'No partners yet.',
        'add_btn' => 'Add Partner',
        'edit_btn' => 'Edit',
        'delete_btn' => 'Delete',
        'form_title_add' => 'Add Partner',
        'form_title_edit' => 'Edit Partner',
        'categories' => [
            'institution' => 'Institution',
            'ngo' => 'NGO',
            'government' => 'Government',
            'university' => 'University',
        ],
        'confirm_delete' => 'Permanently delete this partner?',
        'visit_website' => 'Visit Website',
    ],

    // Gallery
    'gallery' => [
        'page_title' => 'Gallery - Edelweiss Detection',
        'title' => 'Edelweiss Gallery',
        'subtitle' => 'Photo collection of Java Edelweiss flowers from research sites at Mount Gede Pangrango and Mount Lawu.',
        'eyebrow' => 'Photo Collection',
        'empty' => 'No photos in gallery yet.',
        'add_btn' => 'Add Photo',
        'edit_btn' => 'Edit',
        'delete_btn' => 'Delete',
        'form_title_add' => 'Add Gallery Photo',
        'form_title_edit' => 'Edit Gallery Photo',
        'categories' => [
            'field' => 'Field',
            'lab' => 'Laboratory',
            'event' => 'Event',
            'other' => 'Other',
        ],
        'confirm_delete' => 'Permanently delete this photo?',
        'image_hint' => 'Format: JPG, PNG, WEBP. Max 10MB. Images will be auto-compressed.',
    ],
];
