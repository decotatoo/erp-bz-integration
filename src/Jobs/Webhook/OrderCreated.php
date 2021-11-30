<?php

namespace Decotatoo\WoocommerceIntegration\Jobs\Webhook;

use Decotatoo\WoocommerceIntegration\Models\WiCustomer;
use Decotatoo\WoocommerceIntegration\Models\WiOrder;
use Decotatoo\WoocommerceIntegration\Models\WiOrderItem;
use Decotatoo\WoocommerceIntegration\Models\WiProduct;
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

class OrderCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
    public function __construct(Request $request)
    {
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
            (new WithoutOverlapping($this->request->id))->dontRelease()
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
            /** @var WiCustomer $wiCustomer */
            $wiCustomer = WiCustomer::where('wp_customer_id', $this->request->customer_id)->first();

            $wiOrder = new WiOrder();

            if ($wiCustomer) {
                $wiOrder->wi_customer_id = $wiCustomer->id;
            }

            $wiOrder->wp_order_id = $this->request->id;
            $wiOrder->cart_hash = $this->request->cart_hash;
            $wiOrder->order_key = $this->request->order_key;

            $wiOrder->status = $this->request->status;
            $wiOrder->currency = $this->request->currency;
            $wiOrder->discount_total = $this->request->discount_total;
            $wiOrder->discount_tax = $this->request->discount_tax;
            $wiOrder->shipping_total = $this->request->shipping_total;
            $wiOrder->shipping_tax = $this->request->shipping_tax;
            $wiOrder->total = $this->request->total;
            $wiOrder->total_tax = $this->request->total_tax;

            $wiOrder->payment_method = $this->request->payment_method;
            $wiOrder->payment_method_title = $this->request->payment_method_title;

            if ($this->request->transaction_id) {
                $wiOrder->transaction_id = $this->request->transaction_id;
            }

            if ($this->request->date_paid) {
                $wiOrder->date_paid = Carbon::parse($this->request->date_paid);
            }

            if ($this->request->date_completed) {
                $wiOrder->date_completed = Carbon::parse($this->request->date_completed);
            }

            $wiOrder->date_created = Carbon::parse($this->request->date_created_gmt);
            $wiOrder->date_modified = Carbon::parse($this->request->date_modified_gmt);


            $wiOrder->billing = $this->request->billing;
            $wiOrder->shipping = $this->request->shipping;
            $wiOrder->shipping_lines = $this->request->shipping_lines;
            $wiOrder->line_items = $this->request->line_items;
            $wiOrder->tax_lines = $this->request->tax_lines;
            $wiOrder->fee_lines = $this->request->fee_lines;
            $wiOrder->coupon_lines = $this->request->coupon_lines;
            $wiOrder->meta_data = $this->request->meta_data;

            // save without triggering events
            $wiOrder->saveQuietly(['timestamps' => false]);

            $wiOrder->refresh();

            $items = [];

            // adding the order item to wi_order_items table
            foreach ($this->request->line_items as $line_item) {
                $item = new WiOrderItem();

                $item->wp_order_line_item_id = $line_item->id;
                $item->wi_product_id = WiProduct::where('wp_product_id', $line_item->product_id)->first()->id;

                $item->sku = $line_item->sku;
                $item->name = $line_item->name;
                $item->price = $line_item->price;
                $item->quantity = $line_item->quantity;
                $item->subtotal = $line_item->subtotal;
                $item->subtotal_tax = $line_item->subtotal_tax;
                $item->total = $line_item->total;
                $item->total_tax = $line_item->total_tax;
                $item->taxes = $line_item->taxes;
                $item->variation_id = $line_item->variation_id;

                $item->meta_data = $line_item->meta_data;

                $items[] = $item;
            }

            $wiOrder->wiOrderItems()->saveMany($items);

            if ($this->request->status === 'completed') {
                // TODO: add the order to production and shipment
            }
        } catch (\Throwable $th) {
            $this->fail($th->getMessage());
        }
    }
}
