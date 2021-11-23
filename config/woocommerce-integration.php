<?php
// config for Decotatoo/WoocommerceIntegration
return [
    'base_url' => env('WI_APP_URL', 'http://localhost:8080'),
    'credential' => [
        'username' => env('WI_APP_USERNAME', 'admin'),
        'password' => env('WI_APP_PASSWORD', 'secret'),
    ],
    'rest_api' => [
        'base_path' => env('WI_APP_BASE_PATH', '/wp-json/dwi-erp/v1')
    ],
    'secret' => env('WI_APP_SECRET', 'secret'),
];
