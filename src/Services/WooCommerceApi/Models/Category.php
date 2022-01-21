<?php

namespace Decotatoo\Bz\Services\WooCommerceApi\Models;

use Decotatoo\Bz\Services\WooCommerceApi\Traits\QueryBuilderTrait;

class Category extends BaseModel
{
    use QueryBuilderTrait;
    
    protected $endpoint = 'products/categories';
}
