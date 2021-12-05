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
     * @var \App\Models\ProductInCatalog
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
            return;
        }

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
            $bz_product->stock_in_quantity = $this->product->productStockIn->count('id');
            $bz_product->stock_out_quantity = $this->product->productStockOut->count('id');
        }

        $bz_product->save();
        $bz_product->refresh();

        UpdateStock::dispatch($bz_product)->onQueue('high');
    }
}
