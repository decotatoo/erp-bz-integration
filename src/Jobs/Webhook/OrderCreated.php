<?php

namespace Decotatoo\Bz\Jobs\Webhook;

use Decotatoo\Bz\Models\BzCustomer;
use Decotatoo\Bz\Models\BzOrder;
use Decotatoo\Bz\Models\BzOrderItem;
use Decotatoo\Bz\Models\BzProduct;
use Decotatoo\Bz\Models\PackingSimulation;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderCreated implements ShouldQueue
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
        DB::beginTransaction();
        try {
            $bzOrder = BzOrder::where('wp_order_id', $this->request->id)->first();

            if (!$bzOrder) {
                $bzOrder = new BzOrder();
                $bzOrder->wp_order_id = $this->request->id;

                // $uid is a Sales order number with format "SOOLYY-MMXXXX" where "SOOL" is the stand for "Sales Order Online", "YY" is the year of the order and "XXXX" is the order line number. Example: "SOOL19-020001"
                
                // @TODO: fix format
                $prefix = sprintf('SOOL%s', Carbon::now()->format('y-m'));
                $next_increment = BzOrder::where('uid', 'like', $prefix.'%')->count() + 1;

                $bzOrder->uid = sprintf('%s%04d', $prefix, $next_increment);
            }

            /** @var BzCustomer $bzCustomer */
            $bzCustomer = BzCustomer::where('wp_customer_id', $this->request->customer_id)->first();
            
            
            /** @var BzOrder $bzOrder */
            if ($bzCustomer) {
                $bzOrder->bz_customer_id = $bzCustomer->id;
            }

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

            $bzOrder->date_created = Carbon::parse($this->request->date_created_gmt);
            $bzOrder->date_modified = Carbon::parse($this->request->date_modified_gmt);

            if ($this->request->date_paid) {
                $bzOrder->date_paid = Carbon::parse($this->request->date_paid);
            }

            if ($this->request->date_completed) {
                $bzOrder->date_completed = Carbon::parse($this->request->date_completed);
            }

            $bzOrder->billing = $this->request->billing;
            $bzOrder->shipping = $this->request->shipping;
            $bzOrder->shipping_lines = $this->request->shipping_lines;
            $bzOrder->line_items = $this->request->line_items;
            $bzOrder->tax_lines = $this->request->tax_lines;
            $bzOrder->fee_lines = $this->request->fee_lines;
            $bzOrder->coupon_lines = $this->request->coupon_lines;
            $bzOrder->meta_data = $this->request->meta_data;

            // link the packing simulation to the order
            $packing_simulation_key = array_search('_dwi_simulation_id', array_column($this->request->meta_data, 'key'));
            if (false !== $packing_simulation_key) {
                $simulation = PackingSimulation::find($this->request->meta_data[$packing_simulation_key]['value']);
                /** @var PackingSimulation $simulation */
                if ($simulation) {
                    $simulation->bzOrder()->associate($bzOrder);
                    $simulation->save();
                }
            }

            // save without triggering events
            $bzOrder->saveQuietly();

            $bzOrder->refresh();

            $items = [];

            // adding the order item to bz_order_items table
            foreach ($this->request->line_items as $line_item) {
                $item = new BzOrderItem();

                $item->wp_order_line_item_id = $line_item['id'];
                $item->bz_product_id = BzProduct::where('wp_product_id', $line_item['product_id'])->first()->id;

                $item->sku = $line_item['sku'];
                $item->name = $line_item['name'];
                $item->price = $line_item['price'];
                $item->quantity = $line_item['quantity'];
                $item->subtotal = $line_item['subtotal'];
                $item->subtotal_tax = $line_item['subtotal_tax'];
                $item->total = $line_item['total'];
                $item->total_tax = $line_item['total_tax'];
                $item->taxes = $line_item['taxes'];
                $item->variation_id = $line_item['variation_id'];

                $item->meta_data = $line_item['meta_data'];

                $items[] = $item;
            }

            $bzOrder->bzOrderItems()->saveMany($items);
            
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->fail($th);
        }
    }
}
