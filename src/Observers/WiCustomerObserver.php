<?php

namespace Decotatoo\WoocommerceIntegration\Observers;

use Decotatoo\WoocommerceIntegration\Models\WiCustomer;
use Decotatoo\WoocommerceIntegration\Jobs\WiCustomer\Update;

/**
 * TODO:PLACEHOLDER
 */
class WiCustomerObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the WiCustomer "updated" event.
     *
     * @param  WiCustomer  $wiCustomer
     * @return void
     */
    public function updated(WiCustomer $wiCustomer)
    {
        Update::dispatch($wiCustomer)->afterCommit()->onQueue('high');
    }
}
