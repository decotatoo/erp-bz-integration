<?php

namespace Decotatoo\Bz\Observers;

use Decotatoo\Bz\Models\CommerceCategory;
use Decotatoo\Bz\Jobs\BzCategory\Create;
use Decotatoo\Bz\Jobs\BzCategory\Delete;
use Decotatoo\Bz\Jobs\BzCategory\Update;

class CommerceCategoryObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the CommerceCategory "created" event.
     *
     * @param  CommerceCategory  $commerceCategory
     * @return void
     */
    public function created(CommerceCategory $commerceCategory)
    {
        if (!$commerceCategory->bzCategory) {
            Create::dispatch($commerceCategory)->afterCommit()->onQueue('high');
        }
    }

    /**
     * Handle the CommerceCategory "updated" event.
     *
     * @param  CommerceCategory  $commerceCategory
     * @return void
     */
    public function updated(CommerceCategory $commerceCategory)
    {
        if ($commerceCategory->bzCategory) {
            Update::dispatch($commerceCategory)->afterCommit()->onQueue('high');
        } else {
            $this->created($commerceCategory);
        }
    }

    /**
     * Handle the CommerceCategory "deleting" event.
     *
     * @param  CommerceCategory  $commerceCategory
     * @return void
     */
    public function deleting(CommerceCategory $commerceCategory)
    {
        if ($commerceCategory->bzCategory) {
            Delete::dispatch($commerceCategory->bzCategory)->afterCommit()->onQueue('high');
        }
    }
}
