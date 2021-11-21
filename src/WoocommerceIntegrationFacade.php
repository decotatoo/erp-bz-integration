<?php

namespace Decotatoo\WoocommerceIntegration;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Decotatoo\WoocommerceIntegration\WoocommerceIntegration
 */
class WoocommerceIntegrationFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'woocommerce-integration';
    }
}
