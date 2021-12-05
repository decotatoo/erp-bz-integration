<?php

namespace Decotatoo\Bz\Jobs\BzOrder;

use Decotatoo\Bz\Models\BzOrder;
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
     * The BzOrder instance.
     *
     * @var BzOrder
     */
    protected $bzOrder;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BzOrder $bzOrder)
    {
        $this->bzOrder = $bzOrder;
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [
            (new WithoutOverlapping($this->bzOrder->id))->dontRelease()
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
            $payload = [
                'status' => $this->bzOrder->status,
            ];

            $result = \Codexshaper\WooCommerce\Facades\Order::update($this->bzOrder->wp_order_id, $payload);

            if (!$result) {
                throw new Exception("Failed to update Order in WooCommerce. wp_order_id:{$this->bzOrder->wp_order_id}");
            }
        } catch (\Throwable $th) {
            $this->fail($th->getMessage());
        }
    }
}
