<?php

namespace Decotatoo\Bz\Services\WooCommerceApi\Models;

use Decotatoo\Bz\Services\WooCommerceApi\Traits\QueryBuilderTrait;

class System extends BaseModel
{
    use QueryBuilderTrait;
    
    protected $endpoint;

    /**
     * Retrieve all Items.
     *
     * @param array $options
     *
     * @return array
     */
    protected function status($options = [])
    {
        return (new Query($this->service))
            ->setEndpoint('system_status')
            ->all($options);
    }

    /**
     * Retrieve single tool.
     *
     * @param int   $id
     * @param array $options
     *
     * @return object
     */
    protected function tool($id, $options = [])
    {
        return (new Query($this->service))
            ->setEndpoint('system_status/tools')
            ->find($id, $options);
    }

    /**
     * Retrieve all tools.
     *
     * @param array $options
     *
     * @return array
     */
    protected function tools($options = [])
    {
        return (new Query($this->service))
            ->setEndpoint('system_status/tools')
            ->all($options);
    }

    /**
     * Run tool.
     *
     * @param int   $id
     * @param array $data
     *
     * @return object
     */
    protected function run($id, $data)
    {
        return (new Query($this->service))
            ->setEndpoint('system_status/tools')
            ->update($id, $data);
    }
}
