<?php

namespace Decotatoo\Bz\Jobs\BzCategory;

use Decotatoo\Bz\Models\BzCategory;
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
     * @var BzCategory
     */
    protected $bzCategory;

    /**
     * Create a new job instance.
     *
     * @param BzCategory $bzCategory
     * @return void
     */
    public function __construct($bzCategory)
    {
        $this->bzCategory = $bzCategory;
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [
            (new WithoutOverlapping($this->bzCategory->id))->dontRelease()
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
            if (!$this->bzCategory) {
                throw new Exception("Commerce Category doesn't exists in WooCommerce");
            }

            $result = \Codexshaper\WooCommerce\Facades\Category::delete($this->bzCategory->wp_product_category_id, ['force' => true]);

            $this->bzCategory->categoryable()->disassociate();
            $this->bzCategory->delete();
        } catch (\Throwable $th) {
            $this->fail($th->getMessage());
        }
    }
}
