<?php

namespace Decotatoo\Bz\Services\WooCommerceApi\Models;

use Decotatoo\Bz\Services\WooCommerceApi\Traits\QueryBuilderTrait;

class Webhook extends BaseModel
{
    use QueryBuilderTrait;
    
    protected $endpoint = 'webhooks';
}
