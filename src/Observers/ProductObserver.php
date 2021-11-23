<?php

namespace Decotatoo\WoocommerceIntegration\Observers;

use App\Models\ProductInCatalog;
use Decotatoo\WoocommerceIntegration\Jobs\Product\Create;
use Decotatoo\WoocommerceIntegration\Jobs\Product\Update;

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
            !$productInCatalog->wiProduct
            && $productInCatalog->category === 'catalog'
            && $productInCatalog->customer_id === null
            && $productInCatalog->season !== null
            && $productInCatalog->season !== 'None'
            && $productInCatalog->season !== 'Personalize'
            && $productInCatalog->category_prod !== null
            && ($productInCatalog->festivity && $productInCatalog->festivity->status === 'Yes')
            && $productInCatalog->catalog === 'Yes'
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
        if ($productInCatalog->wiProduct) {
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

    /**
     * Handle the ProductInCatalog "force deleted" event.
     *
     * @param  ProductInCatalog  $productInCatalog
     * @return void
     */
    public function forceDeleted(ProductInCatalog $productInCatalog)
    {
        //
    }
}
