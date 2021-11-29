<?php

namespace Decotatoo\WoocommerceIntegration\Http\Controllers;

use Decotatoo\WoocommerceIntegration\Http\Middleware\VerifyWebhookSignature;
use Decotatoo\WoocommerceIntegration\Models\WiCustomer;
use Decotatoo\WoocommerceIntegration\Models\WiOrder;
use Decotatoo\WoocommerceIntegration\Models\WiProduct;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * TODO:PLACEHOLDER
 */
class WebhookController extends Controller
{
    /**
     * Create a new WebhookController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(VerifyWebhookSignature::class);
    }

    /**
     * Handle successful calls on the controller.
     *
     * @param  array  $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function successMethod($parameters = [])
    {
        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  array  $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function missingMethod($parameters = [])
    {
        return new Response;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $method = 'handle' . Str::studly(str_replace('.', '_', $request->header('x-wc-webhook-topic')));

        if (method_exists($this, $method)) {
            return $this->{$method}($request);
        }

        return $this->missingMethod($request);
    }

    protected function handleCustomerUpdated(Request $request)
    {
        return $this->handleCustomerCreated($request);
    }

    protected function handleCustomerCreated(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'email' => 'required|email',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'billing.first_name' => 'nullable|string',
            'billing.last_name' => 'nullable|string',
            'billing.company' => 'nullable|string',
            'billing.address_1' => 'nullable|string',
            'billing.address_2' => 'nullable|string',
            'billing.city' => 'nullable|string',
            'billing.state' => 'nullable|string',
            'billing.postcode' => 'nullable|string',
            'billing.country' => 'nullable|string',
            'billing.email' => 'nullable|email',
            'billing.phone' => 'nullable|string',
            'shipping.first_name' => 'nullable|string',
            'shipping.last_name' => 'nullable|string',
            'shipping.company' => 'nullable|string',
            'shipping.address_1' => 'nullable|string',
            'shipping.address_2' => 'nullable|string',
            'shipping.city' => 'nullable|string',
            'shipping.state' => 'nullable|string',
            'shipping.postcode' => 'nullable|string',
            'shipping.country' => 'nullable|string',
            'shipping.phone' => 'nullable|string',
            'date_created_gmt' => 'nullable|date',
            'date_modified_gmt' => 'nullable|date',
        ]);

        /** @var WiCustomer $wiCustomer */
        $wiCustomer = WiCustomer::where('wp_customer_id', $request->id)->first();

        if (!$wiCustomer) {
            $wiCustomer = new WiCustomer();
            $wiCustomer->wp_customer_id = $request->id;
        } else {
            $this->validate($request, [
                'email' => ['required', 'email', 'unique:' . WiCustomer::class . ',email,' . $wiCustomer->id],
            ]);
        }

        $wiCustomer->email = $request->email;

        $wiCustomer->first_name = $request->first_name;
        $wiCustomer->last_name = $request->last_name;

        $wiCustomer->billing_first_name = $request->billing['first_name'];
        $wiCustomer->billing_last_name = $request->billing['last_name'];
        $wiCustomer->billing_company = $request->billing['company'];
        $wiCustomer->billing_address_1 = $request->billing['address_1'];
        $wiCustomer->billing_address_2 = $request->billing['address_2'];
        $wiCustomer->billing_city = $request->billing['city'];
        $wiCustomer->billing_state = $request->billing['state'];
        $wiCustomer->billing_postcode = $request->billing['postcode'];
        $wiCustomer->billing_country = $request->billing['country'];
        $wiCustomer->billing_email = $request->billing['email'];
        $wiCustomer->billing_phone = $request->billing['phone'];

        $wiCustomer->shipping_first_name = $request->shipping['first_name'];
        $wiCustomer->shipping_last_name = $request->shipping['last_name'];
        $wiCustomer->shipping_company = $request->shipping['company'];
        $wiCustomer->shipping_address_1 = $request->shipping['address_1'];
        $wiCustomer->shipping_address_2 = $request->shipping['address_2'];
        $wiCustomer->shipping_city = $request->shipping['city'];
        $wiCustomer->shipping_state = $request->shipping['state'];
        $wiCustomer->shipping_postcode = $request->shipping['postcode'];
        $wiCustomer->shipping_country = $request->shipping['country'];
        $wiCustomer->shipping_phone = $request->shipping['phone'];

        $wiCustomer->date_created_gmt = Carbon::parse($request->date_created_gmt);
        $wiCustomer->date_modified_gmt = Carbon::parse($request->date_modified_gmt);

        // save without triggering events
        $wiCustomer->saveQuietly(['timestamps' => false]);

        return $this->successMethod();
    }

    protected function handleProductDeleted(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:' . WiProduct::class . ',wp_product_id'],
        ]);

        $wiProduct = WiProduct::where('wp_product_id', $request->input('id'))->first();

        $wiProduct->delete();

        return $this->successMethod();
    }






































    protected function handleOrderCreated(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'cart_hash' => 'required|string',
            'order_key' => 'required|string',
            'status' => 'required',
        ]);

        $wiOrder = new WiOrder();

        $wiOrder->wp_order_id = $request->id;
        $wiOrder->cart_hash = $request->cart_hash;
        $wiOrder->order_key = $request->order_key;

        $wiOrder->status = $request->status;
        $wiOrder->currency = $request->currency;

        $wiCustomer = WiCustomer::where('wp_customer_id', $request->customer_id)->first();

        if ($wiCustomer) {
            $wiOrder->wi_customer_id = $wiCustomer->id;
        }
        

        $wiOrder->date_created = Carbon::parse($request->date_created_gmt);
        $wiOrder->date_modified = Carbon::parse($request->date_modified_gmt);


        












        // placeholder template

        /** @var WiCustomer $wiCustomer */

        // save without triggering events
        $wiCustomer->saveQuietly(['timestamps' => false]);


        return $this->successMethod();
    }

    protected function handleOrderUpdated(Request $request)
    {
        // $wiOrder->saveSilently();
        // return $this->successMethod();
    }
}
