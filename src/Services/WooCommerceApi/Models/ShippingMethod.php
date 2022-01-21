<?php

namespace Decotatoo\Bz\Services\WooCommerceApi\Models;

use Decotatoo\Bz\Services\WooCommerceApi\Traits\QueryBuilderTrait;

class ShippingMethod extends BaseModel
{
    use QueryBuilderTrait;
    
    protected $endpoint = 'shipping_methods';
}
