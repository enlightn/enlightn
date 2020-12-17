<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enlightn Analyzer Classes
    |--------------------------------------------------------------------------
    |
    | The following array lists the "analyzer" classes that will be registered
    | with Enlightn. These analyzers run an analysis on the application via
    | various methods such as static analysis. Feel free to customize it.
    |
    */
    'analyzers' => ['*'],

    // If you wish to skip running some analyzers, list the classes in the array below.
    'exclude_analyzers' => [],

    /*
    |--------------------------------------------------------------------------
    | Enlightn Analyzer Paths
    |--------------------------------------------------------------------------
    |
    | The following array lists the "analyzer" paths that will be searched
    | recursively to find analyzer classes. This option will only be used
    | if the analyzers option above is set to the asterisk wildcard.
    |
    */
    'analyzer_paths' => [
        base_path('vendor/enlightn/enlightn/src/Analyzers'),
        base_path('vendor/enlightn/enlightnpro/src/Analyzers'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Enlightn Base Path
    |--------------------------------------------------------------------------
    |
    | The following array lists the directories that will be scanned for
    | application specific code. By default, we are scanning your app
    | folder, migrations folder and the seeders folder.
    |
    */
    'base_path' => [
        app_path(),
        database_path('migrations'),
        database_path('seeders'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Environment Specific Analyzers
    |--------------------------------------------------------------------------
    |
    | There are some analyzers that are meant to be run for specific environments.
    | The options below specify whether we should skip environment specific
    | analyzers if the environment does not match.
    |
    */
    'skip_env_specific' => env('ENLIGHTN_SKIP_ENVIRONMENT_SPECIFIC', false),

    /*
    |--------------------------------------------------------------------------
    | Analyzer Configurations
    |--------------------------------------------------------------------------
    |
    | The following configuration options pertain to individual analyzers.
    | These are recommended options but feel free to customize them based
    | on your application needs.
    |
    */
    'license_whitelist' => [
        'Apache-2.0', 'Apache2', 'BSD-2-Clause', 'BSD-3-Clause', 'LGPL-2.1-only', 'LGPL-2.1',
        'LGPL-2.1-or-later', 'LGPL-3.0', 'LGPL-3.0-only', 'LGPL-3.0-or-later', 'MIT', 'ISC',
    ],

    // List your commercial packages (licensed by you) below, so that they are not
    // flagged by the License Analyzer.
    'commercial_packages' => [],

    'allowed_permissions' => [
        base_path() => '755',
        app_path() => '755',
        resource_path() => '755',
        storage_path() => '755',
        public_path() => '755',
        config_path() => '755',
        database_path() => '755',
        base_path('routes') => '755',
        app()->bootstrapPath() => '755',
        app()->bootstrapPath('cache') => '755',
        app()->bootstrapPath('app.php') => '644',
        base_path('artisan') => '755',
        public_path('index.php') => '644',
        public_path('server.php') => '644',
    ],

    'writable_directories' => [
        storage_path(),
        app()->bootstrapPath('cache'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Telescope Analyzer Configurations
    |--------------------------------------------------------------------------
    |
    | The following configuration options pertain to Telescope analyzers.
    | These are recommended options but feel free to customize them
    | based on your application needs.
    |
    */
    'disk_usage_threshold' => 90, // %

    'hydration_limit' => 50,

    'request_memory_benchmark' => 50, // MB

    'slow_response_threshold' => 500, // ms
];
