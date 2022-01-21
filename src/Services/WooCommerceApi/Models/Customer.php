<?php

namespace Decotatoo\Bz\Services\WooCommerceApi\Models;

use Decotatoo\Bz\Services\WooCommerceApi\Traits\QueryBuilderTrait;

class Customer extends BaseModel
{
    use QueryBuilderTrait;
    
    protected $endpoint = 'customers';

    /**
     * Download.
     *
     * @param int $id
     *
     * @return object
     */
    protected function downloads($id, $options = [])
    {
        return (new Query($this->service))
            ->setEndpoint("customers/{$id}/downloads")
            ->all($options);
    }
}
