<?php

namespace Decotatoo\Bz\Observers;

use Decotatoo\Bz\Models\BzOrder;
use Decotatoo\Bz\Jobs\BzOrder\Update;

/**
 * TODO:PLACEHOLDER
 */
class BzOrderObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the BzOrder "updated" event.
     *
     * @param  BzOrder  $bzOrder
     * @return void
     */
    public function updated(BzOrder $bzOrder)
    {
        Update::dispatch($bzOrder)->afterCommit()->onQueue('high');
    }
}
