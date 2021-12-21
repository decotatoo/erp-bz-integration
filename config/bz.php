<?php

return [
    'base_url' => env('BZ_BASE_URL', 'http://localhost:8080'),

    'dashboard_path' => env('BZ_DASHBOARD_PATH', '/wp/wp-admin/'),

    'rest_api' => [
        'base_path' => env('BZ_REST_BASE_PATH', '/wp-json/dwi-erp/v1'),
        'credential' => [
            'username' => env('BZ_REST_USERNAME', '__admin__'),
            'password' => env('BZ_REST_PASSWORD', '__secret__'),
        ],
    ],

    'webhook' => [
        'secret' => env('BZ_WEBHOOK_SECRET', false),
    ],

    'woocommerce' => [
        'store_url' => env('BZ_BASE_URL', 'http://localhost:8080'),
        'consumer_key' => env('BZ_WOOCOMMERCE_CONSUMER_KEY', '__key__'),
        'consumer_secret' => env('BZ_WOOCOMMERCE_CONSUMER_SECRET', '__secret__'),
        'verify_ssl' => env('BZ_WOOCOMMERCE_VERIFY_SSL', false),
        'api_version' => env('BZ_WOOCOMMERCE_API_VERSION', 'v3'),
        'wp_api' => env('BZ_API_INTEGRATION', true),
        'query_string_auth' => env('BZ_WOOCOMMERCE_WP_QUERY_STRING_AUTH', false),
        'timeout' => env('BZ_WOOCOMMERCE_WP_TIMEOUT', 15),
        'header_total' => env('BZ_WOOCOMMERCE_WP_HEADER_TOTAL', 'X-WP-Total'),
        'header_total_pages' => env('BZ_WOOCOMMERCE_WP_HEADER_TOTAL_PAGES', 'X-WP-TotalPages'),
    ],
];
