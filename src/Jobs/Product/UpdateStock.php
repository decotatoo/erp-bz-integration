<?php

namespace Decotatoo\WoocommerceIntegration\Jobs\Product;

use Decotatoo\WoocommerceIntegration\Models\WiProduct;
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
     * @var \App\Models\WooCommerce\WiProduct
     */
    protected $wi_product;

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->wi_product->id;
    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(WiProduct $wi_product)
    {
        $this->wi_product = $wi_product;
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [
            new WithoutOverlapping($this->wi_product->id)
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
        // if ($this->wi_product->wp_post_status !== 'publish') {
        //     return;
        // }

        $stockQuantity = $this->wi_product->stock_in_quantity - $this->wi_product->stock_out_quantity;

        try {
            $payload = [
                'stock_quantity' => $stockQuantity,
                'stock_status' => $stockQuantity > 0 ? 'instock' : 'outofstock',
            ];

            $result = \Codexshaper\WooCommerce\Facades\Product::update($this->wi_product->wp_product_id, $payload);

            if (!$result) {
                throw new Exception("Failed to update Product wp_product_id:{$this->wi_product->wp_product_id} in WooCommerce.");
            }

            $this->wi_product->stock_updated_at = Carbon::now();
            $this->wi_product->save();
        } catch (\Throwable $th) {
            $this->fail($th->getMessage());
        }
    }
}
