<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    // 'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'paths' => ['*', 'api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],
    // 'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    // 'allowed_origins' => ['*'],
    'allowed_origins' => [
        env('FRONTEND_URL',  'http://localhost:3000'),
        'https://sdssn.org',
        'https://dev.sdssn.org',
        'https://test.sdssn.org',
        'https://sdssn.vercel.app',
        'https://sdssn-frontend.vercel.app',
        'https://sdssn-test.vercel.app',
        'http://localhost:3000',
        'https://reactapp.sdssn.org',
        'https://static.sdssn.org',
        'https://node.sdssn.org',
        'https://next.sdssn.org',
    ],    

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
