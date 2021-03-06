<?php

namespace Decotatoo\Bz\Jobs\Product;

use App\Models\ProductInCatalog;
use Decotatoo\Bz\Models\BzProduct;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * TODO:FINAL-TEST
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
        DB::beginTransaction();

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
                'images' => $this->product->pic && Storage::disk('public')->exists('images/product/' . $this->product->pic) ? [['src' => asset('images/product/' . $this->product->pic)]] : [],
                'meta_data' => $this->getMetadata(),
                'manage_stock' => true,
                'stock_quantity' => 0,
                'status' => 'publish',
                'weight' => $this->product->gross_weight,
            ];

            $result = (new Product(App::make('bz.woocommerce')))->create($payload);

            $bzProduct = new BzProduct();
            $bzProduct->product()->associate($this->product);
            $bzProduct->wp_product_id = $result['id'];
            $bzProduct->wp_post_status = $result['status'];
            $saved = $bzProduct->save();

            if ($result && $saved) {
                CalculateStock::dispatch($this->product)->onQueue('low');
            } else {
                throw new Exception("Failed to create Product in WooCommerce. product_id:{$this->product->id}");
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->fail($th);
        }
    }

    /**
     * Get the meta data for the product.
     * 
     * @TODO: add more metadata to expose to ecommerce
     * 
     * @return array 
     */
    private function getMetadata()
    {
        $metadata = [];

        $metadata[] = [
            'key' => '_erp_size',
            'value' => $this->product->size,
        ];

        if (substr($this->product->prod_id, 0, 2) == 'FP') {
            $metadata[] = [
                'key' => '_erp_chocolate_size',
                'value' => $this->product->cho_size,
            ];

            $metadata[] = [
                'key' => '_erp_chocolate_type',
                'value' => $this->product->fp_cklt,
            ];
        }

        $metadata[] = [
            'key' => '_erp_net_weight',
            'value' => $this->product->net_weight * (substr($this->product->prod_id, 0, 2) == 'FP' ? intval($this->product->total_box) : 1),
        ];

        $metadata[] = [
            'key' => '_erp_gross_weight',
            'value' => $this->product->gross_weight,
        ];

        if ($this->product->commerceCategory) {
            $metadata[] = [
                'key' => '_erp_industrial_use_only',
                'value' => strpos($this->product->commerceCategory->name, 'B2B') !== false,
            ];
        }

        $metadata[] = [
            'key' => '_erp_season',
            'value' => $this->product->season,
        ];

        // Quantity per box
        if (substr($this->product->prod_id, 0, 2) == 'FP' && strpos($this->product->qty_box, 'sheet') !== false) {
            $_qty_per_box = strtoupper($this->product->total_box);
        } elseif (substr($this->product->prod_id, 0, 2) == 'TR' && (strpos($this->product->prod_name, 'cd') !== false || strpos($this->product->prod_name, 'tablet') !== false)) {
            $_qty_per_box = strtoupper($this->product->total_box);
        } else {
            if ($this->product->total_box && trim($this->product->total_box) != '') {
                $_qty_per_box = strtoupper($this->product->qty_box) . " ({$this->product->total_box})";
            } else {
                $_qty_per_box = strtoupper($this->product->qty_box);
            }
        }
        $metadata[] = [
            'key' => '_erp_quantity_per_box',
            'value' => $_qty_per_box,
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

        if (!$this->product->commerceCategory) {
            return $categories;
        }

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
