<?php

namespace Decotatoo\Bz\Services\WooCommerceApi\Models;

use Decotatoo\Bz\Services\WooCommerceApi\Traits\QueryBuilderTrait;

class TaxClass extends BaseModel
{
    use QueryBuilderTrait;
    
    protected $endpoint = 'taxes/classes';
}
