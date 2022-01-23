<?php

namespace Decotatoo\Bz\Jobs\BzCustomer;

use Decotatoo\Bz\Models\BzCustomer;
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
     * The BzCustomer instance.
     *
     * @var BzCustomer
     */
    protected $bzCustomer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BzCustomer $bzCustomer)
    {
        $this->bzCustomer = $bzCustomer;
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [
            (new WithoutOverlapping($this->bzCustomer->id))->dontRelease()
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
            $this->fail($th);
        }
    }

}
