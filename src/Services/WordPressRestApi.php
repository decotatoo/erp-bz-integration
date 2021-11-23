<?php

namespace Decotatoo\WoocommerceIntegration\Services;

use Illuminate\Support\Facades\Http;

class WordPressRestApi
{
    protected $client;

    public function __construct($base_url, $auth = null)
    {
        $this->client = Http::baseUrl($base_url)
            ->withBasicAuth($auth['username'], $auth['password'])
        ;
    }

    public function post($url, $data)
    {
        return $this->client->post($url, $data);
    }

    public function get($url)
    {
        return $this->client->get($url);
    }

    public function getClient()
    {
        return $this->client;
    }
}
