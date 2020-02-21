<?php

return [
    'analytics' => [
        'enabled' => env('ANALYTICS_ENABLED', false)
    ],
    'revision' => [
        'cleanup' => [
            'enabled' => env('REVISION_CLEANUP_ENABLED', true),
            'limit' => env('REVISION_CLEANUP_LIMIT', 10000)
        ]
    ]
];