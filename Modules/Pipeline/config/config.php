<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pipeline Module Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the Pipeline module.
    |
    */

    'name' => 'Pipeline',

    /*
    |--------------------------------------------------------------------------
    | Default Pipeline Settings
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'pipeline' => [
            'is_active' => true,
            'is_default' => false,
            'order' => 0,
        ],
        'stage' => [
            'color' => '#3b82f6',
            'probability' => 0,
            'order' => 0,
            'is_default' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => env('PIPELINE_CACHE_ENABLED', true),
        'duration' => [
            'pipelines' => 3600, // 1 hour
            'stages' => 3600, // 1 hour
            'types' => 3600, // 1 hour
            'entities' => 300, // 5 minutes
            'statistics' => 300, // 5 minutes
        ],
        'prefix' => 'pipeline:',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pipeline Types Configuration
    |--------------------------------------------------------------------------
    */
    'types' => [
        // These will be loaded from TypeConfigRegistry
    ],

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    */
    'api' => [
        'prefix' => 'api/v1',
        'middleware' => ['api', 'auth:sanctum'],
        'rate_limit' => env('PIPELINE_API_RATE_LIMIT', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | UI Configuration
    |--------------------------------------------------------------------------
    */
    'ui' => [
        'items_per_page' => 20,
        'max_stages_per_pipeline' => 20,
        'default_colors' => [
            '#3b82f6', // blue
            '#10b981', // green
            '#f59e0b', // yellow
            '#ef4444', // red
            '#8b5cf6', // purple
            '#ec4899', // pink
            '#06b6d4', // cyan
            '#f97316', // orange
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications Configuration
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'entity_moved' => true,
        'pipeline_updated' => true,
        'stage_updated' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'pipeline' => [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ],
        'stage' => [
            'name' => 'required|string|max:255',
            'probability' => 'nullable|numeric|min:0|max:100',
        ],
    ],
];
