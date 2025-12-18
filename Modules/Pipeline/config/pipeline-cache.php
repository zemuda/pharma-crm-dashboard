<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pipeline Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the cache settings for the pipeline module.
    |
    */

    'durations' => [
        'types' => 3600, // 1 hour
        'pipelines' => 3600, // 1 hour
        'stages' => 3600, // 1 hour
        'entities' => 300, // 5 minutes
        'statistics' => 300, // 5 minutes
        'model_instances' => 3600, // 1 hour
    ],

    'use_tags' => env('PIPELINE_CACHE_TAGS', true),
    'prefix' => env('PIPELINE_CACHE_PREFIX', 'pipeline:'),
    'store' => env('PIPELINE_CACHE_STORE', null),
];
