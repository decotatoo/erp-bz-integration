<?php

namespace Decotatoo\Bz\Jobs\Product;

use App\Models\ProductInCatalog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateStock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The product instance.
     *
     * @var ProductInCatalog
     */
    protected $product;

    /**
     * The stock type [false|in|out] for overriding the stock lookup. If not defined, the stock will be re-calculated from database.
     * 
     * @var bool|string
     */
    protected $op;

    /**
     * The value to be increment or decrement.
     * 
     * @var int
     */
    protected $value;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ProductInCatalog $product, $op = false, $value = 1)
    {
        $this->product = $product;
        $this->op = $op;
        $this->value = $value;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->product->bzProduct) {
            // $this->fail('Product not found in BZ');
            return;
        }

        /** @var \Decotatoo\Bz\Models\BzProduct $bz_product */
        $bz_product = $this->product->bzProduct;

        if ($this->op !== false) {
            switch ($this->op) {
                case 'in':
                    $bz_product->stock_in_quantity += $this->value;
                    break;

                case 'out':
                    $bz_product->stock_out_quantity += $this->value;
                    break;

                default:
                    break;
            }
        } else {
            $bz_product->stock_in_quantity = (clone $this->product)->productStockIn()->isReleaseable()->count('id');
            $bz_product->stock_out_quantity = (clone $this->product)->productStockOut()->isReleaseable()->count('id');
        }

        // Find the pending order, and deduct the stock counter
        $pendingOrderItems = $bz_product->bzOrderItems()->whereHas('bzOrder', function ($query) {
            $query->where('status', 'processing');
        })->get();

        $onhold_quantity = $pendingOrderItems->map(function ($item) {
            return $item->quantity - $item->productStockOuts()->count();
        })->sum();

        $bz_product->save();
        $bz_product->refresh();

        UpdateStock::dispatch($bz_product, $onhold_quantity)->onQueue('high');
    }
}
