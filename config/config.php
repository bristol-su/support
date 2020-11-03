<?php

return [
    'url' => env('APP_URL', 'http://localhost'),
    'api_url' => env('API_URL', 'http://localhost/api'),
    'analytics' => [
        'enabled' => env('ANALYTICS_ENABLED', false)
    ],
    'revision' => [
        'cleanup' => [
            'enabled' => env('REVISION_CLEANUP_ENABLED', true),
            'limit' => env('REVISION_CLEANUP_LIMIT', 10000)
        ]
    ],
    'caching' => [
        'filters' => [
            'enabled' => env('FILTER_CACHING_ENABLED', true),
            'duration' => env('FILTER_CACHING_DURATION', 900)
        ]
    ],
    'progress' => [
        'default' => 'database',
        'export' => [
            'database' => [
                'driver' => 'database'
            ],
            'airtable' => [
                'driver' => 'airtable',
                'baseId' => env('AIRTABLE_BASE_ID'),
                'tableName' => env('AIRTABLE_TABLE_NAME'),
                'apiKey' => env('AIRTABLE_API_KEY')
            ]
        ]
    ]
];
