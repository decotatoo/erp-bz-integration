<?php

namespace Decotatoo\Bz\Jobs\Product;

use Decotatoo\Bz\Models\BzProduct;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class UpdateStock implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The product instance.
     *
     * @var \App\Models\WooCommerce\BzProduct
     */
    protected $bz_product;

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->bz_product->id;
    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BzProduct $bz_product)
    {
        $this->bz_product = $bz_product;
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [
            new WithoutOverlapping($this->bz_product->id)
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // if product doesn't meet condition for public visibility, skip
        // if ($this->bz_product->wp_post_status !== 'publish') {
        //     return;
        // }

        $stockQuantity = $this->bz_product->stock_in_quantity - $this->bz_product->stock_out_quantity;

        try {
            $payload = [
                'stock_quantity' => $stockQuantity,
                'stock_status' => $stockQuantity > 0 ? 'instock' : 'outofstock',
            ];

            $result = \Codexshaper\WooCommerce\Facades\Product::update($this->bz_product->wp_product_id, $payload);

            if (!$result) {
                throw new Exception("Failed to update Product wp_product_id:{$this->bz_product->wp_product_id} in WooCommerce.");
            }

            $this->bz_product->stock_updated_at = Carbon::now();
            $this->bz_product->save();
        } catch (\Throwable $th) {
            $this->fail($th->getMessage());
        }
    }
}
