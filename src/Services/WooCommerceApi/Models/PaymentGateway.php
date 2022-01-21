<?php

namespace Decotatoo\Bz\Services\WooCommerceApi\Models;

use Decotatoo\Bz\Services\WooCommerceApi\Traits\QueryBuilderTrait;

class PaymentGateway extends BaseModel
{
    use QueryBuilderTrait;
    
    protected $endpoint = 'payment_gateways';
}
