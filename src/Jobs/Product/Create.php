<?php

namespace Decotatoo\Bz\Jobs\Product;

use App\Models\ProductInCatalog;
use Decotatoo\Bz\Models\BzProduct;
use Decotatoo\Bz\Jobs\BzCategory\Create as BzCategoryCreate;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

/**
 * TODO:TEST
 * 
 * @task add weight attribute to product
 */
class Create implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The product instance.
     *
     * @var ProductInCatalog
     */
    protected $product;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ProductInCatalog $product)
    {
        $this->product = $product;
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [
            (new WithoutOverlapping($this->product->id))->dontRelease()
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if ($this->product->bzProduct) {
                throw new Exception("Product already exists in WooCommerce. product_id:{$this->product->id} -> wp_product_id:{$this->product->bzProduct->wp_product_id}");
            }

            $categories = $this->getCategories();

            /**
             * If the category have not been created in WooCommerce, create it and reschedule the job.
             */
            if ($categories === null) {
                return $this->release(20);
            }

            $payload = [
                'type' => 'simple',
                'sku' => $this->product->prod_id,
                'slug' => $this->product->prod_id,
                'name' => "{$this->product->prod_name}",
                'regular_price' => "{$this->product->price_cus_idr}",
                'description' => '',
                'short_description' => '',
                'categories' => $categories,
                'tags' => [],
                'images' => $this->product->pic ? ['src' => asset('images/product/' . $this->product->pic)] : [],
                'meta_data' => $this->getMetadata(),
                'manage_stock' => true,
                'stock_quantity' => 0,
                'status' => 'publish'
            ];

            $result = \Codexshaper\WooCommerce\Facades\Product::create($payload);

            $bzProduct = new BzProduct();
            $bzProduct->product()->associate($this->product);
            $bzProduct->wp_product_id = $result['id'];
            $bzProduct->wp_post_status = $result['status'];
            $bzProduct->stock_updated_at = Carbon::now();
            $saved = $bzProduct->save();

            if ($result && $saved) {
                CalculateStock::dispatch($this->product)->onQueue('low');
            } else {
                throw new Exception("Failed to create Product in WooCommerce. product_id:{$this->product->id}");
            }
        } catch (\Throwable $th) {
            $this->fail($th->getMessage());
        }
    }

    /**
     * Get the meta data for the product.
     * 
     * @return array 
     */
    private function getMetadata()
    {
        $metadata = [];

        // $metadata[] = [
        //     'key' => '_erp_product_size',
        //     'value' => '30cm x 30cm',
        // ];

        $metadata[] = [
            'key' => '_erp_season',
            'value' => $this->product->season,
        ];

        return $metadata;
    }

    /**
     * Get the categories for the product.
     *
     * @return array|void if void returned, release the job
     */
    private function getCategories()
    {
        $categories = [];

        // Category
        if (!$this->product->commerceCategory->bzCategory) {
            BzCategoryCreate::dispatch($this->product->commerceCategory)->afterCommit()->onQueue('high');
            return null;
        }

        $categories[] = [
            'id' => $this->product->commerceCategory->bzCategory->wp_product_category_id
        ];

        // Festivity
        if ($this->product->festivity) {
            if (!$this->product->festivity->bzCategory) {
                BzCategoryCreate::dispatch($this->product->festivity)->afterCommit()->onQueue('high');
                return null;
            }

            $categories[] = [
                'id' => $this->product->festivity->bzCategory->wp_product_category_id
            ];
        }

        return $categories;
    }
}
