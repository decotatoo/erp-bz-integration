<?php

namespace Decotatoo\Bz\Observers;

use Decotatoo\Bz\Models\BzOrderItem;
use Decotatoo\Bz\Models\BzProduct;
use Illuminate\Support\Facades\Log;

class BzProductObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the BzProduct "created" event.
     *
     * @param  BzProduct  $bzProduct
     * @return void
     */
    public function created(BzProduct $bzProduct)
    {
        // Get all dangling order items and link to the new bzProduct record
        $_dangling_order_items = BzOrderItem::query()
            ->where('bz_product_id', null)
            ->where('sku', $bzProduct->product->prod_id)
            ->get();

        foreach ($_dangling_order_items as $item) {
            $item->bzProduct()->associate($bzProduct);
            $item->save();

            Log::debug(__CLASS__ . '::' . __FUNCTION__ . '()');
        }
    }

    /**
     * Handle the BzProduct "updated" event.
     *
     * @param  BzProduct  $bzProduct
     * @return void
     */
    public function updated(BzProduct $bzProduct)
    {
        //
    }

    /**
     * Handle the BzProduct "deleted" event.
     *
     * @param  BzProduct  $bzProduct
     * @return void
     */
    public function deleted(BzProduct $bzProduct)
    {
        //
    }

    /**
     * Handle the BzProduct "restored" event.
     *
     * @param  BzProduct  $bzProduct
     * @return void
     */
    public function restored(BzProduct $bzProduct)
    {
        //
    }
}
