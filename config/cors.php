<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],
    // 'allowed_origins' => ['http://localhost', 'https://holeify.com', 'https://www.holeify.com', 'https://app.flutterwave.com', 'https://flutterwave.com'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true
];
