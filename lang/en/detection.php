<?php

return [
    'title' => 'Health Detection',
    'h1_guest' => 'Edelweiss Flower Health Detection',
    'page_title' => 'Health Detection - Edelweiss Detection',
    'subtitle' => 'Upload a photo or use the camera to detect Java Edelweiss flower health.',

    'mode' => [
        'upload' => 'Upload Image',
        'camera' => 'Realtime Camera',
    ],

    'upload' => [
        'drop_or_click' => 'Drag & drop image here, or click to select',
        'select_file' => 'Select Image',
        'caption' => 'Choose an Edelweiss flower photo to check its health (.jpg, .png, .webp · max 10MB)',
        'detect_button' => 'Detect Now',
        'detecting' => 'Detecting...',
        'change_image' => 'Change Image',
        'click_to_select' => 'Click to select image',
        'release_file' => 'Release file here',
        'drag_drop_hint' => 'or drag & drop file here',
        'release_to_change' => 'Release to change image',
        'ai_analyzing' => 'AI is Analyzing...',
        'processing' => 'Processing...',
    ],

    'camera' => [
        'start' => 'Start Camera',
        'stop' => 'Stop Camera',
        'capture' => 'Capture',
        'capture_save' => 'Capture & Save',
        'capturing' => 'Saving...',
        'inactive' => 'Camera not active',
        'live' => 'LIVE',
        'requesting_permission' => 'Requesting camera permission...',
        'detecting_label' => 'Detecting...',
        'stopped' => 'Stopped',
        'enter_fullscreen' => 'Fullscreen',
        'exit_fullscreen' => 'Exit Fullscreen',
        'realtime_title' => 'Real-time Camera',
        'realtime_subtitle' => 'The Capture button will activate as soon as Edelweiss flowers are detected on the camera screen.',
        'point_camera' => 'Point camera at Edelweiss flower...',
        'stop_short' => 'Stop',
    ],

    'result' => [
        'title' => 'Detection Results',
        'objects_detected' => 'objects detected',
        'no_object_detected' => 'No Edelweiss flower detected',
        'no_object_hint' => 'Try pointing the camera at flowers, or upload an image with clearer flowers.',
        'show_box' => 'Show Box',
        'hide_box' => 'Hide Box',
        'download_annotated' => 'Download',
        'empty_title' => 'No results yet',
        'empty_subtitle' => 'Upload an image or activate camera to start detecting.',
        'conditions_title' => 'Edelweiss Health Conditions',
    ],

    'history_recent' => [
        'title' => 'Recent Detections',
        'view_all' => 'View All',
        'empty_title' => 'No detections yet',
        'empty_subtitle' => 'Detection history will appear after you start checking Edelweiss flower health',
    ],

    'errors' => [
        'file_invalid' => 'File must be an image (.jpg, .png, or .webp)',
        'file_too_big' => 'File size is too large. Maximum 10MB.',
        'timeout' => 'Detection took longer than usual. Try again with a smaller image.',
        'network' => 'Connection lost. Check your network and try again.',
        'service_offline_title' => 'Detection Service Unavailable',
        'service_offline_message' => 'The system cannot connect to the detection service right now. Please try again in a few moments, or contact admin if the issue persists.',
        'generic' => 'An error occurred during detection. Please try again.',
        'camera_permission_denied' => 'Allow camera access in your browser, then try again.',
        'camera_not_found' => 'No camera found on this device.',
        'camera_in_use' => 'Camera is being used by another application.',
        'camera_generic' => 'Cannot access camera.',
    ],

    'conditions_mekar' => 'Blooming',
    'conditions_sangat_mekar' => 'Full Bloom',
    'conditions_penyemaian' => 'Seedling',

    'guest_cta_title' => 'Want full feature access?',
    'guest_cta_register' => 'Sign up',
    'guest_cta_text' => 'to access the statistics dashboard, dataset management, and detection history.',
];
