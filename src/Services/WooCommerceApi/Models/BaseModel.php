<?php

namespace Decotatoo\Bz\Services\WooCommerceApi\Models;

use Decotatoo\Bz\Services\WooCommerceApi\WooCommerceApi;

class BaseModel
{
    protected $properties = [];

    /**
     * @var WooCommerceApi
     */
    protected $service;

    protected $endpoint;

    /**
     * Get  Inaccessible Property.
     *
     * @param string $name
     *
     * @return int|string|array|object|null
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * Set Option.
     *
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    public function __construct(WooCommerceApi $service) {
        $this->service = $service;
    }

    public function setEndpoint($endpoint)
    {
        $this->servive->endpoint = $endpoint;

        return $this;
    }
}
