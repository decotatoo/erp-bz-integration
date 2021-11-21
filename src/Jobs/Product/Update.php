<?php

namespace Decotatoo\WoocommerceIntegration\Jobs\Product;

use App\Models\ProductInCatalog;
use Decotatoo\WoocommerceIntegration\Jobs\WiCategory\Create as WiCategoryCreate;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;


/**
 * TODO:PLACEHOLDER
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
        if (!$this->product->wiProduct) {
            return $this->fail(new Exception('Product not exists in WooCommerce'));
        }

        $categories = $this->getCategories();

        /**
         * If the category have not been created in WooCommerce, create it and reschedule the job.
         */
        if ($categories === null) {
            return $this->release(20);
        }

        $publish_status = $this->getPublishStatus();

        try {
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
                'images' => $this->getImages(),
                'meta_data' => $this->getMetadata(),
                'status' => $publish_status,
            ];

            $result = \Codexshaper\WooCommerce\Facades\Product::update($this->product->wiProduct->wp_product_id, $payload);

            if (!$result) {
                return $this->fail(new Exception('Failed to update Product in WooCommerce.'));
            }

            $wiProduct = $this->product->wiProduct;
            $wiProduct->wp_post_status = $publish_status;

            if (!$wiProduct->save()) {
                $wiProduct->touch();
            }
        } catch (\Throwable $th) {
            throw $th;
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
        if (!$this->product->commerceCategory->wiCategory) {
            WiCategoryCreate::dispatch($this->product->commerceCategory)->afterCommit()->onQueue('high');
            return null;
        }

        $categories[] = [
            'id' => $this->product->commerceCategory->wiCategory->wp_product_category_id
        ];

        // Festivity
        if ($this->product->festivity) {
            if (!$this->product->festivity->wiCategory) {
                WiCategoryCreate::dispatch($this->product->festivity)->afterCommit()->onQueue('high');
                return null;
            }

            $categories[] = [
                'id' => $this->product->festivity->wiCategory->wp_product_category_id
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

        $productName = "{$this->product->prod_name}";

        if ($this->product->pic) {
            $images[] = [
                'name' => $productName,
                'alt' => $productName,
                'src' => asset('images/product/' . $this->product->pic) // absolute url of image
            ];
        }

        if ($this->product->pic_butik) {
            $images[] = [
                'name' => $productName,
                'alt' => $productName,
                'src' => asset('images/product/' . $this->product->pic_butik) // absolute url of image
            ];
        }

        if ($this->product->pic_butik2) {
            $images[] = [
                'name' => $productName,
                'alt' => $productName,
                'src' => asset('images/product/' . $this->product->pic_butik2) // absolute url of image
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
        //     'key' => '_wi_product_size',
        //     'value' => '30cm x 30cm',
        // ];

        $metadata[] = [
            'key' => '_wi_erp_season',
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
