<?php

namespace Decotatoo\Bz\Jobs\Webhook;

use Decotatoo\Bz\Models\BzCustomer;
use Decotatoo\Bz\Models\BzOrder;
use Decotatoo\Bz\Models\BzOrderItem;
use Decotatoo\Bz\Models\BzProduct;
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
            $bzOrder = BzOrder::where('wp_order_id', $this->request->id)->first();

            if (!$bzOrder) {
                throw new Exception("Order with wp_order_id:{$this->request->id} not found");
            }

            if (
                $bzOrder->status === 'completed'
                && $bzOrder->released_date
            ) {
                throw new Exception("Order with wp_order_id:{$this->request->id} already released");
            }

            /** @var BzCustomer $bzCustomer */
            $bzCustomer = BzCustomer::where('wp_customer_id', $this->request->customer_id)->first();

            if ($bzCustomer) {
                $bzOrder->bz_customer_id = $bzCustomer->id;
            }

            $bzOrder->wp_order_id = $this->request->id;
            $bzOrder->cart_hash = $this->request->cart_hash;
            $bzOrder->order_key = $this->request->order_key;

            $bzOrder->status = $this->request->status;
            $bzOrder->currency = $this->request->currency;
            $bzOrder->discount_total = $this->request->discount_total;
            $bzOrder->discount_tax = $this->request->discount_tax;
            $bzOrder->shipping_total = $this->request->shipping_total;
            $bzOrder->shipping_tax = $this->request->shipping_tax;
            $bzOrder->total = $this->request->total;
            $bzOrder->total_tax = $this->request->total_tax;

            $bzOrder->payment_method = $this->request->payment_method;
            $bzOrder->payment_method_title = $this->request->payment_method_title;

            if ($this->request->transaction_id) {
                $bzOrder->transaction_id = $this->request->transaction_id;
            }

            if ($this->request->date_paid) {
                $bzOrder->date_paid = Carbon::parse($this->request->date_paid);
            }

            if ($this->request->date_completed) {
                $bzOrder->date_completed = Carbon::parse($this->request->date_completed);
            }

            $bzOrder->date_created = Carbon::parse($this->request->date_created_gmt);
            $bzOrder->date_modified = Carbon::parse($this->request->date_modified_gmt);

            $bzOrder->billing = $this->request->billing;
            $bzOrder->shipping = $this->request->shipping;
            $bzOrder->shipping_lines = $this->request->shipping_lines;
            $bzOrder->line_items = $this->request->line_items;
            $bzOrder->tax_lines = $this->request->tax_lines;
            $bzOrder->fee_lines = $this->request->fee_lines;
            $bzOrder->coupon_lines = $this->request->coupon_lines;
            $bzOrder->meta_data = $this->request->meta_data;

            // save without triggering events
            $bzOrder->saveQuietly(['timestamps' => false]);

            $bzOrder->refresh();

            $line_items = $this->request->line_items;

            /**
             * IDEA:
             * 1. find the existing bzOrderItems that match by the current line_items's id
             * 2. load all existing bzOrderItems, than trim by the collection from step 1
             * 3. destroy the result of step 2
             * 4. loop through the line_items and find or create the bzOrderItem
             */

            /**
             * IMPLEMENTED:
             * 1. extract the id from the line_items
             * 2. filter $bzOrder->bzOrderItems by the id that doesn't match with the id from step 1
             * 3. destroy the result of step 2
             * 4. refresh the bzOrder
             * 5. loop through the line_items and find or create the bzOrderItem
             */

            $ids = array_column($line_items, 'id');
            $bzOrder->bzOrderItems->filter(function ($item) use ($ids) {
                return !in_array($item->wp_order_line_item_id, $ids);
            })->each(function ($item) {
                $item->delete();
            });

            $bzOrder->refresh();

            $toSaveItems = [];

            foreach ($line_items as $line_item) {
                $bzOrderItem = $bzOrder->bzOrderItems->where('wp_order_line_item_id', $line_item['id'])->first();

                if (!$bzOrderItem) {
                    $bzOrderItem = new BzOrderItem();
                }

                $bzOrderItem->bz_product_id = BzProduct::where('wp_product_id', $line_item['product_id'])->first()->id;

                $bzOrderItem->sku = $line_item['sku'];
                $bzOrderItem->name = $line_item['name'];
                $bzOrderItem->price = $line_item['price'];
                $bzOrderItem->quantity = $line_item['quantity'];
                $bzOrderItem->subtotal = $line_item['subtotal'];
                $bzOrderItem->subtotal_tax = $line_item['subtotal_tax'];
                $bzOrderItem->total = $line_item['total'];
                $bzOrderItem->total_tax = $line_item['total_tax'];
                $bzOrderItem->taxes = $line_item['taxes'];
                $bzOrderItem->variation_id = $line_item['variation_id'];

                $bzOrderItem->meta_data = $line_item['meta_data'];

                $toSaveItems[] = $bzOrderItem;
            }

            $bzOrder->bzOrderItems()->saveMany($toSaveItems);

            $bzOrder->refresh();

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $this->fail($th->getMessage());
        }
    }
}
