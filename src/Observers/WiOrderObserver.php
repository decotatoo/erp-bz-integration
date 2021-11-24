<?php

namespace Decotatoo\WoocommerceIntegration\Observers;

use Decotatoo\WoocommerceIntegration\Models\WiOrder;
use Decotatoo\WoocommerceIntegration\Jobs\WiOrder\Update;

/**
 * TODO:PLACEHOLDER
 */
class WiOrderObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the WiOrder "updated" event.
     *
     * @param  WiOrder  $wiOrder
     * @return void
     */
    public function updated(WiOrder $wiOrder)
    {
        Update::dispatch($wiOrder)->afterCommit()->onQueue('high');
    }
}
