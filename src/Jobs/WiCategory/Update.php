<?php

namespace Decotatoo\WoocommerceIntegration\Jobs\WiCategory;

use App\Models\Festivity;
use Decotatoo\WoocommerceIntegration\Models\CommerceCategory;
use Decotatoo\WoocommerceIntegration\Models\WiCategory;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class Update implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var CommerceCategory|Festivity
     */
    protected $morphCategory;

    /**
     * Create a new job instance.
     *
     * @param CommerceCategory|Festivity $morphCategory
     * @return void
     */
    public function __construct($morphCategory)
    {
        $this->morphCategory = $morphCategory;
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [
            (new WithoutOverlapping($this->morphCategory->id))->dontRelease()
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
            if (!$this->morphCategory->wiCategory) {
                throw new Exception('Commerce Category not exist');
            }

            $result = \Codexshaper\WooCommerce\Facades\Category::update(
                $this->morphCategory->wiCategory->wp_product_category_id,
                [
                    'name' => $this->morphCategory->name,
                    'slug' => Str::slug(!empty(Str::slug($this->morphCategory->slug)) ? $this->morphCategory->slug : $this->morphCategory->name),
                ]
            );
        } catch (\Throwable $th) {
            $this->fail($th->getMessage());
        }
    }
}
