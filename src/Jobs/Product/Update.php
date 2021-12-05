<?php

namespace Decotatoo\Bz\Jobs\Product;

use App\Models\ProductInCatalog;
use Decotatoo\Bz\Jobs\BzCategory\Create as BzCategoryCreate;
use Decotatoo\Bz\Services\WooCommerceApi\Models\Product;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

/**
 * TODO:TEST
 * 
 * @task add weight attribute to product
 */
class Update implements ShouldQueue
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
            if (!$this->product->bzProduct) {
                throw new Exception("Product not exists in WooCommerce. product_id:{$this->product->id}");
            }

            $categories = $this->getCategories();

            /**
             * If the category have not been created in WooCommerce, create it and reschedule the job.
             */
            if ($categories === null) {
                return $this->release(20);
            }

            $publish_status = $this->getPublishStatus();

            $payload = [
                'name' => "{$this->product->prod_name}",
                'regular_price' => "{$this->product->price_cus_idr}",
                'categories' => $categories,
                'tags' => [],
                'meta_data' => $this->getMetadata(),
                'status' => $publish_status,
            ];

            if ($this->product->wasChanged('pic')) {
                $payload['images'] = $this->getImages();
            }

            $result = (new Product(App::make('bz.woocommerce')))->update($this->product->bzProduct->wp_product_id, $payload);

            if (!$result) {
                throw new Exception("Failed to update Product in WooCommerce. product_id:{$this->product->id} -> wp_product_id:{$this->product->bzProduct->wp_product_id}");
            }

            $bzProduct = $this->product->bzProduct;
            $bzProduct->wp_post_status = $publish_status;

            if (!$bzProduct->save()) {
                $bzProduct->touch();
            }
        } catch (\Throwable $th) {
            $this->fail($th->getMessage());
        }
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

    /**
     * Get the images for the product.
     */
    private function getImages()
    {
        $images = [];

        $_online_product = (new Product(App::make('bz.woocommerce')))->find($this->product->bzProduct->wp_product_id);

        if ($_online_product) {
            $images = $_online_product->images;
        }

        if ($this->product->wasChanged('pic') && $this->product->pic) {
            $images[0] = [
                'src' => asset('images/product/' . $this->product->pic) // absolute url of image
            ];
        }

        return $images;
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
     * Get the publish status of the product.
     * 
     * @return string 
     */
    private function getPublishStatus()
    {
        if (
            $this->product->category === 'catalog'
            && $this->product->customer_id === null
            && $this->product->season !== null
            && $this->product->season !== 'None'
            && $this->product->season !== 'Personalize'
            && $this->product->category_prod !== null
            && ($this->product->festivity && $this->product->festivity->status === 'Yes')
            && $this->product->catalog === 'Yes'
        ) {
            return 'publish';
        } else {
            return 'private';
        }
    }
}
