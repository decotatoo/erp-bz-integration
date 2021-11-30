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

/**
 * TODO:PLACEHOLDER
 * 
 * @task updating the line_times
 */
class OrderUpdated implements ShouldQueue
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

            $wiOrder = WiOrder::where('wp_order_id', $this->request->id)->first();

            if (!$wiOrder) {
                throw new Exception("Order with wp_order_id:{$this->request->id} not found");
            }

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

            $line_items = $this->request->line_items;

            /**
             * IDEA:
             * 1. find the existing wiOrderItems that match by the current line_items's id
             * 2. load all existing wiOrderItems, than trim by the collection from step 1
             * 3. destroy the result of step 2
             * 4. loop through the line_items and find or create the wiOrderItem
             */

            /**
             * IMPLEMENTED:
             * 1. extract the id from the line_items
             * 2. filter $wiOrder->wiOrderItems by the id that doesn't match with the id from step 1
             * 3. destroy the result of step 2
             * 4. refresh the wiOrder
             * 5. loop through the line_items and find or create the wiOrderItem
             */

            $ids = array_column($line_items, 'id');
            $wiOrder->wiOrderItems->filter(function ($item) use ($ids) {
                return !in_array($item->wp_order_line_item_id, $ids);
            })->each(function ($item) {
                $item->delete();
            });

            $wiOrder->refresh();

            $toSaveItems = [];

            foreach ($line_items as $line_item) {
                $wiOrderItem = $wiOrder->wiOrderItems->where('wp_order_line_item_id', $line_item->id)->first();

                if (!$wiOrderItem) {
                    $wiOrderItem = new WiOrderItem();
                }

                $wiOrderItem->wi_product_id = WiProduct::where('wp_product_id', $line_item->product_id)->first()->id;

                $wiOrderItem->sku = $line_item->sku;
                $wiOrderItem->name = $line_item->name;
                $wiOrderItem->price = $line_item->price;
                $wiOrderItem->quantity = $line_item->quantity;
                $wiOrderItem->subtotal = $line_item->subtotal;
                $wiOrderItem->subtotal_tax = $line_item->subtotal_tax;
                $wiOrderItem->total = $line_item->total;
                $wiOrderItem->total_tax = $line_item->total_tax;
                $wiOrderItem->taxes = $line_item->taxes;
                $wiOrderItem->variation_id = $line_item->variation_id;

                $wiOrderItem->meta_data = $line_item->meta_data;

                $toSaveItems[] = $wiOrderItem;
            }

            $wiOrder->wiOrderItems()->saveMany($toSaveItems);

            $wiOrder->refresh();

            if ($wiOrder->status !== $this->request->status) {
                if ($this->request->status === 'completed') {
                    // TODO: add the order to production and shipment
                }
            }
        } catch (\Throwable $th) {
            $this->fail($th->getMessage());
        }
    }
}
