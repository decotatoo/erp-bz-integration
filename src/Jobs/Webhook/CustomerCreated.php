<?php

namespace Decotatoo\WoocommerceIntegration\Jobs\Webhook;

use Decotatoo\WoocommerceIntegration\Models\WiCustomer;
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
     * The WiCustomer instance.
     *
     * @var WiCustomer
     */
    protected $wiCustomer;

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
    public function __construct(WiCustomer $wiCustomer, Request $request)
    {
        $this->wiCustomer = $wiCustomer;
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
            $this->wiCustomer->email = $this->request->email;

            $this->wiCustomer->first_name = $this->request->first_name;
            $this->wiCustomer->last_name = $this->request->last_name;

            $this->wiCustomer->billing_first_name = $this->request->billing['first_name'];
            $this->wiCustomer->billing_last_name = $this->request->billing['last_name'];
            $this->wiCustomer->billing_company = $this->request->billing['company'];
            $this->wiCustomer->billing_address_1 = $this->request->billing['address_1'];
            $this->wiCustomer->billing_address_2 = $this->request->billing['address_2'];
            $this->wiCustomer->billing_city = $this->request->billing['city'];
            $this->wiCustomer->billing_state = $this->request->billing['state'];
            $this->wiCustomer->billing_postcode = $this->request->billing['postcode'];
            $this->wiCustomer->billing_country = $this->request->billing['country'];
            $this->wiCustomer->billing_email = $this->request->billing['email'];
            $this->wiCustomer->billing_phone = $this->request->billing['phone'];

            $this->wiCustomer->shipping_first_name = $this->request->shipping['first_name'];
            $this->wiCustomer->shipping_last_name = $this->request->shipping['last_name'];
            $this->wiCustomer->shipping_company = $this->request->shipping['company'];
            $this->wiCustomer->shipping_address_1 = $this->request->shipping['address_1'];
            $this->wiCustomer->shipping_address_2 = $this->request->shipping['address_2'];
            $this->wiCustomer->shipping_city = $this->request->shipping['city'];
            $this->wiCustomer->shipping_state = $this->request->shipping['state'];
            $this->wiCustomer->shipping_postcode = $this->request->shipping['postcode'];
            $this->wiCustomer->shipping_country = $this->request->shipping['country'];
            $this->wiCustomer->shipping_phone = $this->request->shipping['phone'];

            $this->wiCustomer->date_created_gmt = Carbon::parse($this->request->date_created_gmt);
            $this->wiCustomer->date_modified_gmt = Carbon::parse($this->request->date_modified_gmt);

            // save without triggering events
            $this->wiCustomer->saveQuietly(['timestamps' => false]);
        } catch (\Throwable $th) {
            $this->fail($th->getMessage());
        }
    }
}
