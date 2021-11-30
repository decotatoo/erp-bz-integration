<?php

namespace Decotatoo\WoocommerceIntegration\Http\Controllers;

use Decotatoo\WoocommerceIntegration\Http\Middleware\VerifyWebhookSignature;
use Decotatoo\WoocommerceIntegration\Jobs\Webhook\CustomerCreated;
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

        // Passing the request object or the request's  input?
        CustomerCreated::dispatch($wiCustomer, $request)->afterCommit()->onQueue('webhook');

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

        // Passing the request object or the request's  input?
        CustomerCreated::dispatch($request)->afterCommit()->onQueue('webhook');

        return $this->successMethod();
    }

    protected function handleOrderUpdated(Request $request)
    {
        // $wiOrder->saveSilently();
        // return $this->successMethod();
    }
}
