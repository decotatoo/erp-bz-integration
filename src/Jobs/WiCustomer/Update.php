<?php

namespace Decotatoo\WoocommerceIntegration\Jobs\WiCustomer;

use Decotatoo\WoocommerceIntegration\Models\WiCustomer;
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
     * The WiCustomer instance.
     *
     * @var WiCustomer
     */
    protected $wiCustomer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(WiCustomer $wiCustomer)
    {
        $this->wiCustomer = $wiCustomer;
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [
            (new WithoutOverlapping($this->wiCustomer->id))->dontRelease()
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
