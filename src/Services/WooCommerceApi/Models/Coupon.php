<?php

namespace Decotatoo\Bz\Services\WooCommerceApi\Models;

use Decotatoo\Bz\Services\WooCommerceApi\Traits\QueryBuilderTrait;

class Coupon extends BaseModel
{
    use QueryBuilderTrait;
    
    protected $endpoint = 'coupons';
}
