<?php

namespace Decotatoo\Bz\Services\WooCommerceApi\Models;

use Decotatoo\Bz\Services\WooCommerceApi\Traits\QueryBuilderTrait;

class Tax extends BaseModel
{
    use QueryBuilderTrait;
    
    protected $endpoint = 'taxes';
}
