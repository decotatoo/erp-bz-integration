<?php

namespace Decotatoo\Bz\Jobs\Webhook;

use Decotatoo\Bz\Models\BzCustomer;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CustomerCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The BzCustomer instance.
     *
     * @var BzCustomer
     */
    protected $bzCustomer;

    /**
     * The request instance.
     * 
     * @var Request
     */
    protected $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BzCustomer $bzCustomer, Request $request)
    {
        $this->bzCustomer = $bzCustomer;
        $this->request = $request;
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
            $this->bzCustomer->email = $this->request->email;

            $this->bzCustomer->first_name = $this->request->first_name;
            $this->bzCustomer->last_name = $this->request->last_name;

            $this->bzCustomer->billing_first_name = $this->request->billing['first_name'];
            $this->bzCustomer->billing_last_name = $this->request->billing['last_name'];
            $this->bzCustomer->billing_company = $this->request->billing['company'];
            $this->bzCustomer->billing_address_1 = $this->request->billing['address_1'];
            $this->bzCustomer->billing_address_2 = $this->request->billing['address_2'];
            $this->bzCustomer->billing_city = $this->request->billing['city'];
            $this->bzCustomer->billing_state = $this->request->billing['state'];
            $this->bzCustomer->billing_postcode = $this->request->billing['postcode'];
            $this->bzCustomer->billing_country = $this->request->billing['country'];
            $this->bzCustomer->billing_email = $this->request->billing['email'];
            $this->bzCustomer->billing_phone = $this->request->billing['phone'];

            $this->bzCustomer->shipping_first_name = $this->request->shipping['first_name'];
            $this->bzCustomer->shipping_last_name = $this->request->shipping['last_name'];
            $this->bzCustomer->shipping_company = $this->request->shipping['company'];
            $this->bzCustomer->shipping_address_1 = $this->request->shipping['address_1'];
            $this->bzCustomer->shipping_address_2 = $this->request->shipping['address_2'];
            $this->bzCustomer->shipping_city = $this->request->shipping['city'];
            $this->bzCustomer->shipping_state = $this->request->shipping['state'];
            $this->bzCustomer->shipping_postcode = $this->request->shipping['postcode'];
            $this->bzCustomer->shipping_country = $this->request->shipping['country'];
            $this->bzCustomer->shipping_phone = $this->request->shipping['phone'];

            $this->bzCustomer->date_created_gmt = Carbon::parse($this->request->date_created_gmt);
            $this->bzCustomer->date_modified_gmt = Carbon::parse($this->request->date_modified_gmt);

            // save without triggering events
            $this->bzCustomer->saveQuietly(['timestamps' => false]);
        } catch (\Throwable $th) {
            $this->fail($th->getMessage());
        }
    }
}
