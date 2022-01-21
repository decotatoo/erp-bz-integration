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
use Illuminate\Support\Facades\Log;
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
     * @var object
     */
    protected $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            /** @var BzCustomer $bzCustomer */
            $bzCustomer = BzCustomer::where('wp_customer_id', $this->request->id)->first();

            if (!$bzCustomer) {
                $bzCustomer = new BzCustomer();
                $bzCustomer->wp_customer_id = $this->request->id;
            }

            $bzCustomer->email = $this->request->email;

            $bzCustomer->first_name = $this->request->first_name;
            $bzCustomer->last_name = $this->request->last_name;

            $bzCustomer->billing_first_name = $this->request->billing['first_name'];
            $bzCustomer->billing_last_name = $this->request->billing['last_name'];
            $bzCustomer->billing_company = $this->request->billing['company'];
            $bzCustomer->billing_address_1 = $this->request->billing['address_1'];
            $bzCustomer->billing_address_2 = $this->request->billing['address_2'];
            $bzCustomer->billing_city = $this->request->billing['city'];
            $bzCustomer->billing_state = $this->request->billing['state'];
            $bzCustomer->billing_postcode = $this->request->billing['postcode'];
            $bzCustomer->billing_country = $this->request->billing['country'];
            $bzCustomer->billing_email = $this->request->billing['email'];
            $bzCustomer->billing_phone = $this->request->billing['phone'];

            $bzCustomer->shipping_first_name = $this->request->shipping['first_name'];
            $bzCustomer->shipping_last_name = $this->request->shipping['last_name'];
            $bzCustomer->shipping_company = $this->request->shipping['company'];
            $bzCustomer->shipping_address_1 = $this->request->shipping['address_1'];
            $bzCustomer->shipping_address_2 = $this->request->shipping['address_2'];
            $bzCustomer->shipping_city = $this->request->shipping['city'];
            $bzCustomer->shipping_state = $this->request->shipping['state'];
            $bzCustomer->shipping_postcode = $this->request->shipping['postcode'];
            $bzCustomer->shipping_country = $this->request->shipping['country'];
            $bzCustomer->shipping_phone = $this->request->shipping['phone'];

            $bzCustomer->date_created_gmt = Carbon::parse($this->request->date_created_gmt);
            $bzCustomer->date_modified_gmt = Carbon::parse($this->request->date_modified_gmt);

            // save without triggering events
            $bzCustomer->saveQuietly(['timestamps' => false]);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $this->fail($th->getMessage());
        }
    }
}
