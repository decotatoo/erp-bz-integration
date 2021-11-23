<?php

namespace Decotatoo\WoocommerceIntegration\Jobs\WiCategory;

use Decotatoo\WoocommerceIntegration\Models\WiCategory;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class Delete implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var WiCategory
     */
    protected $wiCategory;

    /**
     * Create a new job instance.
     *
     * @param WiCategory $wiCategory
     * @return void
     */
    public function __construct($wiCategory)
    {
        $this->wiCategory = $wiCategory;
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [
            (new WithoutOverlapping($this->wiCategory->id))->dontRelease()
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->wiCategory) {
            return $this->fail(new Exception('Commerce Category doesn\'t exists in WooCommerce'));
        }

        try {
            $result = \Codexshaper\WooCommerce\Facades\Category::delete($this->wiCategory->wp_product_category_id, ['force' => true]);

            $this->wiCategory->categoryable()->disassociate();
            $this->wiCategory->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
