<?php

return [

    'dsn' => env('MIX_SENTRY_LARAVEL_DSN', env('SENTRY_LARAVEL_DSN', env('SENTRY_DSN'))),

    'send_default_pii' => true,

    // capture release as git sha
    // 'release' => trim(exec('git --git-dir ' . base_path('.git') . ' log --pretty="%h" -n1 HEAD')),

    'breadcrumbs' => [

        // Capture bindings on SQL queries logged in breadcrumbs
        'sql_bindings' => true,

    ],

];
