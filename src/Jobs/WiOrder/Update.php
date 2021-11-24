<?php

namespace Decotatoo\WoocommerceIntegration\Jobs\WiOrder;

use Decotatoo\WoocommerceIntegration\Models\WiOrder;
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
     * The WiOrder instance.
     *
     * @var WiOrder
     */
    protected $wiOrder;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(WiOrder $wiOrder)
    {
        $this->wiOrder = $wiOrder;
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [
            (new WithoutOverlapping($this->wiOrder->id))->dontRelease()
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
            
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
