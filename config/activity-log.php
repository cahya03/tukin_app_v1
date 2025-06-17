<?php
return [
    'enabled' => env('ACTIVITY_LOG_ENABLED', true),
    'cleanup_days' => env('ACTIVITY_LOG_CLEANUP_DAYS', 90),
    'max_retention' => env('ACTIVITY_LOG_MAX_RETENTION', 365),
    'excluded_paths' => [
        'css', 'js', 'images', 'fonts', 'favicon.ico'
    ],
    'logged_methods' => ['POST', 'PUT', 'PATCH', 'DELETE'],
];