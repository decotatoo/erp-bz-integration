<?php

namespace Decotatoo\WoocommerceIntegration\Observers;

use Decotatoo\WoocommerceIntegration\Models\CommerceCategory;
use Decotatoo\WoocommerceIntegration\Jobs\WiCategory\Create;
use Decotatoo\WoocommerceIntegration\Jobs\WiCategory\Delete;
use Decotatoo\WoocommerceIntegration\Jobs\WiCategory\Update;

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
        if (!$commerceCategory->wiCategory) {
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
        if ($commerceCategory->wiCategory) {
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
        if ($commerceCategory->wiCategory) {
            Delete::dispatch($commerceCategory->wiCategory)->afterCommit()->onQueue('high');
        }
    }

    /**
     * Handle the CommerceCategory "restored" event.
     *
     * @param  CommerceCategory  $commerceCategory
     * @return void
     */
    public function restored(CommerceCategory $commerceCategory)
    {
        //
    }

    /**
     * Handle the CommerceCategory "force deleted" event.
     *
     * @param  CommerceCategory  $commerceCategory
     * @return void
     */
    public function forceDeleted(CommerceCategory $commerceCategory)
    {
        //
    }
}
