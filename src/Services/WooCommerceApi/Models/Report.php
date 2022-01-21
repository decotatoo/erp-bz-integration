<?php

namespace Decotatoo\Bz\Services\WooCommerceApi\Models;

use Decotatoo\Bz\Services\WooCommerceApi\Traits\QueryBuilderTrait;

class Report extends BaseModel
{
    use QueryBuilderTrait;
    
    protected $endpoint = 'reports';

    /**
     * Retrieve all sales.
     *
     * @param array $options
     *
     * @return array
     */
    protected function sales($options = [])
    {
        return (new Query($this->service))
            ->setEndpoint('reports/sales')
            ->all($options);
    }

    /**
     * Retrieve all top sellers.
     *
     * @param array $options
     *
     * @return array
     */
    protected function topSellers($options = [])
    {
        return (new Query($this->service))
            ->setEndpoint('reports/top_sellers')
            ->all($options);
    }

    /**
     * Retrieve all coupons.
     *
     * @param array $options
     *
     * @return array
     */
    protected function coupons($options = [])
    {
        return (new Query($this->service))
            ->setEndpoint('reports/coupons/totals')
            ->all($options);
    }

    /**
     * Retrieve all customers.
     *
     * @param array $options
     *
     * @return array
     */
    protected function customers($options = [])
    {
        return (new Query($this->service))
            ->setEndpoint('reports/customers/totals')
            ->all($options);
    }

    /**
     * Retrieve all orders.
     *
     * @param array $options
     *
     * @return array
     */
    protected function orders($options = [])
    {
        return (new Query($this->service))
            ->setEndpoint('reports/orders/totals')
            ->all($options);
    }

    /**
     * Retrieve all products.
     *
     * @param array $options
     *
     * @return array
     */
    protected function products($options = [])
    {
        return (new Query($this->service))
            ->setEndpoint('reports/products/totals')
            ->all($options);
    }

    /**
     * Retrieve all reviews.
     *
     * @param array $options
     *
     * @return array
     */
    protected function reviews($options = [])
    {
        return (new Query($this->service))
            ->setEndpoint('reports/reviews/totals')
            ->all($options);
    }
}
