<?php

namespace Decotatoo\Bz\Services;

use Automattic\WooCommerce\Client;
use Codexshaper\WooCommerce\Traits\WooCommerceTrait;

class WooCommerceApi
{
    use WooCommerceTrait;

    /**
     *@var \Automattic\WooCommerce\Client
     */
    protected $client;

    /**
     *@var array
     */
    protected $headers = [];

    /**
     * Build Woocommerce connection.
     *
     * @return void
     */
    public function __construct($baseUrl, $consumerKey, $consumerSecret, $options = [])
    {
        try {
            $this->client = new Client(
                $baseUrl,
                $consumerKey,
                $consumerSecret,
                [
                    'version' => $options['version'],
                    'wp_api' => $options['wp_api'],
                    'verify_ssl' => $options['verify_ssl'],
                    'query_string_auth' => $options['query_string_auth'],
                    'timeout' => $options['timeout'],
                ]
            );

            $this->headers = [
                'header_total'       => $options['header_total'] ?? 'X-WP-Total',
                'header_total_pages' => $options['header_total_pages'] ?? 'X-WP-TotalPages',
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 1);
        }
    }
}
