<?php

namespace Decotatoo\WoocommerceIntegration\Observers;

use App\Models\Festivity;
use Decotatoo\WoocommerceIntegration\Jobs\WiCategory\Create;
use Decotatoo\WoocommerceIntegration\Jobs\WiCategory\Delete;
use Decotatoo\WoocommerceIntegration\Jobs\WiCategory\Update;

class FestivityObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the Festivity "created" event.
     *
     * @param  Festivity  $festivity
     * @return void
     */
    public function created(Festivity $festivity)
    {
        if (!$festivity->wiCategory) {
            Create::dispatch($festivity)->afterCommit()->onQueue('high');
        }
    }

    /**
     * Handle the Festivity "updated" event.
     *
     * @param  Festivity  $festivity
     * @return void
     */
    public function updated(Festivity $festivity)
    {
        if ($festivity->wiCategory) {
            Update::dispatch($festivity)->afterCommit()->onQueue('high');
        } else {
            $this->created($festivity);
        }
    }

    /**
     * Handle the Festivity "deleting" event.
     *
     * @param  Festivity  $festivity
     * @return void
     */
    public function deleting(Festivity $festivity)
    {
        if ($festivity->wiCategory) {
            Delete::dispatch($festivity->wiCategory)->afterCommit()->onQueue('high');
        }
    }
}
