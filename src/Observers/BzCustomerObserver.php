<?php

namespace Decotatoo\Bz\Observers;

use Decotatoo\Bz\Models\BzCustomer;
use Decotatoo\Bz\Jobs\BzCustomer\Update;

/**
 * TODO:PLACEHOLDER
 */
class BzCustomerObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the BzCustomer "updated" event.
     *
     * @param  BzCustomer  $bzCustomer
     * @return void
     */
    public function updated(BzCustomer $bzCustomer)
    {
        Update::dispatch($bzCustomer)->afterCommit()->onQueue('high');
    }
}
