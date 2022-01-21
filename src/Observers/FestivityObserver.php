<?php

namespace Decotatoo\Bz\Observers;

use App\Models\Festivity;
use Decotatoo\Bz\Jobs\BzCategory\Create;
use Decotatoo\Bz\Jobs\BzCategory\Delete;
use Decotatoo\Bz\Jobs\BzCategory\Update;

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
        if (!$festivity->bzCategory) {
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
        if ($festivity->bzCategory) {
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
        if ($festivity->bzCategory) {
            Delete::dispatch($festivity->bzCategory)->afterCommit()->onQueue('high');
        }
    }
}
