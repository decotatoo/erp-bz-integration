<?php

namespace Decotatoo\Bz\Observers;

use App\Models\ProductInCatalog;
use Decotatoo\Bz\Jobs\Product\Create;
use Decotatoo\Bz\Jobs\Product\Update;

/**
 * TODO:PLACEHOLDER
 */
class ProductObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the ProductInCatalog "created" event.
     *
     * @param  ProductInCatalog  $productInCatalog
     * @return void
     */
    public function created(ProductInCatalog $productInCatalog)
    {
        if (
            !$productInCatalog->bzProduct
            && $productInCatalog->category === 'catalog'
            && $productInCatalog->customer_id === null
            && $productInCatalog->season !== null
            && $productInCatalog->season !== 'None'
            && $productInCatalog->season !== 'Personalize'
            && $productInCatalog->category_prod !== null
            && ($productInCatalog->festivity->exists() && $productInCatalog->festivity->status === 'Yes')
            && ($productInCatalog->season === 'Four Season' || ($productInCatalog->commerceCatalog()->exists() && $productInCatalog->commerceCatalog->is_published === true))
        ) {
            Create::dispatch($productInCatalog)->afterCommit()->onQueue('default');
        }
    }

    /**
     * Handle the ProductInCatalog "updated" event.
     *
     * @param  ProductInCatalog  $productInCatalog
     * @return void
     */
    public function updated(ProductInCatalog $productInCatalog)
    {
        if ($productInCatalog->bzProduct) {
            Update::dispatch($productInCatalog)->afterCommit()->onQueue('default');
        } else {
            $this->created($productInCatalog);
        }
    }

    /**
     * Handle the ProductInCatalog "deleted" event.
     *
     * @param  ProductInCatalog  $productInCatalog
     * @return void
     */
    public function deleted(ProductInCatalog $productInCatalog)
    {
        //
    }

    /**
     * Handle the ProductInCatalog "restored" event.
     *
     * @param  ProductInCatalog  $productInCatalog
     * @return void
     */
    public function restored(ProductInCatalog $productInCatalog)
    {
        //
    }
}
